<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAbcDebToEducationalQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('educational_qualifications', function (Blueprint $table) {
            $table->unsignedBigInteger('abc_id')->nullable()->after('id');
            $table->unsignedBigInteger('deb_id')->nullable()->after('abc_id');
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
              $table->dropColumn(['abc_id', 'deb_id']);
        });
    }
}
