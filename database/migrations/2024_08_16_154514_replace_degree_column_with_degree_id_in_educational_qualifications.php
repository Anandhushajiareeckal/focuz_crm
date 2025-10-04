<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReplaceDegreeColumnWithDegreeIdInEducationalQualifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('educational_qualifications', function (Blueprint $table) {


            $table->foreignId('degree_id')->nullable()->constrained('degrees')->onDelete('cascade'); // Link to period names

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('educational_qualifications', function (Blueprint $table) {
            // Check if the foreign key exists before dropping it
            //    if (Schema::hasColumn('educational_qualifications', 'degree_id')) {
            //     $table->dropForeign(['degree_id']);
            // }

            // // Drop the degree_id column
            // $table->dropColumn('degree_id');



        });
    }
}
