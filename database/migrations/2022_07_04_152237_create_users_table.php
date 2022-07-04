<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fio', 256);
            $table->string('email', 256);
            $table->string('phone', 24);
            $table->timestamp('birthday');
            $table->string('address', 256);
            $table->string('organization', 256);
            $table->string('post', 256);
            $table->string('type_employment', 256);
            $table->timestamp('date_admission');
            $table->timestamp('update_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
