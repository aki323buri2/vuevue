<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameCatalogMekame extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('catalog', function ($table)
        {
            $table->renameColumn('makeme', 'mekame');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('catalog', function ($table)
        {
            $table->renameColumn('mekame', 'makeme');
        });

    }
}
