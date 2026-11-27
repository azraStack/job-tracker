<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();
        });

        $userId = DB::table('users')->orderBy('id')->value('id');

        if ($userId === null && DB::table('companies')->exists()) {
            throw new RuntimeException('Cannot assign existing companies to a user: no users exist.');
        }

        if ($userId !== null) {
            DB::table('companies')
                ->whereNull('user_id')
                ->update(['user_id' => $userId]);
        }

        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->dropUnique(['name']);
            $table->unique(['user_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'name']);
            $table->unique(['name']);
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
