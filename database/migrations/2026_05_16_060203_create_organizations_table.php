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
        if (
            str_contains(
                config('services.laravelpassport.scope'),
                'organization'
            )
        ) {
            Schema::create('organizations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('avonez_id')
                    ->unique();
                $table->string('slug');
                $table->string('type');
                $table->string('status');
                $table->string('email')
                    ->nullable();
                $table->string('phone')
                    ->nullable();
                $table->string('website')
                    ->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            str_contains(
                config('services.laravelpassport.scope'),
                'organization'
            )
        ) {
            Schema::dropIfExists('organizations');
        }
    }
};
