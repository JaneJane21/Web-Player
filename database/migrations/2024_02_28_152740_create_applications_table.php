<?php

use App\Models\Category;
use App\Models\Track;
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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Category::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('status')->default('обработка');
            $table->foreignIdFor(Track::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('user_comment')->nullable();
            $table->text('admin_comment')->nullable();
            $table->text('text');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
