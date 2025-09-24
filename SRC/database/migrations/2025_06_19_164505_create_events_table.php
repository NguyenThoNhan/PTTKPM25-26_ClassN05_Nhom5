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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
                   $table->string('title');
        $table->text('description');
        $table->string('location');
        $table->dateTime('start_time');
        $table->dateTime('end_time');
        $table->unsignedInteger('max_attendees')->nullable(); // Số người tham gia tối đa, có thể không giới hạn
        $table->foreignId('user_id')->constrained()->comment('Người tạo sự kiện (Admin)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
