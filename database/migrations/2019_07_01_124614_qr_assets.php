<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QrAssets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qr_assets', function (Blueprint $table){
            $table->unsignedInteger('qrId');
            $table->unsignedInteger('assetId');
            $table->timestampsTz();
            $table->foreign('qrId')->references('id')->on('qr');
            $table->foreign('assetId')->references('id')->on('asset');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qr_assets');
    }
}
