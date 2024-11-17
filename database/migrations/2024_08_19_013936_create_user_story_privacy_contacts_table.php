<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_story_privacy_contacts', function (Blueprint $table) {
            $table->foreignId('user_story_privacy_id')->constrained('user_story_privacies')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();

            $table->primary(['user_id', 'user_story_privacy_id']);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_story_privacy_contacts');
    }
};
