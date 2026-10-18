<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->unsignedInteger('cantidad');
            $table->tinyInteger('estado')->default('1');
            $table->timestamps();

            $table->unique('producto_id');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};