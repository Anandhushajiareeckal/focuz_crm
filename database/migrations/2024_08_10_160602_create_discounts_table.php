<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('promocode')->unique(); // Promotion code, must be unique, Add default entry as DISCOUNT
            $table->text('description')->nullable(); // Description of the discount
            $table->decimal('discount_amount', 8, 2); // Discount amount, e.g., 20.00
            $table->date('start_date'); // Start date of the discount
            $table->date('end_date'); // End date of the discount
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('created_at')->useCurrent()->nullable(); // Default value
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable(); // Default value with a
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discounts');
    }
}
