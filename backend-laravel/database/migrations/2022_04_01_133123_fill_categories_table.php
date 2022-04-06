<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FillCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('categories')->insert([
            'id' => 1,
            'name' => 'Happy'
        ]);

        DB::table('categories')->insert([
            'id' => 2,
            'name' => 'Cool'
        ]);

        DB::table('categories')->insert([
            'id' => 3,
            'name' => 'Sad'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('categories')->truncate();
    }
}
