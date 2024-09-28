<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('companies', function (Blueprint $table) {
            //TODO: picture_path
            //TODO: cover_image_path
            $table->id();
            $table->string('name')->unique();
            $table->string('slogan')->nullable();
            $table->mediumText('description')->nullable();
            $table->string('vat_number')->unique();
            $table->string('iban')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('companies');
    }
};
