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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('cover_image_path')->nullable(); // Đường dẫn ảnh bìa, có thể trống
            $table->text('description')->nullable(); // Mô tả chi tiết
            $table->enum('type', ['physical', 'online']); // Phân loại: sách vật lý hoặc tài liệu online
            $table->longText('content')->nullable(); // Nội dung cho tài liệu online
            $table->unsignedInteger('quantity')->default(1); // Số lượng cho sách vật lý
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
