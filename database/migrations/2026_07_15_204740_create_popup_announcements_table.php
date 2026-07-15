<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('popup_announcements', function (Blueprint $table) {

            $table->id();

            $table->string('title');

            $table->string('slug')->unique();

            $table->text('description');

            $table->string('image')->nullable();

            $table->string('button_text')->nullable();

            $table->string('button_link')->nullable();

            $table->string('background_color')
                    ->default('#ffffff');

            $table->string('text_color')
                    ->default('#000000');

            $table->boolean('show_on_login')
                    ->default(false);

            $table->boolean('show_on_dashboard')
                    ->default(false);

            $table->boolean('show_once_per_day')
                    ->default(true);

            $table->date('start_date');

            $table->date('end_date');

            $table->integer('priority')
                    ->default(1);

            $table->boolean('status')
                    ->default(true);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('popup_announcements');
    }
};
