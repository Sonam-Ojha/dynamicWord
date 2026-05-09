<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('templates', 'category_id')) {
            Schema::table('templates', function (Blueprint $table) {
                try { $table->dropIndex(['bank_id', 'category_id', 'status']); } catch (\Throwable $e) {}
                try { $table->dropForeign(['category_id']); } catch (\Throwable $e) {}
                $table->dropColumn('category_id');
            });
        }

        Schema::dropIfExists('template_categories');
    }

    public function down(): void
    {
        // No-op: categories feature removed.
    }
};
