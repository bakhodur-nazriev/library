<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('link')->nullable();
            $table->text('description')->nullable();
            $table->string('ISBN')->unique();
            $table->date('published_at')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('genre')->nullable();
            $table->string('language')->nullable();
            $table->integer('pages')->nullable();
            $table->string('publisher')->nullable();
            $table->timestamps();
        });


        // Assign permissions to the admin role
//        $adminRole = Role::findByName('admin');
//        $adminRole->givePermissionTo(['manage_books']);
//
//// Assign permissions to the user role
//        $userRole = Role::findByName('user');
//        $userRole->givePermissionTo(['view_books']);

    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
