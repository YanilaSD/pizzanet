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
        Schema::create('promociones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('festividad_id')->constrained('festividades')->onDelete('cascade');
            $table->string('nombre')->unique();
            $table->decimal('descuento', 10, 2);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->decimal('compra_minima', 10, 2);
            $table->integer('limite_uso');
            $table->tinyInteger('estado')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promociones');
    }
};
