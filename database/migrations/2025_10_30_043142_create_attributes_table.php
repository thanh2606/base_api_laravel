<?php

use App\Enums\EnumAttributeType;
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
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('Tên thuộc tính: Color, Size, Material');
            $table->string('slug', 100)->unique();
            $table->tinyInteger('type')->default(EnumAttributeType::SELECT->value)->comment('1: select, 2: color, 3: button, 4: radio');
            $table->tinyInteger('attribute_type')->default(\App\Enums\EnumAttributePropertyType::PRODUCT->value)->comment('Loai thuộc tính: 1 - thuộc tính sản phẩm, 2 - thuộc tính mô tả');
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(EnumStatus::ACTIVE->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
