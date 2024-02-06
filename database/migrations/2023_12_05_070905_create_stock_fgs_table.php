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
        Schema::create('stock_fgs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_id');
            $table->string('no_sj');
            $table->string('part_number');
            $table->string('lot_number');
            $table->integer('quantity');
            $table->string('ket_in', 3);
            $table->string('no_sj_out')->nullable();
            $table->foreignId('status_out_id')->nullable();
            $table->date('date_out')->nullable();
            $table->foreignId('gate_id')->nullable();
            $table->string('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_fgs');
    }
};
