<?php
$getUser = Helper::getUser();
?>



<?php $__env->startSection('title', 'Attendance'); ?>
<?php $__env->startSection('page_title', 'ATTENDANCE'); ?>
<?php $__env->startSection('page_sub', Session::get('first_name') . '-' . $getUser['ClassTypes']['name']); ?>

<?php $__env->startSection('content'); ?>
<section class="attendance-page">

  <!-- 🔹 Calendar Header -->
  <div class="calendar-header d-flex justify-content-between align-items-center">
    <button id="prevMonth" class="nav-btn"><i class="bi bi-chevron-left"></i></button>
    <h6 id="monthYear" class="fw-bold mb-0 text-primary"></h6>
    <button id="nextMonth" class="nav-btn"><i class="bi bi-chevron-right"></i></button>
  </div>

  <!-- 🔹 Calendar Grid -->
  <div class="calendar-container mt-2">
    <div class="weekdays">
      <span>Sun</span><span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span><span>Fri</span><span>Sat</span>
    </div>
    <div class="days" id="attendanceDays"></div>
  </div>

 <div class="attendance-legend d-flex justify-content-around py-2 border-top">
  <div><span class="legend present"></span> Present</div>
  <div><span class="legend absent"></span> Absent</div>
  <div><span class="legend holiday"></span> Holiday</div>
  <div><span class="legend event"></span> Event</div>
  <div><span class="legend exam"></span> Exam</div>
</div>

  <!-- 🔹 Attendance Summary -->
  <div class="attendance-summary text-center mt-3">
    <div class="attendance-bar">
  <div class="attendance-bar-fill" id="barFill"></div>
  <div class="attendance-bar-text" id="summaryPercent">0% Attendance</div>
</div>
    <h6 class="mt-2 fw-bold text-danger" id="summaryMonth"></h6>
    <div class="row text-center mt-2">
      <div class="col"><p class="mb-1">Present</p><h6 id="presentCount">0</h6></div>
      <div class="col"><p class="mb-1">Absent</p><h6 class="text-danger" id="absentCount">0</h6></div>
    </div>
  </div>

</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener("DOMContentLoaded", function () {

  const daysContainer  = document.getElementById("attendanceDays");
  const monthYear      = document.getElementById("monthYear");
  const summaryMonth   = document.getElementById("summaryMonth");
  const presentCountEl = document.getElementById("presentCount");
  const absentCountEl  = document.getElementById("absentCount");
  const summaryPercentEl = document.getElementById("summaryPercent");
  const barFill        = document.getElementById("barFill");
  const barTextEl      = document.querySelector('.attendance-bar-text');

  const months = ["JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"];
  let currentDate = new Date();
  let studentID = "<?php echo e(Session::get('id')); ?>";

  function normalizeBackendData(raw) {
    const normalized = {};
    if (!raw) return normalized;
    Object.keys(raw).forEach(key => {
      const parsed = new Date(key);
      if (!isNaN(parsed)) {
        const y = parsed.getFullYear();
        const m = String(parsed.getMonth() + 1).padStart(2, '0');
        const d = String(parsed.getDate()).padStart(2, '0');
        normalized[`${y}-${m}-${d}`] = raw[key];
      } else {
        normalized[key] = raw[key];
      }
    });
    return normalized;
  }

  function fetchMonthAttendance(year, month) {
    return fetch(`/getAttendanceDates`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        admission_id: studentID,
        month: month,
        year: year
      })
    }).then(res => res.json());
  }

  function renderCalendar(date, rawBackendData, totals) {
    daysContainer.innerHTML = "";

    const backendData = normalizeBackendData(rawBackendData || {});
    const year  = date.getFullYear();
    const month = date.getMonth();

    const firstDay  = new Date(year, month, 1);
    const lastDay   = new Date(year, month + 1, 0);
    const startDay  = firstDay.getDay();
    const totalDays = lastDay.getDate();

    const today = new Date();

    monthYear.textContent = `${months[month]} ${year}`;
    if (summaryMonth) summaryMonth.textContent = `${months[month]} ${year}`;

    const mapClass = {
      "Present": "present",
      "In": "present",
      "Out": "present",
      "Absent": "absent",
      "Holiday": "holiday",
      "Leave": "leave",
      "Event": "event",
      "Exam": "exam"
    };

    let sundayCountPassed = 0;

    for (let i = 0; i < startDay; i++) {
      const empty = document.createElement("div");
      empty.classList.add("empty");
      daysContainer.appendChild(empty);
    }

    for (let d = 1; d <= totalDays; d++) {

      const day = document.createElement("div");
      day.classList.add("day");
      day.innerHTML = `<span>${d}</span>`;

      const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
      let status = backendData[dateStr] ?? null;
      const curr = new Date(year, month, d);
      const weekday = curr.getDay();

      // All Sundays = Holiday UI
      if (weekday === 0) {

        // Count only passed Sundays
        if (
          curr.getFullYear() < today.getFullYear() ||
          curr.getMonth() < today.getMonth() ||
          (curr.getFullYear() === today.getFullYear() &&
           curr.getMonth() === today.getMonth() &&
           d <= today.getDate())
        ) {
          sundayCountPassed++;
        }

        status = "Holiday";
      }

      if (status) {
        const dot = document.createElement("div");
        dot.classList.add("event-dot");
        if (mapClass[status]) dot.classList.add(mapClass[status]);
        day.appendChild(dot);
      }

      daysContainer.appendChild(day);
    }

    // -----------------------------------------
    // PERCENT CALCULATION (DECIMAL POINT)
    // -----------------------------------------

    const totalDaysInMonth = totalDays;
    const dailyWeight = 100 / totalDaysInMonth;

    const totalPresent = Number(totals?.Present ?? 0);
    const totalAbsent  = Number(totals?.Absent ?? 0);
    const totalHoliday = Number(totals?.Holiday ?? 0) + sundayCountPassed;
    const totalLeave   = Number(totals?.Leave ?? 0);
    const totalEvent   = Number(totals?.Event ?? 0);
    const totalExam    = Number(totals?.Exam ?? 0);

    const workingCount =
      totalPresent +
      totalHoliday +
      totalLeave +
      totalEvent +
      totalExam;

    // DECIMAL % HERE (2 decimals)
    const percent = (workingCount * dailyWeight).toFixed(2);

    if (presentCountEl) presentCountEl.textContent = totalPresent;
    if (absentCountEl) absentCountEl.textContent = totalAbsent;

    if (summaryPercentEl) {
      summaryPercentEl.textContent = `${percent}% (working=${workingCount})`;
    }

    // Bar width = rounded int only
    if (barFill) barFill.style.width = Math.round(percent) + "%";

    if (barTextEl) barTextEl.textContent = `${percent}%`;
  }

  function loadMonth() {
    const year  = currentDate.getFullYear();
    const month = currentDate.getMonth() + 1;

    fetchMonthAttendance(year, month).then(res => {
      const backendDataRaw = res?.data ?? {};
      const totals = res?.total ?? { Present:0, Absent:0, Holiday:0, Leave:0, Event:0, Exam:0 };
      renderCalendar(currentDate, backendDataRaw, totals);
    }).catch(err => {
      renderCalendar(currentDate, {}, { Present:0, Absent:0, Holiday:0, Leave:0, Event:0, Exam:0 });
    });
  }

  document.getElementById("prevMonth")?.addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    loadMonth();
  });

  document.getElementById("nextMonth")?.addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    loadMonth();
  });

  loadMonth();
});
</script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('student_login.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/student_login/attendence.blade.php ENDPATH**/ ?>