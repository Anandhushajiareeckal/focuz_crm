<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSslcIntermediateFieldsToEducationalQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('educational_qualifications', function (Blueprint $table) {
            $table->string('sslc_board')->nullable()->after('gpa');
            $table->string('sslc_passout')->nullable()->after('sslc_board');
            $table->string('intermediate_board')->nullable()->after('sslc_passout');
            $table->string('intermediate_passout')->nullable()->after('intermediate_board');
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
            $table->dropColumn(['sslc_board', 'sslc_passout', 'intermediate_board', 'intermediate_passout']);
        });
    }
}
