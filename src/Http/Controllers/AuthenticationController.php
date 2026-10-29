<?php

namespace AvonezAuthClient\Http\Controllers;

use AvonezAuthClient\Models\Organization;
use AvonezAuthClient\Models\OrganizationUser;
use AvonezAuthClient\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AuthenticationController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $state = Str::random(40);

        $request->session()->put('oauth_state', $state);

        $query = http_build_query([
            'client_id' => config('avonez-auth-client.client_id'),
            'redirect_uri' => config('avonez-auth-client.redirect'),
            'response_type' => 'code',
            'scope' => config('avonez-auth-client.scope'),
            'state' => $state,
        ]);

        return redirect($this->passportUrl('/oauth/authorize') . '?' . $query);
    }

    public function redirect(Request $request): RedirectResponse
    {
        $userModelClass = config('avonez-auth-client.user-model', User::class);

        abort_if(
            $request->has('error'),
            403,
            $request->input('error_description', 'Authorization denied.')
        );

        $expectedState = $request->session()->pull('oauth_state');

        abort_unless(
            $expectedState !== null && hash_equals($expectedState, (string) $request->input('state')),
            419,
            'Invalid OAuth state.'
        );

        $token = Http::asForm()
            ->post($this->passportUrl('/oauth/token'), [
                'grant_type' => 'authorization_code',
                'client_id' => config('avonez-auth-client.client_id'),
                'client_secret' => config('avonez-auth-client.client_secret'),
                'redirect_uri' => config('avonez-auth-client.redirect'),
                'code' => $request->input('code'),
            ])
            ->throw()
            ->json();


        $user = Http::withToken($token['access_token'])
            ->acceptJson()
            ->get($this->passportUrl('/api/user'))
            ->throw()
            ->json();

        $oauthUser = $user['data'];

        $user = $userModelClass::updateOrCreate(
            ['avonez_id' => $oauthUser['id']],
            [
                'name' => $oauthUser['name'],
                'mobile_number' => $oauthUser['mobile_number'],
                'email' => $oauthUser['email'],
                'mobile_number_verified' => $oauthUser['mobile_number_verified'],
                'email_verified' => $oauthUser['email_verified'],
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'],
            ]
        );

        $this->handleOrganization($user);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    private function handleOrganization(User $user)
    {
        $organizationModelClass = config('avonez-auth-client.organization-model', Organization::class);
        $organizationUserModelClass = config('avonez-auth-client.organization-user-model', OrganizationUser::class);

        $accessToken = $user->access_token;

        $scopes = $this->scopesFromJwt($accessToken);

        $canReadOrganization = array_search('organizations:read', $scopes);

        if (!$canReadOrganization) {
            return;
        }

        $organizations = Http::withToken($accessToken)
            ->acceptJson()
            ->get($this->passportUrl('/api/organizations'))
            ->throw()
            ->json();

        $oauthOrganizations = $organizations['data'];

        foreach ($oauthOrganizations as $oauthOrganization) {
            $organization = $organizationModelClass::updateOrCreate(
                ['avonez_id' => $oauthOrganization['id']],
                [
                    'slug' => $oauthOrganization['slug'],
                    'type' => $oauthOrganization['type'],
                    'status' => $oauthOrganization['status'],
                    'email' => $oauthOrganization['email'],
                    'phone' => $oauthOrganization['phone'],
                    'website' => $oauthOrganization['website'],
                ],
            );
            $organizationUserModelClass::updateOrCreate(
                [
                    'organization_id' => $organization->id,
                    'user_id' => $user->id,
                ],
                [
                    'designation' => $oauthOrganization['designation'],
                ],
            );
        }
    }

    private function passportUrl(string $path): string
    {
        return rtrim((string) config('avonez-auth-client.host'), '/') . '/' . ltrim($path, '/');
    }

    /**
     * @return array<int, string>
     */
    private function scopesFromJwt(string $jwt): array
    {
        $parts = explode('.', $jwt);

        if (count($parts) < 2) {
            return [];
        }

        $payload = json_decode(base64_decode(strtr($parts[1], '-_', '+/')), true);

        return is_array($payload['scopes'] ?? null) ? $payload['scopes'] : [];
    }
}
