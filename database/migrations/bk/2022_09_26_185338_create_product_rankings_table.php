<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductRankingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_rankings', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('category_id');
            $table->float('sourcing_score')->nullable();
            $table->float('manufacturing_score')->nullable();
            $table->float('packaging_score')->nullable();
            $table->float('shipping_score')->nullable();
            $table->float('use_score')->nullable();
            $table->integer('end_of_life_score')->nullable();
            $table->integer('sourcing_level')->nullable();
            $table->integer('manufacturing_level')->nullable();
            $table->integer('packaging_level')->nullable();
            $table->integer('shipping_level')->nullable();
            $table->integer('use_level')->nullable();
            $table->integer('end_of_life_level')->nullable();
            $table->integer('overall_sustainability_ranking')->nullable();
            $table->boolean('is_calculated')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_rankings');
    }
}
