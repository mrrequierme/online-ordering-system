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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable(); // optional link to original
            $table->unsignedBigInteger('user_id')->nullable(); // customer id (but optional if deleted)
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_contact')->nullable();
            $table->string('customer_gender')->nullable();
            $table->date('customer_birthday')->nullable();
            $table->text('customer_address')->nullable();

            $table->decimal('total', 10, 2);
            $table->date('claim_date')->nullable();
            $table->string('status'); // done/unclaimed

            // staff info
            $table->unsignedBigInteger('staff_id');
            $table->string('staff_name');
            $table->string('staff_email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
