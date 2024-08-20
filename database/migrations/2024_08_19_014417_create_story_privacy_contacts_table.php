<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('story_privacy_contacts', function (Blueprint $table) {
            $table->foreignUuid('story_id')->constrained('stories')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->primary(['story_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_privacy_contacts');
    }
};
