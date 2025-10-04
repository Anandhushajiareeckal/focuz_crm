<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->nullable()->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->nullable()->onDelete('cascade');
            $table->foreignId('course_schedule_id')->constrained('course_schedules')->nullable()->onDelete('cascade');
            $table->decimal('amount', 8, 2);
            $table->decimal('discount', 8, 2)->nullable()->default(0);
            $table->enum('payment_status', ['pending', 'completed']);
            $table->foreignId('payment_id')->nullable()->constrained('payments')->onDelete('set null');
            $table->date('next_payment_date')->nullable(); // Example field for status
            $table->enum('status', ['active', 'inactive'])->nullable()->default('active');
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
        Schema::dropIfExists('course_payments');
    }
}
