<?php

use App\Models\Genre;
use App\Models\User;
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
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->string('audio');
            $table->string('img')->nullable();
            $table->string('status')->default('модерация');
            $table->string('is_available')->default('false');
            $table->string('is_cens')->default('false');
            $table->date('date_release')->nullable();
            $table->text('text')->nullable();
            $table->foreignIdFor(Genre::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('listeners')->default(0);
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracks');
    }
};
