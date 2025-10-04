<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReplaceUniversityColumnWithDegreeIdInEducationalQualifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('educational_qualifications', function (Blueprint $table) {
            $table->dropColumn('institution');
            $table->foreignId('institution_id')->nullable()->constrained('universities')->onDelete('restrict'); // Link to period names
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
            //
        });
    }
}
