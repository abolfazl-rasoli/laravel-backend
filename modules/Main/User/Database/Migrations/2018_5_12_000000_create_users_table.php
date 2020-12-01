<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email', 191)->nullable()->unique();
            $table->string('mobile', 20)->nullable()->unique();
            $table->unsignedBigInteger('role')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('password');
            $table->string('avatar', 191)->nullable();
            $table->timestamps();

            $table->softDeletes();

            $table->foreign('role')
                ->references('id')
                ->on('roles')
                ->onUpdate('cascade')
                ->onDelete('set null');

        });
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
}
