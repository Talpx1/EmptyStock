<?php

use App\Models\Shop;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            //TODO: add published/draft support
            $table->foreignIdFor(Shop::class)->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('title');
            $table->mediumText('description');
            $table->decimal('price');
            $table->integer('pieces_per_bundle')->nullable();
            $table->boolean('individually_sellable')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('products');
    }
};
