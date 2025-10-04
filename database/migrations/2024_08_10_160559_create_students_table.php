<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {

            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('fathers_name')->nullable();;
            $table->string('mothers_name')->nullable();;
            $table->foreignId('religion_id')->constrained('religions')->nullable();
            $table->foreignId('religion_category_id')->constrained('religion_categories')->nullable()->onDelete('restrict');
            $table->foreignId('country_id')->constrained('countries')->onDelete('restrict');
            $table->foreignId('city_id')->constrained('cities')->onDelete('restrict');
            $table->foreignId('state_id')->constrained('states')->onDelete('restrict');
            $table->string('postal_code')->nullable();
            $table->date('enrollment_date')->nullable();
            $table->decimal('gpa', 3, 2)->nullable();
            $table->foreignId('identity_card_id')->nullable()->constrained('identity_cards')->onDelete('restrict');
            $table->string('identity_card_no')->nullable();
            $table->foreignId('employment_status_id')->constrained('employment_statuses')->onDelete('restrict');
            $table->date('date_of_birth');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('alternative_number')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->text('address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->enum('student_status', ['active', 'inactive']);
            $table->string('profile_picture')->nullable();
            $table->string('field_of_study')->nullable();
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
        Schema::dropIfExists('students');
    }
}
