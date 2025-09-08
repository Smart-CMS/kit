<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(config('kit.pages_table_name'), function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('slug', 191)->unique()->index();
            $table->string('status')->default('published');
            $table->unsignedInteger('sorting')->default(0);
            $table->string('image')->nullable();
            $table->string('banner')->nullable();
            $table->unsignedBigInteger('views')->default(0);

            $table->tinyInteger('depth')->default(0);
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->unsignedBigInteger('root_id')->nullable()->index();

            $table->json('settings')->nullable();
            $table->unsignedBigInteger('layout_id')->nullable()->index();
            $table->json('layout_settings')->nullable();
            $table->boolean('is_system')->default(false);
            $table->boolean('is_root')->default(false);
            $table->timestamp('published_at')->nullable();

            $table->json('title')->nullable();
            $table->json('heading')->nullable();
            $table->json('summary')->nullable();
            $table->json('content')->nullable();
            $table->json('description')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->boolean('is_index')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists(config('kit.pages_table_name'));
    }
};
