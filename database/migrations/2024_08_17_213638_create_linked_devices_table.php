<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('linked_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('token_id')->constrained('personal_access_tokens')->cascadeOnDelete();
            $table->string('device_name');
            $table->string('channel_name')->unique();
            $table->timestamp('linked_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('linked_devices');
    }
};
