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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('slug');
            $table->text('short_desc')->nullable();
            $table->text( 'desc')->nullable();
            $table->longText('content')->nullable();
            $table->string('meta_title', 255)->nullable()->comment('SEO meta title');
            $table->text('meta_desc')->nullable()->comment('SEO meta description');
            $table->text('meta_keywords')->nullable()->comment('SEO meta keywords');
            $table->string('sku')->nullable();
            $table->tinyInteger('type')->comment('1: simple, 2: variable, 3: virtual')->default(EnumProductType::SIMPLE->value);
            $table->tinyInteger('status')->comment('1: active, 0: inactive')->default(EnumStatus::ACTIVE->value);
            $table->tinyInteger('sale_status')->comment('1: active, 0: inactive')->default(EnumStatus::ACTIVE->value);
            $table->decimal('price', 15, 2)->nullable()->comment('Giá bán');
            $table->decimal('sale_price', 15, 2)->nullable()->comment('Giá khuyến mãi');
            $table->timestamp('sale_price_start')->nullable()->comment('Thời gian bắt đầu khuyến mãi');
            $table->timestamp('sale_price_end')->nullable()->comment('Thời gian kết thúc khuyến mãi');
            $table->tinyInteger('manage_stock')->default(EnumProductManagerStock::NO->value)->comment('Có quản lý tồn kho hay không');
            $table->integer('stock_qty')->nullable();
            $table->tinyInteger('stock_status')->default(EnumProductStockStatus::IN_STOCK->value)->comment('Trạng thái kho');
            $table->unsignedBigInteger('image_id')->nullable();
            $table->string('download_link', 500)->nullable()->comment('Link tải về sản phẩm (dành cho sản phẩm ảo)');
            $table->integer('download_limit')->nullable()->comment('Số lần được phép tải về (dành cho sản phẩm ảo)');
            $table->timestamp('download_expiry')->nullable()->comment('Thời hạn download');
            $table->unsignedBigInteger('view_count')->nullable()->comment('Số lượt xem');
            $table->unsignedBigInteger('order_count')->nullable()->comment('Số lượt mua');
            $table->jsonb('attributes')->nullable()->comment('Thông tin thuộc tính sản phẩm JSON');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
