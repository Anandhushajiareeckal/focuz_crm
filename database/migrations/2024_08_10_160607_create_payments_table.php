<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained('payment_methods')->onDelete('restrict');
            $table->decimal('amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->foreignId('promocode')->nullable()->constrained('discounts')->onDelete('set null')->nullable(); // Link to terminals
            $table->date('payment_date');
            $table->foreignId('terminal_id')->nullable()->constrained('terminals')->onDelete('restrict')->default(1); // Link to terminals
            $table->foreignId('card_type_id')->nullable()->constrained('card_types')->onDelete('restrict'); // Link to card types
            $table->foreignId('bank_id')->nullable()->constrained('banks')->onDelete('restrict'); // Link to banks

            $table->string('transaction_ref', 50)->nullable();
            $table->text('notes')->nullable(); // Additional notes or comments about the payment
            $table->foreignId('course_id')->nullable()->constrained('courses')->onDelete('restrict');
            $table->foreignId('course_schedule_id')->nullable()->constrained('course_schedules')->onDelete('restrict');
            $table->enum('status', ['active', 'reversed'])->nullable()->default('active');
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
        Schema::dropIfExists('payments');
    }
}
