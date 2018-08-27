<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('accounts_id')->unsigned();
            $table->foreign('accounts_id')->references('id')->on('account');
            $table->string('name')->nullable();
            $table->string('email')->unique();
            // not a real emum in Laravel
            $table->enum('state', [ 'active',
                                    'unsubscribed',
                                    'junk',
                                    'bounced',
                                    'unconfirmed']);
            // $table->rememberToken();
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
        Schema::dropIfExists('subscribers');
    }
}
