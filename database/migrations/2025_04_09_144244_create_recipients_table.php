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
        Schema::create('recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mail_template_id')->constrained('mail_templates')->onDelete('cascade');
            $table->string('email');
            $table->string('attribute_1')->nullable();
            $table->string('attribute_2')->nullable();
            $table->string('attribute_3')->nullable();
            $table->string('attribute_4')->nullable();
            $table->string('attribute_5')->nullable();
            $table->string('attribute_6')->nullable();
            $table->string('attribute_7')->nullable();
            $table->string('attribute_8')->nullable();
            $table->string('attribute_9')->nullable();
            $table->string('attribute_10')->nullable();
            $table->datetime('delivered_at')->nullable();
            $table->json('options')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipients');
    }
};
