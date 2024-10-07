<ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
  <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
    <a class="nav-link" href="index.php">
      <i class="fa fa-fw fa-dashboard"></i>
      <span class="nav-link-text">Dashboard</span>
    </a>
  </li>
  <?php if( ACCOUNT_TYPE < 3 ): ?>
  <li class="nav-item" data-toggle="tooltip" data-placement="right" title="My Profile">
    <a class="nav-link" href="profile.php">
      <i class="fa fa-fw fa-address-card-o"></i>
      <span class="nav-link-text">My Profile</span>
    </a>
  </li>
  <?php endif; ?>
  <?php if( !empty( array_intersect( array( 'attendance.php' ), $user_access ) ) ): ?>
  <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Reports">
    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#navAttendance" data-parent="#exampleAccordion" aria-expanded="false">
      <i class="fa fa-fw fa-calendar"></i>
      <span class="nav-link-text">Attendance</span>
    </a>
    <ul class="sidenav-second-level collapse" id="navAttendance">
      <li>
        <a href="attendance.php?act=add">Add Log</a>
      </li>
      <li>
        <a href="attendance.php">All Logs</a>
      </li>
      <li>
        <a href="overtime.php">Overtime</a>
      </li>
      <?php if( in_array( 'report-timecard.php', $user_access ) ): ?>
        <li>
          <a href="report-timecard.php">Time Card</a>
        </li>
      <?php endif; ?>
    </ul>
  </li>
  <?php endif; ?>
  <?php if( !empty( array_intersect( array( 'all-schedules.php', 'employee-schedules.php' ), $user_access ) ) ): ?>
    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Employees">
      <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#navSchedules" data-parent="#exampleAccordion" aria-expanded="false">
        <i class="fa-regular fa-fw fa-calendar-days"></i>
        <span class="nav-link-text">Schedules</span>
      </a>
      <ul class="sidenav-second-level collapse" id="navSchedules">

        <?php if( in_array( 'all-schedules.php', $user_access ) ): ?>
        <li>
          <a href="all-schedules.php">All Schedule</a>
        </li>
        <?php endif; ?>
        <?php if( in_array( 'employee-schedules.php', $user_access ) ): ?>
        <li>
          <a href="employee-schedules.php">Employee Schedules</a>
        </li>
        <?php endif; ?>
      
      </ul>
    </li>
  <?php endif; ?>
  <?php if( !empty( array_intersect( array( 'report-payroll.php', 'view-payrolls.php', 'report-thirteenth-month.php', 'view-thirteenths.php' ), $user_access ) ) ): ?>
  <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Reports">
    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#navReports" data-parent="#exampleAccordion" aria-expanded="false">
      <i class="fa fa-fw fa-file-text"></i>
      <span class="nav-link-text">Payroll</span>
    </a>
    <ul class="sidenav-second-level collapse" id="navReports">
      <?php if( in_array( 'report-payroll.php', $user_access ) ): ?>
        <li>
          <a href="report-payroll.php">Generate Payroll</a>
        </li>
      <?php endif; ?>
      <?php if( in_array( 'report-thirteenth-month.php', $user_access ) ): ?>
        <li>
          <a href="report-thirteenth-month.php">Generate 13th Month</a>
        </li>
      <?php endif; ?>
      <?php if( in_array( 'view-payrolls.php', $user_access ) ): ?>
        <li>
          <a href="view-payrolls.php">View Payroll/Payslip</a>
        </li>
      <?php endif; ?>
      <?php if( in_array( 'view-thirteenths.php', $user_access ) ): ?>
        <li>
          <a href="view-thirteenths.php">View 13th Month</a>
        </li>
      <?php endif; ?>
    </ul>
  </li>
  <?php endif; ?>
  <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Vacation">
    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#navVacation" data-parent="#vacationAccordion" aria-expanded="false">
      <i class="fa fa-fw fa-plane"></i>
      <span class="nav-link-text">Vacation</span>
    </a>
    <ul class="sidenav-second-level collapse" id="navVacation">
      <li>
        <a href="<?php echo !empty( array_intersect( array( 'leaves.php' ), $user_access ) ) ? 'leaves.php?act=add' : 'profile.php#leave-calendar-wrapper' ; ?>">Add Vacation</a>
      </li>
      <?php if( !empty( array_intersect( array( 'leaves.php' ), $user_access ) ) ): ?>
      <li>
        <a href="leaves.php">All Vacations</a>
      </li>
      <?php endif; ?>
      <?php if( !empty( array_intersect( array( 'holiday.php' ), $user_access ) ) ): ?>
      <li>
        <a href="holiday.php">Holiday Calendar</a>
      </li>
      <?php endif; ?>
    </ul>
  </li>
  <?php if( !empty( array_intersect( array( 'add-user.php', 'users.php', 'inactive-users.php' ), $user_access ) ) ): ?>
  <li class="nav-item" data-toggle="tooltip" data-placement="right" title="" data-original-title="Employees">
    <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" href="#navUsers" data-parent="#exampleAccordion" aria-expanded="false">
      <i class="fa fa-fw fa-users"></i>
      <span class="nav-link-text">Employees</span>
    </a>
    <ul class="sidenav-second-level collapse" id="navUsers">
      <?php if( in_array( 'add-user.php', $user_access ) ): ?>
      <li>
        <a href="add-user.php">Add Employee</a>
      </li>
      <?php endif; ?>
      <?php if( in_array( 'users.php', $user_access ) ): ?>
      <li>
        <a href="users.php">All Employees</a>
      </li>
      <?php endif; ?>
      <?php if( in_array( 'inactive-users.php', $user_access ) ): ?>
      <li>
        <a href="inactive-users.php">Inactive Employees</a>
      </li>
      <?php endif; ?>
      <?php if( in_array( 'document.php', $user_access ) ): ?>
      <li>
        <a href="document.php">Add Memo</a>
      </li>
      <?php endif; ?>
    </ul>
  </li>
  <?php endif; ?>
</ul>
<ul class="navbar-nav sidenav-toggler">
  <li class="nav-item">
    <a class="nav-link text-center" id="sidenavToggler">
      <i class="fa fa-fw fa-angle-left"></i>
    </a>
  </li>
</ul>