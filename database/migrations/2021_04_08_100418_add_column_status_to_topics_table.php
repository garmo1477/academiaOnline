<?php

use App\Models\Topic;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStatusToTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('topics', function (Blueprint $table) {
            //creamos una columna llamada status con enum, con dos posibles valores, por defecto tendrá el valor de pending y se pondrá después de la columna content
            $table
                ->enum('status', [Topic::PENDING, Topic::SOLVED])
                ->default(Topic::PENDING)
                ->after('content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
