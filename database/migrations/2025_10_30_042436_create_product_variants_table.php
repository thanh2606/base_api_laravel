<?php

use App\Enums\EnumProductManagerStock;
use App\Enums\EnumProductStockStatus;
use App\Enums\EnumProductType;
use App\Enums\EnumStatus;
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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

            $table->string('sku')->nullable();
            $table->text('title');
            $table->tinyInteger('status')->comment('1: active, 0: inactive')->default(EnumStatus::ACTIVE->value);
            $table->decimal('price', 15, 2)->nullable()->comment('Giá bán');
            $table->decimal('sale_price', 15, 2)->nullable()->comment('Giá khuyến mãi');
            $table->tinyInteger('manage_stock')->default(EnumProductManagerStock::NO->value)->comment('Có quản lý tồn kho hay không');
            $table->integer('stock_qty')->nullable();
            $table->tinyInteger('stock_status')->default(EnumProductStockStatus::IN_STOCK->value)->comment('Trạng thái kho');
            $table->unsignedBigInteger('image_id')->nullable();
            $table->tinyInteger('is_default')->nullable()->comment('Biến thể mặc định');
            $table->json('attributes')->comment('Thuộc tính biến thể: {color: "Đỏ", size: "XL"}');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
