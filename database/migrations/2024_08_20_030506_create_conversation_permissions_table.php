<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('conversation_permissions', function (Blueprint $table) {
            $table->foreignUuid('conversation_id')->primary()->constrained()->cascadeOnDelete();
            $table->boolean('edit_group_settings');
            $table->boolean('send_messages');
            $table->boolean('add_other_members');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversation_permissions');
    }
};
