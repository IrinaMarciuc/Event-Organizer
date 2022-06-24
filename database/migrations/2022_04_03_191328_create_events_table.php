<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('description', 4000);
            $table->string("location", 120);
            $table->integer("limit");
            $table->string('image')->nullable();
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->string('status');
            $table->foreignIdFor(User::class);
            $table->timestamps();
        });

        Schema::create('event_user', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id');
            $table->integer('user_id');
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
        Schema::dropIfExists('events');
        Schema::dropIfExists('event_user');
    }
};
