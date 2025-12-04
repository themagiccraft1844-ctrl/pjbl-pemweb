<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
    Schema::create('tree_messages', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('tree_id');
        $table->unsignedBigInteger('user_id')->nullable();

        $table->string('name');
        $table->text('message');
        $table->string('color');
        $table->float('x');
        $table->float('y');

        $table->timestamps();

        $table->foreign('tree_id')->references('id')->on('trees')->onDelete('cascade');
        $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
    });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tree_messages');
    }
};
