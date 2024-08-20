<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('story_views', function (Blueprint $table) {
            $table->foreignUuid('story_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('viewed_at')->useCurrent();

            $table->primary(['story_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_views');
    }
};
