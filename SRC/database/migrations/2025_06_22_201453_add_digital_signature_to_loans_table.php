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
        // Sử dụng Schema::table để sửa đổi bảng 'loans'
        Schema::table('loans', function (Blueprint $table) {
            // Thêm cột mới để lưu chữ ký số
            // Đặt nó sau cột 'integrity_status' để dễ quản lý
            $table->text('digital_signature')->nullable()->after('integrity_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Logic để xóa cột đi nếu bạn chạy lệnh rollback
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn('digital_signature');
        });
    }
};
