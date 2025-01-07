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
        Schema::table('product_tag', function (Blueprint $table) {
            //
        $table->foreignId('product_id')->constrained('products')->cascadeOnDelete()->unique();
        $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete()->unique();
         // $table->primary(['product_id','tag_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_tag', function (Blueprint $table) {
            $table->dropConstrainedForeignId(['product_id']);
            $table->dropConstrainedForeignId(['tag_id']);
        });
    
    }
};
