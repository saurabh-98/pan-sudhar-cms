<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('charge_locations', function (Blueprint $table) {

            $table->id();

            $table->foreignId('charge_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('state_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('district_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('price',10,2);

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            $table->unique([
                'charge_id',
                'state_id',
                'district_id'
            ]);

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('charge_locations');
    }
};