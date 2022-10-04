<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
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
            $table->string('password');
            $table->boolean('permission')->default(1); //0:admin, 1:user
            $table->timestamps();
        });

        User::create(['name'=>'Library', 'email'=>'library@gmail.com', 'password'=>Hash::make('Aa123456'), 'permission'=>0]);
        User::create(['name'=>'Szilárd', 'email'=>'diak2@gmail.com', 'password'=>Hash::make('Ab123456')]);
        User::create(['name'=>'person', 'email'=>'example@email.ex', 'password'=>Hash::make('Ac123456')]);
        User::create(['name'=>'Gizi', 'email'=>'diak1@gmail.com', 'password'=>Hash::make('Ad123456')]);
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
