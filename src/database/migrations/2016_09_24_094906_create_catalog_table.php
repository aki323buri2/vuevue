<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalog', function (Blueprint $table) {
            // $table->increments('id');
            $table->string('catno')           ->comment('カタログＣＤ');
            $table->string('shcds')           ->comment('ｼｮｸﾘｭｰＣＤ');
            $table->string('eoscd')           ->comment('ＥＯＳＣＤ');
            $table->string('makeme')          ->comment('メーカー名');
            $table->string('shiren')          ->comment('仕入先ＣＤ');
            $table->string('hinmei')          ->comment('品名');
            $table->string('sanchi')          ->comment('産地');
            $table->string('tenyou')          ->comment('天・養');
            $table->decimal('nouka'  , 10, 2) ->comment('納価');
            $table->decimal('baika'  , 10, 2) ->comment('売価');
            $table->decimal('stanka' , 10, 2) ->comment('仕入');
            $table->timestamps();

            $table->primary('catno');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('catalog');
    }
}
