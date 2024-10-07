<?php include_once('header.php'); ?>
<?php
$employee =  new Employee;
$settings =  new Settings;
$dateFrom = '';
$dateTo   = '';
$userID   = '';
if (isset($_POST['generate-timecard'])) {
  $dateFrom = $_POST['dateFrom'];
  $dateTo   = $_POST['dateTo'];
  $userID = $_POST['employee'];
} elseif (isset($_GET['uid']) && is_numeric($_GET['uid'])) {
  $dateFrom = Date('Y-m') . '-01';
  $dateTo   = Date('Y-m') . '-' . get_days_in_month(Date('m'), Date('Y'));
  $userID = $_GET['uid'];
}
function isDateTime($str){
  return is_numeric(strtotime($str));
}
?>
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Employee Timecard</li>
  </ol>
  <!-- Icon Cards-->
  <div id="payroll-timecard-wrapper" class="row">
    <div class="col-12 mb-3">
      <div class="card mb-3">
        <div class="card-header">
          <span data-toggle="tooltip" data-placement="top" title="Tooltip on top">Employee Timecard Form<span>
        </div> <!-- card Header -->
        <div class="card-body">
          <form id="payroll-timecard-form" method="POST">
            <div class="form-row align-items-center">
              <div class="form-group col-md-3 mb-3">
                <label class="sr-only" for="dateFrom">From</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">From</div>
                  </div>
                  <input type="text" class="form-control datepicker" id="dateFrom" name="dateFrom" placeholder="yyyy-mm-dd" value="<?php echo $dateFrom; ?>" required="required" autocomplete="off">
                </div>
              </div>
              <div class="form-group col-md-3 mb-3">
                <label class="sr-only" for="dateTo">To</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">To</div>
                  </div>
                  <input type="text" class="form-control datepicker" id="dateTo" name="dateTo" placeholder="yyyy-mm-dd" value="<?php echo $dateTo; ?>" required="required" autocomplete="off">
                </div>
              </div>
              <div class="form-group col-md-4 mb-3">
                <label class="sr-only" for="employeeList">Employee</label>
                <select class="form-control select2" id="employeeList" name="employee" required="required">
                  <option value="">Select Employee</option>
                  <?php
                  $get_all_employees = $employee->get_all_employees(array('fname', 'lname', 'idd', 'access_level'));
                  if (!empty($get_all_employees)) {
                    foreach ($get_all_employees as $employee) {
                      if ($employee->access_level == 1) {
                        continue;
                      }
                  ?><option value="<?php echo $employee->idd; ?>" <?php echo $employee->idd == $userID ? 'selected' : ''; ?>><?php echo $employee->lname; ?>, <?php echo $employee->fname; ?></option><?php
                                                                                                                                                                                                    }
                                                                                                                                                                                                  } else {
                                                                                                                                                                                                      ?><option>NO Registered Employee Found</option><?php
                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                      ?>
                </select>
              </div>
              <div class="form-group col-auto mb-3">
                <button type="submit" name="generate-timecard" class="btn btn-primary btn-lg pm-blue">Submit</button>
              </div>
            </div>
          </form>
        </div> <!--  card-body -->
      </div> <!-- Card wrapper -->
    </div> <!-- Content Wrapper -->
    <div class="col-12 mb-3">
      <div class="card mb-3">
        <div class="card-header">
          Timecard Report
        </div> <!-- card Header -->
        <div class="card-body">
          <?php
          if (isset($_POST['generate-timecard']) || (isset($_GET['uid']) && is_numeric($_GET['uid']))) {
            $record     = new Records($userID, $dateFrom, $dateTo);
            $logs       = $record->all_datelogs();
            $fname      = $record->get_user_data_by_biometric($userID, 'fname');
            $lname      = $record->get_user_data_by_biometric($userID, 'lname');
            $biom_id    = $record->get_user_data_by_biometric($userID, 'idd');
            $is_nightdiff_enabled  = $record->get_user_data_by_biometric($userID, 'is_nightdiff_enabled');
          ?>
            <div class="row">
              <div class="form-group col-md-12 mb-3">
                <span id="biom-id" class="d-none"><?php echo $biom_id; ?></span>
                <h5>Employee Name: <span id="employee-name"><?php echo $lname . ', ' . $fname; ?></span></h5>
                <p><span id="date-from">Date From: <?php echo $dateFrom; ?></span><br />
                  <span id="date-to">Date To: <?php echo $dateTo; ?></span>
                </p>
                <button type="button" id="openAddlogModal" data-id="<?php echo $userID; ?>" class="btn btn-outline-success _success btn-sm" data-toggle="modal" data-target="#addLogModal">Add New Log</button>
                <button type="button" id="download-log" class="btn btn-success btn-sm _success float-right"><i class="fa fa-fw fa-download"></i> Download Time Log ( PDF )</button>
                <button type="button" id="download-log-csv" class="btn btn-success btn-sm _success float-right mr-2"><i class="fa fa-fw fa-download"></i> Download Time Log ( CSV )</button>
              </div>
            </div>
            <table class="table table-bordered" id="timecardTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Date</th>
                  <th style="display:none !important;">Fullname</th>
                  <th>Schedule</th>
                  <th>Log In(AM)</th>
                  <th>Log Out(AM)</th>
                  <th>Log In(PM)</th>
                  <th>Log Out(PM)</th>
                  <th>OT In</th>
                  <th>OT Out</th>
                  <th>OT Hours</th>
                  <th>Night Diff. Hours</th>
                  <th>Late Hours</th>
                  <th>Work Hours</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Date</th>
                  <th style="display:none !important;">Fullname</th>
                  <th>Schedule</th>
                  <th>Log In(AM)</th>
                  <th>Log Out(AM)</th>
                  <th>Log In(PM)</th>
                  <th>Log Out(PM)</th>
                  <th>OT In</th>
                  <th>OT Out</th>
                  <th>OT Hours</th>
                  <th>Night Diff. Hours</th>
                  <th>Late Hours</th>
                  <th>Work Hours</th>
                </tr>
              </tfoot>
              <tbody>
                <?php
                $timelog = array();
                $timelogcsv = array();
                if (!empty($logs)) {
                  $total_late = 0;
                  $total_time = 0;
                  $total_ot   = 0;
                  $total_night_diff = 0;

                  foreach ($logs as $date_log => $log_value) {
                    $log_data       = $record->get_work_hours($log_value, $date_log);
                    $ot_data        = $record->get_ot_hours($log_value, $date_log);
                    $schedule_date  = $record->get_user_schedule_date($userID, $date_log);
                    $log_late       = unix_to_hour($log_data['late']);
                    $log_time_spent = unix_to_hour($log_data['time_consume']);
                    $ot_time_spent  = unix_to_hour($ot_data);
                    $total_late     = $total_late + $log_data['late'];
                    $total_time     = $total_time + $log_data['time_consume'];
                    $total_ot       = $total_ot + $ot_data;
                    $schedule_name  = "Open Schedule";
                    $mark_ftimeIn    = '';
                    $mark_ftimeOut   = '';
                    $mark_stimeIn    = '';
                    $mark_stimeOut   = '';
                    if (!empty($schedule_date)) {
                      $schedule_name    = $schedule_date['name'];
                      $sched_ftimeIn    = pcm_time_to_unix($date_log . ' ' . $schedule_date['ftimein']);
                      $sched_ftimeOut   = pcm_time_to_unix($date_log . ' ' . $schedule_date['ftimeout']);
                      $sched_stimeIn    = pcm_time_to_unix($date_log . ' ' . $schedule_date['stimein']);
                      $sched_stimeOut   = pcm_time_to_unix($date_log . ' ' . $schedule_date['stimeout']);
                      // mark time log if late
                      if (!empty($log_value['ftimeIn']['time'])) :
                        $ftimeIn = $log_value['ftimeIn']['time'];
                        if ($sched_ftimeIn < $ftimeIn) {
                          $mark_ftimeIn = 'warning';
                        }
                      endif;
                      if (!empty($log_value['ftimeOut']['time'])) :
                        $ftimeOut = $log_value['ftimeOut']['time'];
                        if ($sched_ftimeOut > $ftimeOut) {
                          $mark_ftimeOut = 'warning';
                        }
                      endif;
                      if (!empty($log_value['stimeIn']['time'])) :
                        $stimeIn = $log_value['stimeIn']['time'];
                        if ($sched_stimeIn < $stimeIn) {
                          $mark_stimeIn = 'warning';
                        }
                      endif;
                      if (!empty($log_value['stimeOut']['time'])) :
                        $stimeOut = $log_value['stimeOut']['time'];
                        if ($sched_stimeOut > $stimeOut) {
                          $mark_stimeOut = 'warning';
                        }
                      endif;
                    }
                    $ftimeIn    = '';
                    $ftimeOut   = '';
                    $stimeIn    = '';
                    $stimeOut   = '';
                    $OTin       = '';
                    $OTout      = '';

                ?>
                    <tr>
                      <td class="fullname" style="display:none !important;"><?php echo $lname . ', ' . $fname; ?></td>
                      <td class="logDate"><?php echo $date_log; ?></td>
                      <td><?php echo $schedule_name; ?></td>
                      <td id="<?php echo $log_value['ftimeIn']['id']; ?>" class="<?php echo $mark_ftimeIn; ?>">
                        <?php if (!empty($log_value['ftimeIn']['time'])) : $ftimeIn = pcm_unix_to_time($log_value['ftimeIn']['time']); ?>
                          <a href="#" class="update" data-toggle="modal" data-target="#updateLogModal" data-sched="ftimeIn" data-date="<?php echo $date_log; ?>" data-time="<?php echo get_log_time($log_value['ftimeIn']['time']); ?>" data-id="<?php echo $log_value['ftimeIn']['id']; ?>" data-comment="<?php echo $log_value['ftimeIn']['comment']; ?>"><i class="fa fa-fw fa-edit"></i></a>
                          <?php if ($log_value['ftimeIn']['absent'] == '1') : ?>
                            <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $log_value['ftimeIn']['comment']; ?>"><?php echo 'Absent: ' . $log_value['ftimeIn']['comment']; ?>
                            <?php else : ?>
                              <?php if (empty($log_value['ftimeIn']['comment'])) : ?>
                                <?php echo $ftimeIn; ?>
                              <?php else : ?>
                                <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $log_value['ftimeIn']['comment']; ?>"><?php echo $ftimeIn; ?>
                                <?php endif; ?>
                              <?php endif; ?>
                              <a href="#" class="delete-log text-danger"><i class="fa fa-fw fa-trash"></i></a>
                            <?php else : ?>
                              <a href="#" class="add-sched-log text-center text-success" data-toggle="modal" data-target="#addDateLogModal" data-date="<?php echo $date_log; ?>" data-sched="ftimeIn" data-id="<?php echo $userID; ?>"><i class="fa fas fa-calendar"></i>
                              <?php endif; ?>
                      </td>
                      <td id="<?php echo $log_value['ftimeOut']['id']; ?>" class="<?php echo $mark_ftimeOut; ?>">
                        <?php if (!empty($log_value['ftimeOut']['time'])) : $ftimeOut = pcm_unix_to_time($log_value['ftimeOut']['time']); ?>
                          <a href="#" class="update" data-toggle="modal" data-target="#updateLogModal" data-sched="ftimeOut" data-date="<?php echo $date_log; ?>" data-time="<?php echo get_log_time($log_value['ftimeOut']['time']); ?>" data-id="<?php echo $log_value['ftimeOut']['id']; ?>" data-comment="<?php echo $log_value['ftimeOut']['comment'] ?>"><i class="fa fa-fw fa-edit"></i></a>
                          <?php if ($log_value['ftimeOut']['absent'] == '1') : ?>
                            <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $log_value['ftimeOut']['comment']; ?>"><?php echo 'Absent: ' . $log_value['ftimeOut']['comment']; ?>
                            <?php else : ?>
                              <?php if (empty($log_value['ftimeOut']['comment'])) : ?>
                                <?php echo $ftimeOut; ?>
                              <?php else : ?>
                                <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $log_value['ftimeOut']['comment']; ?>"><?php echo $ftimeOut; ?>
                                <?php endif; ?>
                              <?php endif; ?>
                              <a href="#" class="delete-log text-danger"><i class="fa fa-fw fa-trash"></i></a>
                            <?php else : ?>
                              <a href="#" class="add-sched-log text-center text-success" data-toggle="modal" data-target="#addDateLogModal" data-date="<?php echo $date_log; ?>" data-sched="ftimeOut" data-id="<?php echo $userID; ?>"><i class="fa fas fa-calendar"></i>
                              <?php endif; ?>
                      </td>
                      <td id="<?php echo $log_value['stimeIn']['id']; ?>" class="<?php echo $mark_stimeIn; ?>">
                        <?php if (!empty($log_value['stimeIn']['time'])) : $stimeIn = pcm_unix_to_time($log_value['stimeIn']['time']); ?>
                          <a href="#" class="update" data-toggle="modal" data-target="#updateLogModal" data-sched="stimeIn" data-date="<?php echo $date_log; ?>" data-time="<?php echo get_log_time($log_value['stimeIn']['time']); ?>" data-id="<?php echo $log_value['stimeIn']['id']; ?>" data-comment="<?php echo $log_value['stimeIn']['comment'] ?>"><i class="fa fa-fw fa-edit"></i></a>
                          <?php if ($log_value['stimeIn']['absent'] == '1') : ?>
                            <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $log_value['stimeIn']['comment']; ?>"><?php echo 'Absent: ' . $log_value['stimeIn']['comment']; ?>
                            <?php else : ?>
                              <?php if (empty($log_value['stimeIn']['comment'])) : ?>
                                <?php echo $stimeIn; ?>
                              <?php else : ?>
                                <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $log_value['stimeIn']['comment']; ?>"><?php echo $stimeIn; ?>
                                <?php endif; ?>
                              <?php endif; ?>
                              <a href="#" class="delete-log text-danger"><i class="fa fa-fw fa-trash"></i></a>
                            <?php else : ?>
                              <a href="#" class="add-sched-log text-center text-success" data-toggle="modal" data-target="#addDateLogModal" data-date="<?php echo $date_log; ?>" data-sched="stimeIn" data-id="<?php echo $userID; ?>"><i class="fa fas fa-calendar"></i>
                              <?php endif; ?>
                      </td>
                      <td id="<?php echo $log_value['stimeOut']['id']; ?>" class="<?php echo $mark_stimeOut; ?>">
                        <?php if (!empty($log_value['stimeOut']['time'])) : $stimeOut = pcm_unix_to_time($log_value['stimeOut']['time']); ?>
                          <a href="#" class="update" data-toggle="modal" data-target="#updateLogModal" data-sched="stimeOut" data-date="<?php echo $date_log; ?>" data-time="<?php echo get_log_time($log_value['stimeOut']['time']); ?>" data-id="<?php echo $log_value['stimeOut']['id']; ?>" data-comment="<?php echo $log_value['stimeOut']['comment'] ?>"><i class="fa fa-fw fa-edit"></i></a>
                          <?php if ($log_value['stimeOut']['absent'] == '1') : ?>
                            <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $log_value['stimeOut']['comment']; ?>"><?php echo 'Absent: ' . $log_value['stimeOut']['comment']; ?>
                            <?php else : ?>
                              <?php if (empty($log_value['stimeOut']['comment'])) : ?>
                                <?php echo $stimeOut; ?>
                              <?php else : ?>
                                <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $log_value['stimeOut']['comment']; ?>"><?php echo $stimeOut; ?>
                                <?php endif; ?>
                              <?php endif; ?>
                              <a href="#" class="delete-log text-danger"><i class="fa fa-fw fa-trash"></i></a>
                            <?php else : ?>
                              <a href="#" class="add-sched-log text-center text-success" data-toggle="modal" data-target="#addDateLogModal" data-date="<?php echo $date_log; ?>" data-sched="stimeOut" data-id="<?php echo $userID; ?>"><i class="fa fas fa-calendar"></i>
                              <?php endif; ?>
                      </td>
                      <td id="<?php echo $log_value['OTin']['id']; ?>">
                        <?php if (!empty($log_value['OTin']['time'])) : ?>
                          <?php
                          $_OTin = $log_value['OTin']['time'];
                          $am_pm = get_am_pm(date('h:i:A', $_OTin)); // if time set is between 12:00am and 6:00pm, add 24 hours
                          if ($am_pm == 'AM') {
                            $_OTin += 86400;
                          }
                          $OTin = pcm_unix_to_time($_OTin);
                          ?>
                          <a href="#" class="update" data-toggle="modal" data-target="#updateLogModal" data-sched="OTin" data-date="<?php echo $date_log; ?>" data-time="<?php echo get_log_time($log_value['OTin']['time']); ?>" data-id="<?php echo $log_value['OTin']['id']; ?>" data-comment="<?php echo $log_value['OTin']['comment'] ?>"><i class="fa fa-fw fa-edit"></i></a>
                          <?php if (empty($log_value['OTin']['comment'])) : ?>
                            <?php echo $OTin; ?>
                          <?php else : ?>
                            <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $log_value['OTin']['comment']; ?>"><?php echo $OTin; ?>
                            <?php endif; ?>
                            <a href="#" class="delete-log text-danger"><i class="fa fa-fw fa-trash"></i></a>
                          <?php else : ?>
                            <a href="#" class="add-sched-log text-center text-success" data-toggle="modal" data-target="#addDateLogModal" data-date="<?php echo $date_log; ?>" data-sched="OTin" data-id="<?php echo $userID; ?>"><i class="fa fas fa-calendar"></i>
                            <?php endif; ?>
                      </td>
                      <td id="<?php echo $log_value['OTout']['id']; ?>">
                        <?php if (!empty($log_value['OTout']['time'])) : ?>
                          <?php
                          $_OTout = $log_value['OTout']['time'];
                          $am_pm   = get_am_pm(date('h:i:A', $_OTout)); // if time set is between 12:00am and 6:00pm, add 24 hours
                          if ($am_pm == 'AM') {
                            $_OTout += 86400;
                          }
                          $OTout = pcm_unix_to_time($_OTout);
                          ?>
                          <a href="#" class="update" data-toggle="modal" data-target="#updateLogModal" data-sched="OTout" data-date="<?php echo $date_log; ?>" data-time="<?php echo get_log_time($log_value['OTout']['time']); ?>" data-id="<?php echo $log_value['OTout']['id']; ?>" data-comment="<?php echo $log_value['OTout']['comment'] ?>"><i class="fa fa-fw fa-edit"></i></a>
                          <?php if (empty($log_value['OTout']['comment'])) : ?>
                            <?php echo $OTout; ?>
                          <?php else : ?>
                            <span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $log_value['OTout']['comment']; ?>"><?php echo $OTout; ?>
                            <?php endif; ?>
                            <a href="#" class="delete-log text-danger"><i class="fa fa-fw fa-trash"></i></a>
                          <?php else : ?>
                            <a href="#" class="add-sched-log text-center text-success" data-toggle="modal" data-target="#addDateLogModal" data-date="<?php echo $date_log; ?>" data-sched="OTout" data-id="<?php echo $userID; ?>"><i class="fa fas fa-calendar"></i>
                            <?php endif; ?>
                      </td>
                      <td>
                        <?php echo $ot_time_spent; ?>
                      </td>
                      <td>
                        <?php
                        // get night diff
                        $night_diff = 0;
                        if ($is_nightdiff_enabled == 1) { // if user night diff is enabled
                          if (!empty($log_value['ftimeIn']['time']) && !empty($log_value['ftimeOut']['time'])) {
                            $_ftimeIn = $log_value['ftimeIn']['time'];
                            $_ftimeOut = $log_value['ftimeOut']['time'];
                            if ($log_value['ftimeIn']['absent'] == '0' && $log_value['ftimeOut']['absent']) {
                              $night_diff += night_difference($_ftimeIn, $_ftimeOut);
                            }
                          }
                          if (!empty($log_value['stimeIn']['time']) && !empty($log_value['stimeOut']['time'])) {
                            $_stimeIn = $log_value['stimeIn']['time'];
                            $_stimeOut = $log_value['stimeOut']['time'];
                            if ($log_value['stimeIn']['absent'] == '0' && $log_value['stimeOut']['absent']) {
                              $night_diff += night_difference($_stimeIn, $_stimeOut);
                            }
                          }
                          if (!empty($log_value['ftimeIn']['time']) && empty($log_value['ftimeOut']['time']) && !empty($log_value['stimeOut']['time']) && empty($log_value['stimeIn']['time'])) {
                            $_ftimeIn = $log_value['ftimeIn']['time'];
                            $_stimeOut = $log_value['stimeOut']['time'];
                            if ($log_value['ftimeIn']['absent'] == '0' && $log_value['stimeOut']['absent']) {
                              $night_diff += night_difference($_ftimeIn, $_stimeOut);
                            }
                          }
                          if (!empty($log_value['OTin']['time']) && !empty($log_value['OTout']['time'])) {
                            $__OTin = $log_value['OTin']['time'];
                            $__OTout = $log_value['OTout']['time'];
                            $night_diff += night_difference($__OTin, $__OTout);
                          }
                        } else { // if user night diff is disabled
                          $night_diff = 0;
                        }
                        $total_night_diff += $night_diff > 0 ? ($night_diff * 3600) : 0;
                        $_night_diff = $night_diff > 0 ? unix_to_hour($night_diff * 3600) : unix_to_hour(0);
                        echo $_night_diff;
                        ?>
                      </td>
                      <td class="<?php echo ($log_data['late']) ? 'warning' : ''; ?>">
                        <?php echo $log_late; ?>
                      </td>
                      <td>
                        <?php echo $log_time_spent; ?>
                      </td>
                    </tr>
                  <?php
                    if ($log_value['ftimeIn']['absent'] == '1') {
                      $ftimeIn = 'Absent: ' . $log_value['ftimeIn']['comment'];
                    }
                    if ($log_value['ftimeOut']['absent'] == '1') {
                      $ftimeOut = 'Absent: ' . $log_value['ftimeOut']['comment'];
                    }
                    if ($log_value['stimeIn']['absent'] == '1') {
                      $stimeIn = 'Absent: ' . $log_value['stimeIn']['comment'];
                    }
                    if ($log_value['stimeOut']['absent'] == '1') {
                      $stimeOut = 'Absent: ' . $log_value['stimeOut']['comment'];
                    }
                    $timelog[] = array(
                      'date'       => $date_log,
                      'schedule'   => $schedule_name,
                      'ftimeIn'    => isDateTime($ftimeIn) ? get_log_time(strtotime($ftimeIn)) : $ftimeIn,
                      'ftimeOut'   => isDateTime($ftimeOut) ? get_log_time(strtotime($ftimeOut)) : $ftimeOut,
                      'stimeIn'    => isDateTime($stimeIn) ? get_log_time(strtotime($stimeIn)) : $stimeIn,
                      'stimeOut'   => isDateTime($stimeOut) ? get_log_time(strtotime($stimeOut)) : $stimeOut,
                      'OTin'       => isDateTime($OTin) ? get_log_time(strtotime($OTin)) : $OTin,
                      'OTout'      => isDateTime($OTout) ? get_log_time(strtotime($OTout)) : $OTout,
                      'OTSpent'    => $ot_time_spent,
                      'nightDiff'  => $_night_diff,
                      'logSpent'   => $log_late,
                      'timeSpent'  => $log_time_spent,
                      'totalOt'    => unix_to_hour($total_ot),
                      'totalNightD' => unix_to_hour($total_night_diff),
                      'totalLate'  => unix_to_hour($total_late),
                      'totalTime'  => unix_to_hour($total_time),
                    );
                    $timelogcsv[] = array(
                      'date'       => date('F d, Y', strtotime($date_log)),
                      'schedule'   => $schedule_name,
                      'ftimeIn'    => isDateTime($ftimeIn) ? get_log_time(strtotime($ftimeIn)) : $ftimeIn,
                      'ftimeOut'   => isDateTime($ftimeOut) ? get_log_time(strtotime($ftimeOut)) : $ftimeOut,
                      'stimeIn'    => isDateTime($stimeIn) ? get_log_time(strtotime($stimeIn)) : $stimeIn,
                      'stimeOut'   => isDateTime($stimeOut) ? get_log_time(strtotime($stimeOut)) : $stimeOut,
                      'OTin'       => isDateTime($OTin) ? get_log_time(strtotime($OTin)) : $OTin,
                      'OTout'      => isDateTime($OTout) ? get_log_time(strtotime($OTout)) : $OTout
                    );
                  }
                  ?>
                  <tr style="color: #28a745;">
                    <th colspan="8" style="text-align: right;">Accumulated Records</th>
                    <th><?php echo unix_to_hour($total_ot); ?></th>
                    <th><?php echo unix_to_hour($total_night_diff); ?></th>
                    <th><?php echo unix_to_hour($total_late); ?></th>
                    <th><?php echo unix_to_hour($total_time); ?> </th>
                  </tr>
                <?php
                } else {
                ?><tr>
                    <td colspan="11">No record Found</td>
                  </tr><?php
                      }
                        ?>
              </tbody>
            </table>
            <div id="time-log" data-log='<?php echo serialize($timelog); ?>' data-logcount="<?php echo count($timelog); ?>" style="display:none;">
              <form action="generate-log-csv.php" id="generate-log-csv-form" method="post">
                <input type="hidden" name="emp_name" value="<?php echo strtolower($lname . '-' . $fname); ?>" />
                <input type="hidden" name="emp_bid" value="<?php echo $biom_id; ?>" />
                <textarea name="emp_log" id="emp_log" cols="30" rows="10"><?php echo serialize($timelogcsv); ?></textarea>
              </form>
            <?php
          }
            ?>
            </div> <!-- card body -->
        </div> <!-- .card -->
      </div> <!-- Card Wrapper -->
    </div> <!-- payroll-Timecard-wrapper -->
  </div><!-- /.container-fluid-->
  <!-- Modal -->
  <!-- Add new Time Log -->
  <div class="modal fade" id="addLogModal" tabindex="-1" role="dialog" aria-labelledby="addLog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form id="addlog-form">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-white" id="addLog">Add New Time Log</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="form-group col-md-12">
                <label for="date">Date</label>
                <input type="text" class="form-control datepicker" id="date" placeholder="yyyy-mm-dd" autocomplete="off">
              </div>
              <div class="form-group col-md-12">
                <label for="__absent">Absent</label>
                <input type="checkbox" id="__absent" />
              </div>
            </div>
            <div id="log-sections" class="row">
              <div class="form-group col-md-6">
                <label for="ftimein">Time In (AM)</label>
                <input type="text" class="form-control timepicker" id="ftimein" placeholder="0:00am" autocomplete="off">
              </div>
              <div class="form-group col-md-6">
                <label for="ftimeout">Time Out (AM)</label>
                <input type="text" class="form-control timepicker" id="ftimeout" placeholder="0:00am" autocomplete="off">
              </div>
              <div class="form-group col-md-6">
                <label for="stimein">Time In (PM)</label>
                <input type="text" class="form-control timepicker" id="stimein" placeholder="0:00pm" autocomplete="off">
              </div>
              <div class="form-group col-md-6">
                <label for="stimeout">Time Out (PM)</label>
                <input type="text" class="form-control timepicker" id="stimeout" placeholder="0:00pm" autocomplete="off">
              </div>
              <div class="form-group col-md-6">
                <label for="stimeout">OT In </label>
                <input type="text" class="form-control timepicker" id="OTin" placeholder="0:00" autocomplete="off">
              </div>
              <div class="form-group col-md-6">
                <label for="stimeout">OT Out</label>
                <input type="text" class="form-control timepicker" id="OTout" placeholder="0:00" autocomplete="off">
              </div>
            </div>
            <div id="reason-section" class="d-none row">
              <div class="form-group col-md-12">
                <label for="__absent_reason">Reason</label>
                <input type="text" class="form-control" id="__absent_reason" autocomplete="off" />
              </div>
            </div>
          </div>
          <div class="modal-footer pm-blue">
            <button type="button" class="btn btn-secondary _secondary" data-dismiss="modal">Close</button>
            <input type="hidden" id="biometricID" name="biometricID" value="">
            <input type="submit" class="btn btn-danger _danger" value="Add Log">
          </div>
        </div>
      </form>
    </div>
  </div>
  <!-- Add new Date Log -->
  <div class="modal fade" id="addDateLogModal" tabindex="-1" role="dialog" aria-labelledby="addLog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form id="scheduleLog-form">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-white" id="addLog">Add Schedule Log</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="sr-only" for="schedDateLog">Date</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text">Date</div>
                  </div>
                  <input type="text" class="form-control datepicker" id="schedDateLog" required="required" autocomplete="off">
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="sr-only" for="schedTimeLog">Time</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text">Time</div>
                  </div>
                  <input type="text" class="form-control timepicker" id="schedTimeLog" required="required" autocomplete="off">
                </div>
              </div>
              <div class="col-md-12 mb-3">
                <label>Notes</label>
                <div class="input-group mb-2">
                  <textarea name="notes" id="schedComment" class="form-control" rows="4" required="required" autocomplete="off"></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary _secondary" data-dismiss="modal">Close</button>
            <input type="hidden" id="schedulelog" name="schedulelog" value="">
            <input type="hidden" id="biometricID" name="biometricID" value="">
            <input type="submit" class="btn btn-danger _danger" value="Add Schedule Log">
          </div>
        </div>
      </form>
    </div>
  </div>
  <!-- update Time Log -->
  <div class="modal fade" id="updateLogModal" tabindex="-1" role="dialog" aria-labelledby="updateLog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form id="updatelog-form">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-white" id="updateLog">Update Log</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="sr-only" for="newDateLog">Date</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text">Date</div>
                  </div>
                  <input type="text" class="form-control datepicker" id="newDateLog" required="required" autocomplete="off">
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="sr-only" for="newTimeLog">Time</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text">Time</div>
                  </div>
                  <input type="text" class="form-control timepicker" id="newTimeLog" required="required" autocomplete="off">
                </div>
              </div>
              <div class="col-md-12 mb-3">
                <label>Notes</label>
                <div class="input-group mb-2">
                  <textarea name="notes" id="comment" class="form-control" rows="4" required="required" autocomplete="off"></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary _secondary" data-dismiss="modal">Close</button>
            <input type="hidden" id="logID" name="logID" value="">
            <input type="hidden" id="checkinSched" name="checkinSched" value="">
            <input type="submit" class="btn btn-danger _danger" value="Confirm">
          </div>
        </div>
      </form>
    </div>
  </div>
  <script>
    jQuery(document).ready(function($) {
      // download timelog csv code
      $('button#download-log-csv').on('click', function() {
        $('form#generate-log-csv-form').trigger('submit');
      });
    });
  </script>
  <?php include_once('footer.php'); ?>