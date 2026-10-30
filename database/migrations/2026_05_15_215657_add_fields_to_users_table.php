<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('avonez_id')
                ->unique()
                ->after('id');
            $table->string('mobile_number')
                ->before('email');

            $table->text('access_token');
            $table->text('refresh_token');

            $table->string('email')
                ->nullable()
                ->change();

            $table->boolean('email_verified')
                ->after('email_verified_at')
                ->default(false);
            $table->boolean('mobile_number_verified')
                ->before('email_verified')
                ->default(false);

            $table->dropColumn('password');
            $table->dropColumn('remember_token');
            $table->dropColumn('email_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
