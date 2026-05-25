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
        Schema::create('support_tickets', function (Blueprint $table) {

            $table->id();

            $table->string('ticket_no')->unique();

            $table->string('name');

            $table->string('email');

            $table->string('mobile');

            $table->string('subject');

            $table->longText('message');

            $table->string('attachment')->nullable();

            $table->enum('priority', [

                'low',
                'medium',
                'high'

            ])->default('medium');

            $table->enum('status', [

                'open',
                'in_progress',
                'resolved',
                'closed'

            ])->default('open');

            $table->text('admin_reply')->nullable();

            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
