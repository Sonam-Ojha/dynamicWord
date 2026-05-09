<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('templates', 'branch_id')) {
            Schema::table('templates', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->after('bank_id')
                    ->constrained('bank_branches')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropConstrainedForeignId('branch_id');
        });
    }
};
