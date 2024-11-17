<?php

use App\Enums\StoryPrivacy;
use App\Enums\StoryType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', StoryType::values());
            $table->string('text')->nullable();
            $table->string('duration')->nullable();
            $table->enum('privacy', StoryPrivacy::values())->comment(StoryPrivacy::comment());
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};
