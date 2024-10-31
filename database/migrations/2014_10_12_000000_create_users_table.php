<?php

use App\Constants\Role;
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
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('avt')->nullable();
            $table->string('email')->unique();
            $table->string('phone', 11)->unique();
            $table->string('password');
            $table->string('address')->nullable();
            $table->tinyInteger('vip_level')->default(0);
            $table->enum('role', Role::ROLES)->default(Role::ROLE_USER);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->softDeletes();
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
