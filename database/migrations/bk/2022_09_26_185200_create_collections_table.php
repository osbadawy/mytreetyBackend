<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name');
            $table->string('sourcing_transportation')->nullable();
            $table->float('sourcing_transporationDistance')->nullable();
            $table->float('sourcing_exact')->nullable();
            $table->float('manufacturing_energyConsumed');
            $table->float('manufacturing_renewableFraction');
            $table->float('manufacturing_nonRenewableFraction');
            $table->text('manufacturing_icons')->nullable();
            $table->float('packaging_mass');
            $table->string('packaging_material');
            $table->string('shipping_transportation')->nullable();
            $table->float('shipping_distance')->nullable();
            $table->float('shipping_exact')->nullable();
            $table->string('use_amount')->nullable();
            $table->float('endoflife_mass');
            $table->float('endoflife_recycledAmount');
            $table->float('endoflife_thrownAmount');
            $table->boolean('is_green')->default(false);
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
        Schema::dropIfExists('collections');
    }
}
