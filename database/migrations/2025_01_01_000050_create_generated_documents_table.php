<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generated_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('bank_id')->constrained('banks')->cascadeOnDelete();
            $table->foreignId('template_id')->constrained('templates')->cascadeOnDelete();
            $table->string('document_number')->unique();
            $table->json('form_data')->nullable();
            $table->string('generated_file')->nullable();
            $table->enum('status', ['draft', 'generated', 'archived'])->default('draft');
            $table->timestamps();

            $table->index(['user_id', 'bank_id', 'template_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generated_documents');
    }
};
