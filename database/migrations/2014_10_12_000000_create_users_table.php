<?php

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
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable()->after('id');
            $table->string('role')->default('admin'); // admin, subadmin, etc.
            $table->boolean('is_active')->default(true);

            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('permission')->default('none')->references('id')->on('users')->onDelete('cascade'); // all, none
            $table->foreign('permissions')->references('id')->on('users')->onDelete('cascade');; // all, none
            $table->string('password');
            $table->rememberToken();
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 
            'role', 
            'is_active',
            'permission',
            'permissions'
        ]);
        });
    }
};
