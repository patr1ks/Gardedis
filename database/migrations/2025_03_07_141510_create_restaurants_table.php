<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('slug', 400);
            $table->longText('description')->nullable();
            $table->boolean('published')->default(0);
            $table->decimal('price', 10, 2);
            $table->foreignIdFor(User::class, 'owner')->nullable();
            $table->longText('layout_json')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
