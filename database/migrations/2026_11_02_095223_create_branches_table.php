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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('avonez_id')
                    ->unique();
            $table->foreignId('organization_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('address');
            $table->string('status')->default('pending');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('metadata');
            $table->timestamps();
        });

        Schema::table('organization_user', function (Blueprint $table) {
            $table->foreignId('branch_id')
                ->after('organization_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            if (!Schema::hasIndex('organization_user', ['organization_id', 'branch_id', 'user_id'], 'unique')) {
                $table->unique(['organization_id', 'branch_id', 'user_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organization_user', function (Blueprint $table) {
            if (Schema::hasIndex('organization_user', ['organization_id', 'branch_id', 'user_id'], 'unique')) {
                $table->dropUnique(['organization_id', 'branch_id', 'user_id']);
            }
            $table->dropColumn('branch_id');
        });
        Schema::dropIfExists('branches');
    }
};
