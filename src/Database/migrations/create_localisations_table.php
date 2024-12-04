<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('localisations', function (Blueprint $table) {
            $table->id();
            $table->string('language', 5);
            $table->string('field');
            $table->text('value')->nullable();
            $table->string('localizable_type');
            $table->unsignedBigInteger('localizable_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('localisations');
    }
};
