<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('identification_type');
            $table->string('identification_number', 30)->unique();
            $table->string('names', 255);
            $table->string('last_names', 255);
            $table->date('born_date')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('email', 100)->unique();
            $table->string('phone', 30)->nullable();
            $table->string('address', 255)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('created_by')->constrained('users');
            $table->unsignedInteger('country_id')->nullable();
            $table->foreignId('state_id')->nullable()->constrained();
            $table->foreignId('city_id')->nullable()->constrained();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
