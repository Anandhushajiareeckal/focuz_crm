<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installment_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade'); // Link to the course
            $table->foreignId('period_name_id')->constrained('period_names')->onDelete('cascade'); // Link to period names
            $table->date('start_date'); // Start date of the installment period
            $table->date('end_date'); // End date of the installment period
            $table->decimal('amount', 8, 2); // Amount for each installment
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
        Schema::dropIfExists('installment_periods');
    }
}
