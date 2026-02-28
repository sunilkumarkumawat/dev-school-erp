<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBiomatricAttendanceTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('biomatric_attendance')) {
            return;
        }

        Schema::create('biomatric_attendance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('attendance_unique_id', 100);
            $table->date('date');
            $table->time('time');
            $table->timestamps();

            $table->index(['attendance_unique_id', 'date'], 'biomatric_attendance_uid_date_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('biomatric_attendance');
    }
}
