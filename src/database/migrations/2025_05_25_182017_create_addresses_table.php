<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {


            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');


            $table->string('postal_code', 20);    // 郵便番号
            $table->string('address', 255);       // 住所（都道府県 + 市区町村 + 番地）
            $table->string('building', 255);      // 建物名

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
