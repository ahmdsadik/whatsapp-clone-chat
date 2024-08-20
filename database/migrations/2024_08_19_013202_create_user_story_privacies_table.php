<?php

use App\Enums\StoryPrivacy;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_story_privacies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained()->cascadeOnDelete();
            $table->enum('privacy', StoryPrivacy::values())->comment(StoryPrivacy::comment());
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_story_privacies');
    }
};
