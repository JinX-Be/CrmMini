<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('niche')->index();
            $table->string('name');
            $table->decimal('default_price', 10, 2)->default(0);
            $table->integer('default_duration_minutes')->default(60);
            // Used by Phase 4 reschedule reminders.
            $table->integer('default_interval_weeks')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
