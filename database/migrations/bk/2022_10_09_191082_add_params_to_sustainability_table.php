<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParmsSustainabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sustainabilities', function (Blueprint $table) {
            $table->float('price')->nullable();
            $table->float('emisson_reduction')->nullable();
            $table->string('ui_sepertion')->nullable();
            $table->text('required_documents')->nullable();
            $table->boolean('sourcing')->nullable()->default(0);
            $table->boolean('manufacturing')->nullable()->default(0);
            $table->boolean('packaging')->nullable()->default(0);
            $table->boolean('shipping')->nullable()->default(0);
            $table->boolean('use')->nullable()->default(0);
            $table->boolean('end_of_life')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sustainabilities', function (Blueprint $table) {
            //
        });
    }
}
