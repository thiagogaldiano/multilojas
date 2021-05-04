<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FirstTablesEcommerce extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('subtitle');
            $table->text('description');

            $table->foreignId('user_id')->constrained();

            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);

            $table->foreignId('user_id')->constrained();

            $table->timestamps();
        });

        Schema::create('combinations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');

            $table->unsignedBigInteger('category2_id');
            $table->foreign('category2_id')->references('id')->on('categories');

            $table->foreignId('user_id')->constrained();

            $table->timestamps();
        });

        Schema::create('product_combination', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedBigInteger('combination_id');
            $table->foreign('combination_id')->references('id')->on('combinations');

            $table->foreignId('user_id')->constrained();

            $table->timestamps();
        });

        Schema::create('states', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255);
            $table->string('initials', 2);

            $table->timestamps();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('state_id');
            $table->foreign('state_id')->references('id')->on('states');

            $table->string('name', 255);
            $table->string('ibge_code', 255);

            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255);
            $table->string('cpf', 19)->nullable();
            $table->string('rg', 16)->nullable();
            $table->string('cnpj', 18)->nullable();
            $table->date('date_birth');

            $table->foreignId('user_id')->constrained();

            $table->timestamps();
        });

        Schema::create('type_address', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255);

            $table->timestamps();
        });

        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->unsignedBigInteger('address_type_id');
            $table->foreign('address_type_id')->references('id')->on('type_address');

            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->references('id')->on('cities');

            $table->string('cep', 10);
            $table->string('street', 255);
            $table->string('number', 10)->nullable();
            $table->string('district', 255);

            $table->foreignId('user_id')->constrained();

            $table->timestamps();
        });

        Schema::create('attributes', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255);

            $table->foreignId('user_id')->constrained();

            $table->timestamps();
        });

        Schema::create('attribute_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('attribute_id');
            $table->foreign('attribute_id')->references('id')->on('attributes');

            $table->foreignId('user_id')->constrained();

            $table->timestamps();
        });

        Schema::create('stock_combination', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('product_combination_id');
            $table->foreign('product_combination_id')->references('id')->on('product_combination');

            $table->string('sku', 255);

            $table->foreignId('user_id')->constrained();

            $table->timestamps();
        });

        Schema::create('attribute_combination', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('attribute_item_id');
            $table->foreign('attribute_item_id')->references('id')->on('attribute_items');

            $table->unsignedBigInteger('stock_combination_id');
            $table->foreign('stock_combination_id')->references('id')->on('stock_combination');

            $table->foreignId('user_id')->constrained();

            $table->timestamps();
        });

        Schema::create('stocks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('stock_combination_id');
            $table->foreign('stock_combination_id')->references('id')->on('stock_combination');

            $table->decimal('qtd', 10, 2);

            $table->foreignId('user_id')->constrained();

            $table->timestamps();
        });

        Schema::create('requests', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('stock_id');
            $table->foreign('stock_id')->references('id')->on('stocks');

            $table->unsignedBigInteger('customer_address_id');
            $table->foreign('customer_address_id')->references('id')->on('customer_addresses');

            $table->unsignedBigInteger('customer_address_fat_id');
            $table->foreign('customer_address_fat_id')->references('id')->on('customer_addresses');

            $table->decimal('qtd', 10, 2);

            $table->foreignId('user_id')->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
        Schema::dropIfExists('stocks');
        Schema::dropIfExists('attribute_combination');
        Schema::dropIfExists('stock_combination');
        Schema::dropIfExists('attribute_items');
        Schema::dropIfExists('attributes');
        Schema::dropIfExists('customer_addresses');
        Schema::dropIfExists('type_address');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('states');
        Schema::dropIfExists('product_combination');
        Schema::dropIfExists('combinations');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('products');
    }
}
