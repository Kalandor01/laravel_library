<?php

use App\Models\Lending;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lendings', function (Blueprint $table) {
            $table->primary(['user_id', 'copy_id', 'start']);
            //létrehozza a mezőt és össze is köti a megf. tábla megf. mezőjével
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('copy_id')->references('copy_id')->on('copies');
            $table->date('start');
            $table->date('end')->nullable();
            $table->tinyInteger('extension')->default(0);
            $table->smallInteger('notice')->default(0);
            $table->timestamps();
        });

        Lending::create(['user_id'=>1, 'copy_id'=>1, 'start'=>'2022-10-06', 'end'=>'2022-11-15', 'extension'=>1, 'notice'=>0]);
        Lending::create(['user_id'=>1, 'copy_id'=>2, 'start'=>'2022-10-21', 'end'=>'2022-11-01', 'notice'=>0]);
        Lending::create(['user_id'=>2, 'copy_id'=>2, 'start'=>'2022-11-11']);
        Lending::create(['user_id'=>3, 'copy_id'=>1, 'start'=>'2021-05-16', 'end'=>'2021-10-06', 'extension'=>1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lendings');
    }
};
