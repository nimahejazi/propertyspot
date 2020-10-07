<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
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
            $table->string('fullname')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('api_token', 80)->unique();
            $table->rememberToken();
            $table->timestamps();
            $table->string('license_no')->nullable();
            $table->string('title')->nullable();
            $table->string('photo_url')->nullable();
            $table->string('photo_url_2x')->nullable();
            $table->boolean('has_company')->default(false);
            $table->string('company_name')->nullable();
            $table->string('company_website')->nullable();
            $table->string('company_address')->nullable();
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
}
