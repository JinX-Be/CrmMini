<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_field_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('niche')->index();
            $table->string('key');          // stable slug — never changed once set
            $table->string('label');
            $table->string('type')->default('text'); // text|number|select|checkbox|textarea
            $table->json('options')->nullable();      // choices for the "select" type
            $table->boolean('required')->default(false);
            $table->string('help_text')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['niche', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_field_definitions');
    }
};
