<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->foreignUuid('user_id')->index()->constrained()->cascadeOnDelete();
            $table->string('mobile_number')->index();
            $table->string('name')->nullable();

            $table->primary(['user_id', 'mobile_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
