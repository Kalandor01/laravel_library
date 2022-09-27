<?php

use App\Models\User;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });

        User::create(['name'=>'Library', 'email'=>'library@gmail.com']);
        User::create(['name'=>'Szilárd', 'email'=>'diak2@gmail.com']);
        User::create(['name'=>'person', 'email'=>'example@email.ex']);
        User::create(['name'=>'Gizi', 'email'=>'diak1@gmail.com']);
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
};
