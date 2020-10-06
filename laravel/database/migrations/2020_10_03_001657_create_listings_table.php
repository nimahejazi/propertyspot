<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('street');
            $table->string('add_line2')->nullable();
            $table->string('county')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('elementary_school')->nullable();
            $table->string('middle_school')->nullable();
            $table->string('high_school')->nullable();
            $table->foreignId('property_type_id')->contrained()->nullable();
            $table->decimal('bedrooms', 3,1)->nullable();
            $table->decimal('bathrooms', 3, 1)->nullable();
            $table->integer('sqaure_ft')->nullable();
            $table->integer('price')->nullable();
            $table->string('msl_no')->nullable();
            $table->foreignId('listing_status_id')->constrained()->nullable();
            $table->year('year_built')->nullable();
            $table->integer('log_sqaure_ft')->nullable();
            $table->tinyInteger('floors')->nullable();
            $table->tinyInteger('garage_size')->nullable();
            $table->text('property_desc')->nullable();
            $table->string('slug')->nullable();
            $table->string('square_customer_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listings');
    }
}
