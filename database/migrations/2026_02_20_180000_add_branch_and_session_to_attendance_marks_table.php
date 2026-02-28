<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchAndSessionToAttendanceMarksTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('attendance_marks')) {
            return;
        }

        Schema::table('attendance_marks', function (Blueprint $table) {
            if (!Schema::hasColumn('attendance_marks', 'branch_id')) {
                $table->unsignedBigInteger('branch_id')->nullable()->after('status');
            }

            if (!Schema::hasColumn('attendance_marks', 'session_id')) {
                $table->unsignedBigInteger('session_id')->nullable()->after('branch_id');
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('attendance_marks')) {
            return;
        }

        Schema::table('attendance_marks', function (Blueprint $table) {
            if (Schema::hasColumn('attendance_marks', 'session_id')) {
                $table->dropColumn('session_id');
            }

            if (Schema::hasColumn('attendance_marks', 'branch_id')) {
                $table->dropColumn('branch_id');
            }
        });
    }
}
