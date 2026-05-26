<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('templates')) {
            return;
        }

        // Make changes safely: check column existence before attempting to drop constraints/columns
        if (Schema::hasColumn('templates', 'category_id')) {
            Schema::table('templates', function (Blueprint $table) {
                    // Drop foreign key first (MySQL prevents dropping an index used by a foreign key)
                    try {
                        $table->dropForeign(['category_id']);
                    } catch (\Throwable $e) {
                        // Laravel couldn't find a FK by conventional name; try dropping any FK that references category_id
                        try {
                            $sm = Schema::getConnection()->getDoctrineSchemaManager();
                            $fkList = $sm->listTableForeignKeys('templates');
                            foreach ($fkList as $fk) {
                                $localCols = $fk->getLocalColumns();
                                if (in_array('category_id', $localCols, true)) {
                                    DB::statement(sprintf('ALTER TABLE `templates` DROP FOREIGN KEY `%s`', $fk->getName()));
                                }
                            }
                        } catch (\Throwable $_) {
                            // ignore
                        }
                    }

                    // Then drop composite index if it exists. Use try/catch to avoid migration failure if index name differs.
                    try {
                        $table->dropIndex(['bank_id', 'category_id', 'status']);
                    } catch (\Throwable $e) {
                        // ignore
                    }

                try {
                    $table->dropColumn('category_id');
                } catch (\Throwable $e) {
                    // ignore
                }
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('templates')) {
            return;
        }

        Schema::table('templates', function (Blueprint $table) {
            if (! Schema::hasColumn('templates', 'category_id')) {
                $table->foreignId('category_id')->constrained('template_categories')->cascadeOnDelete()->after('bank_id');
                $table->index(['bank_id', 'category_id', 'status']);
            }
        });
    }
};
