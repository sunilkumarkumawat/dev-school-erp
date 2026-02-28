<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceMessagesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('attendance_messages')) {
            return;
        }

        Schema::create('attendance_messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('attendance_mark_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('session_id')->nullable();
            $table->string('attendance_unique_id', 100);
            $table->enum('service', ['whatsapp', 'firebase', 'sms']);
            $table->string('mobile', 20)->nullable();
            $table->text('firebase_token')->nullable();
            $table->date('attendance_date')->nullable();
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->string('attendance_status', 30)->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->text('payload')->nullable();
            $table->timestamps();

            $table->index(['attendance_unique_id', 'attendance_date'], 'attendance_messages_uid_date_idx');
            $table->index(['service', 'status'], 'attendance_messages_service_status_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_messages');
    }
}
