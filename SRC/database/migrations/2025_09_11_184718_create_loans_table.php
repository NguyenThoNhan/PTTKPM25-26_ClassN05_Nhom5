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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
                    $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Người mượn
        $table->foreignId('book_id')->constrained()->onDelete('cascade'); // Sách được mượn
        $table->dateTime('loan_date'); // Ngày mượn
        $table->date('due_date')->nullable(); // Hạn trả (cho sách vật lý)
        $table->dateTime('return_date')->nullable(); // Ngày trả thực tế
        $table->enum('status', ['borrowed', 'returned'])->default('borrowed'); // Trạng thái
        
        // Cột này để lưu "chữ ký số" (hash) của tài liệu online lúc mượn
        $table->string('content_hash_on_loan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
