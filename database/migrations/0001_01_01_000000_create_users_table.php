<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('username')->unique(); // tambahkan ini
        $table->string('password');
        $table->timestamps();
    });
}

};
