<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVerificationFieldsToDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            // Screenshot path for verification
            $table->string('verification_screenshot')->nullable()->after('status');
            // Any remarks entered by the verifier
            $table->text('verification_remarks')->nullable()->after('verification_screenshot');

            // Who verified (nullable to keep backward compatibility)
            $table->unsignedBigInteger('verified_by')->nullable()->after('verification_remarks');
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');

            // When it was verified
            $table->timestamp('verified_at')->nullable()->after('verified_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            // Drop foreign key first, then columns
            if (Schema::hasColumn('documents', 'verified_by')) {
                $table->dropForeign(['verified_by']);
            }

            $table->dropColumn([
                'verification_screenshot',
                'verification_remarks',
                'verified_by',
                'verified_at',
            ]);
        });
    }
}
