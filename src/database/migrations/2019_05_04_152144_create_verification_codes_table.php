<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerificationCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verification_codes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('code');
            $table->timestamp('expires_at');

            $table->morphs('object');
            $table->nullableMorphs('actor');

            $table->string('action')->nullable();

            $table->unsignedTinyInteger('attempts')->nullable()
                ->comment('Counts attempts to verify. Limits once attempts > MAX_ATTEMPTS');

            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verification_codes');
    }
}
