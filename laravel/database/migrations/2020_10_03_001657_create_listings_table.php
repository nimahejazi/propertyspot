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
            $table->foreignId('user_id')->constrained();
            $table->string('street')->nullable();
            $table->string('add_line2')->nullable();
            $table->string('county')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->boolean('schools_fetched')->default(false);
            $table->string('elementary_school')->nullable();
            $table->string('middle_school')->nullable();
            $table->string('high_school')->nullable();
            $table->string('property_type')->nullable();
            $table->integer('bedrooms')->nullable();
            $table->decimal('bathrooms', 3, 1)->nullable();
            $table->string('square_ft')->nullable();
            $table->integer('price')->nullable();
            $table->string('mls_no')->nullable();
            $table->foreignId('listing_status_id')->nullable()->constrained();
            $table->year('year_built')->nullable();
            $table->string('lot_square_ft')->nullable();
            $table->tinyInteger('floors')->nullable();
            $table->tinyInteger('garage_size')->nullable();
            $table->text('property_desc')->nullable();
            $table->string('slug')->nullable();
            $table->foreignId('payment_status')->nullable()->constrained();
            $table->string('payment_id')->nullable();
            $table->boolean('paid')->nullable();
            $table->string('payment_amount')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->integer('views')->nullable();
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
