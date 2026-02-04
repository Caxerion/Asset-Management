<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pickups', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id();
            $table->string('pickup_no', 40)->unique();
            $table->dateTime('pickup_date');

            $table->foreignId('requested_by')->constrained('users');
            $table->foreignId('floor_id')->constrained();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreignId('created_by')->nullable()
                  ->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()
                  ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickups');
    }
};
