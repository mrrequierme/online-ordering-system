<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_histories', function (Blueprint $table) {
            $table->id();
            // history_id must exist before foreign() is called
        $table->unsignedBigInteger('history_id');
            
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->integer('qty');
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();

            $table->foreign('history_id')->references('id')->on('histories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_histories');
    }
};
