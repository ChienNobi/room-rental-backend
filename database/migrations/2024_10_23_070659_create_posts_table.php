<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->json('images');
            // room info
            $table->string('room_type');
            $table->string('acreage');

            $table->string('city');
            $table->string('district');
            $table->string('ward');
            $table->string('detail_address');
            $table->decimal('rent_price', 15, 0);
            $table->decimal('electricity_price', 10, 0)->nullable();


            //
            $table->unsignedBigInteger('user_id');
            $table->string('status', 10)->default('draft')->comment('draft, published, unpublished');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
