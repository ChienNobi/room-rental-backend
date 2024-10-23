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

            $table->string('city');
            $table->string('district');
            $table->string('ward');
            $table->string('detail_address');
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();

            $table->string('room_type');
            $table->string('acreage');
            $table->decimal('rent_fee', 15, 0);
            $table->decimal('electricity_fee', 10, 0)->nullable();
            $table->decimal('water_fee', 10, 0)->nullable();
            $table->decimal('internet_fee', 10, 0)->nullable();
            $table->decimal('extra_fee', 10, 0)->nullable();
            $table->string('furniture')->nullable()->comment('furnished, unfurnished, partially_furnished');

            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone');

            $table->unsignedBigInteger('user_id')->index();
            $table->string('status', 10)->default('draft')->comment('draft, published, unpublished');
            $table->string('promote_status', 10)->default('normal')->comment('normal, promote');
            $table->dateTime('promote_expired_at')->nullable();
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
