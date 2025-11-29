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
        Schema::table('categories', function (Blueprint $table) {
            $table->foreign('image_id')
                ->references('id')->on('medias')->nullOnDelete();
            $table->dropColumn(['image']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->foreign('image_id')
                ->references('id')->on('medias')->nullOnDelete();
            $table->dropColumn(['image']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['image_id']);
            $table->unsignedBigInteger('image_id')->nullable()->change();
            $table->string('image')->nullable()->after('image_id');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['image_id']);
            $table->unsignedBigInteger('image_id')->nullable()->change();
            $table->string('image')->nullable()->after('image_id');
        });
    }
};
