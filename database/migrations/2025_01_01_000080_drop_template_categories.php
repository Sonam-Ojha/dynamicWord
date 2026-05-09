<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('templates', 'category_id')) {
            $db = DB::getDatabaseName();

            $hasFk = DB::selectOne(
                "SELECT COUNT(*) AS c FROM information_schema.TABLE_CONSTRAINTS
                 WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = 'templates'
                   AND CONSTRAINT_NAME = 'templates_category_id_foreign'
                   AND CONSTRAINT_TYPE = 'FOREIGN KEY'",
                [$db]
            )->c > 0;

            if ($hasFk) {
                Schema::table('templates', fn (Blueprint $t) => $t->dropForeign(['category_id']));
            }

            $hasIndex = DB::selectOne(
                "SELECT COUNT(*) AS c FROM information_schema.STATISTICS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'templates'
                   AND INDEX_NAME = 'templates_bank_id_category_id_status_index'",
                [$db]
            )->c > 0;

            if ($hasIndex) {
                Schema::table('templates', fn (Blueprint $t) => $t->dropIndex(['bank_id', 'category_id', 'status']));
            }

            Schema::table('templates', fn (Blueprint $t) => $t->dropColumn('category_id'));
        }

        Schema::dropIfExists('template_categories');
    }

    public function down(): void
    {
        // No-op: categories feature removed.
    }
};
