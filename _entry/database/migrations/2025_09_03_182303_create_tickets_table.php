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
        Schema::create('tickets', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('subject');
            $table->text('body');
            $table->enum('status', ['new','open','pending','closed'])->default('new');
            $table->string('category')->nullable();
            $table->text('explanation')->nullable();
            $table->decimal('confidence', 3, 2)->nullable(); // 0.00 to 1.00
            $table->text('note')->nullable(); // Internal note
            $table->boolean('category_is_manual')->default(false);
            $table->timestamp('category_changed_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('category');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
