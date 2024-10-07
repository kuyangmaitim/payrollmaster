<?php
if (!defined('ABSPATH')) {
  header('Location: ' . $siteHostAdmin . '404.php');
  exit; //Exit if accessed directly
}
$date      = pcm_current_date();
$dateFrom    = pcm_date_first_day($date);
$dateTo    = pcm_date_last_day($date);
$employees    = $employee->get_all_employees(array('id', 'fname', 'lname', 'idd', 'access_level'));
$monthly_leaves   = $employee->get_users_leave_by_daterange($dateFrom, $dateTo);
$monthly_overtime = $employee->get_users_overtime($dateFrom, $dateTo);
?>
<div class="row">
  <div id="user-timecard" class="col-md-12">
    <div class="card mb-3 bg-light">
      <div class="card-header">
        <strong>Time Card Date: <?php echo $date; ?></strong>
      </div>
      <div class="card-body">
        <div class="card-body-icon"><i class="fa fa-fw fa-calendar"></i></div>
        <table class="table table-bordered filter-table" id="timecardTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th class="fullname">Employee Name</th>
              <th>Schedule</th>
              <th>Log In (AM)</th>
              <th>Log Out(AM)</th>
              <th>Log In(PM)</th>
              <th>Log Out(PM)</th>
              <th>OT In</th>
              <th>OT Out</th>
              <th>OT Hours</th>
              <th>Late Hours</th>
              <th>Work Hours</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (!empty($employees)) {
              $total_late = 0;
              $total_time = 0;
              $total_ot   = 0;
              foreach ($employees as $_employee) {

                $emp_id       = $_employee->id;
                $emp_biometricID   = $_employee->idd;
                $fullname       = $_employee->fname . ' ' . $_employee->lname;
                $record        = new Records($emp_biometricID, $date, $date);
                $logs           = $record->all_datelogs();

                if (empty($logs)) {
                  continue;
                }
                if ($_employee->access_level == 1) {
                  continue;
                }

                $log_value     = $logs[$date];

                $log_data       = $record->get_work_hours($log_value, $date);
                $ot_data        = $record->get_ot_hours($log_value, $date);
                $schedule_date  = $record->get_user_schedule_date($emp_biometricID, $date);

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
                  $schedule_name = $schedule_date['name'];
                  $sched_ftimeIn    = pcm_time_to_unix($date . ' ' . $schedule_date['ftimein']);
                  $sched_ftimeOut   = pcm_time_to_unix($date . ' ' . $schedule_date['ftimeout']);
                  $sched_stimeIn    = pcm_time_to_unix($date . ' ' . $schedule_date['stimein']);
                  $sched_stimeOut   = pcm_time_to_unix($date . ' ' . $schedule_date['stimeout']);
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

            ?>
                <tr>
                  <td class="fullname"><?php echo $fullname; ?></td>
                  <td><?php echo $schedule_name; ?></td>
                  <td id="<?php echo $log_value['ftimeIn']['id']; ?>" class="<?php echo $mark_ftimeIn; ?>">
                    <?php if (!empty($log_value['ftimeIn']['time'])) : ?>
                      <?php if ($log_value['ftimeIn']['absent'] == '1') : ?>
                        <?php echo 'Absent: ' . $log_value['ftimeIn']['comment']; ?>
                      <?php else : ?>
                        <?php echo pcm_unix_to_time($log_value['ftimeIn']['time']); ?>
                      <?php endif; ?>
                    <?php endif; ?>
                  </td>
                  <td id="<?php echo $log_value['ftimeOut']['id']; ?>" class="<?php echo $mark_ftimeOut; ?>">
                    <?php if (!empty($log_value['ftimeOut']['time'])) : ?>
                      <?php if ($log_value['ftimeOut']['absent'] == '1') : ?>
                        <?php echo 'Absent: ' . $log_value['ftimeOut']['comment']; ?>
                      <?php else : ?>
                        <?php echo pcm_unix_to_time($log_value['ftimeOut']['time']); ?>
                      <?php endif; ?>
                    <?php endif; ?>
                  </td>
                  <td id="<?php echo $log_value['stimeIn']['id']; ?>" class="<?php echo $mark_stimeIn; ?>">
                    <?php if (!empty($log_value['stimeIn']['time'])) : ?>
                      <?php if ($log_value['stimeIn']['absent'] == '1') : ?>
                        <?php echo 'Absent: ' . $log_value['stimeIn']['comment']; ?>
                      <?php else : ?>
                        <?php echo pcm_unix_to_time($log_value['stimeIn']['time']); ?>
                      <?php endif; ?>
                    <?php endif; ?>
                  </td>
                  <td id="<?php echo $log_value['stimeOut']['id']; ?>" class="<?php echo $mark_stimeOut; ?>">
                    <?php if (!empty($log_value['stimeOut']['time'])) : ?>
                      <?php if ($log_value['stimeOut']['absent'] == '1') : ?>
                        <?php echo 'Absent: ' . $log_value['stimeOut']['comment']; ?>
                      <?php else : ?>
                        <?php echo pcm_unix_to_time($log_value['stimeOut']['time']); ?>
                      <?php endif; ?>
                    <?php endif; ?>
                  </td>
                  <td id="<?php echo $log_value['OTin']['id']; ?>">
                    <?php if (!empty($log_value['OTin']['time'])) : ?>
                      <?php echo pcm_unix_to_time($log_value['OTin']['time']); ?>
                    <?php endif; ?>
                  </td>
                  <td id="<?php echo $log_value['OTout']['id']; ?>">
                    <?php if (!empty($log_value['OTout']['time'])) : ?>
                      <?php echo pcm_unix_to_time($log_value['OTout']['time']); ?>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php echo $ot_time_spent; ?>
                  </td>
                  <td class="<?php echo ($log_data['late']) ? 'warning' : ''; ?>">
                    <?php echo $log_late; ?>
                  </td>
                  <td>
                    <?php echo $log_time_spent; ?>
                  </td>
                </tr>
              <?php
              }
              ?>
              <tr style="color: #28a745;">
                <th colspan="8" style="text-align: right;">Accumulated Records</th>
                <th><?php echo unix_to_hour($total_ot); ?></th>
                <th><?php echo unix_to_hour($total_late); ?></th>
                <th><?php echo unix_to_hour($total_time); ?> </th>
              </tr>
            <?php
            } else {
            ?>
              <tr>
                <td colspan="11">No record Found</td>
              </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div> <!-- User Leave -->
  <div id="user-leave" class="col-md-12">
    <div class="card mb-3 card text-white bg-primary pm-blue">
      <div class="card-header pm-blue">
        <a href="leaves.php" class="text-white"><strong>Leaves for the month of <?php echo date('F'); ?></strong></a> <button id="add-leave" class="btn btn-sm _dark btn-dark" data-toggle="modal" data-target="#userLeaveApplicationModal">Add Leave</button>
      </div>
      <div class="card-body">
        <div class="card-body-icon"><i class="fa fa-fw fa-plane"></i></div>
        <table class="table table-bordered" id="leaveList" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Date</th>
              <th>Employee Name</th>
              <th>Leave Type</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (!empty($monthly_leaves)) {
              foreach ($monthly_leaves as $leave) {
                $leaveID   = $leave->id;
                $_userID   = $leave->uid;
                $_type     = work_status()[$leave->type];
                $_date     = $leave->date;
                $_status   = $leave->status;
                $fname     = $employee->get_user_data_by_id($_userID, 'fname');
                $lname     = $employee->get_user_data_by_id($_userID, 'lname');
                $_fullname   = $fname . ' ' . $lname;
                $_leave_status   = 'Not Approved';
                $_buttonLabel   = 'Approved';
                if ($_status == 1) {
                  $_leave_status = 'Approved';
                  $_buttonLabel = 'Not Approved';
                }
            ?>
                <tr id="leave-<?php echo $leaveID; ?>">
                  <td><?php echo $_date; ?></td>
                  <td><?php echo $_fullname; ?></td>
                  <td><?php echo $_type; ?></td>
                  <td class="status"><?php echo $_leave_status; ?></td>
                  <td class="action"><button type="button" data-id="<?php echo $leaveID; ?>" data-status="<?php echo $_status; ?>" class="btn btn-secondary _secondary btn-sm approval"><?php echo $_buttonLabel; ?></button>
                    <button type="button" data-id="<?php echo $leaveID; ?>" class="btn btn-danger _danger btn-sm delete">Delete</button>
                  </td>
                </tr>
              <?php
              }
            } else {
              ?><tr>
                <td colspan="5">No Leave applied</td>
              </tr><?php
                  }
                    ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div id="user-overtime" class="col-md-12">
    <div class="card mb-3 card text-white bg-success">
      <div class="card-header">
        <a href="overtime.php" class="text-white"><strong>Overtime for the month of <?php echo date('F'); ?></strong></a> <button class="btn btn-sm _dark btn-dark" data-toggle="modal" data-target="#overtimeApplicationModal">Add Overtime</button>
      </div>
      <div class="card-body">
        <div class="card-body-icon"><i class="fa fa-fw fa-clock-o"></i></div>
        <table class="table table-bordered" id="ovetimeList" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>Date</th>
              <th>Employee Name</th>
              <th>Time Range</th>
              <th>Status</th>
              <th>Action</th>
              <th>Note</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (!empty($monthly_overtime)) {
              foreach ($monthly_overtime as $ovetime) {
                $otID           = $ovetime->id;
                $_ot_userID   = $ovetime->uid;
                $_ot_date     = $ovetime->date;
                $_ot_status   = $ovetime->status;
                $_ot_fname     = $employee->get_user_data_by_id($_ot_userID, 'fname');
                $_ot_lname     = $employee->get_user_data_by_id($_ot_userID, 'lname');
                $_ot_fullname   = $_ot_fname . ' ' . $_ot_lname;
                $ot_status     = 'Approved';
                $_ot_buttonLabel   = 'Not approved';
                if ($_ot_status != 1) {
                  $ot_status       = 'Not approved';
                  $_ot_buttonLabel   = 'Approved';
                }
            ?>
                <tr id="ot-<?php echo $otID; ?>">
                  <td><?php echo $ovetime->date; ?></td>
                  <td><?php echo $_ot_fullname; ?></td>
                  <td><?php echo $ovetime->time_range; ?></td>
                  <td class="status"><?php echo $ot_status; ?></td>
                  <td class="action"><button type="button" data-id="<?php echo $otID; ?>" data-status="<?php echo $_ot_status; ?>" class="btn btn-secondary _secondary btn-sm approval"><?php echo $_ot_buttonLabel ?></button>
                    <button type="button" data-id="<?php echo $otID; ?>" class="btn btn-danger _danger btn-sm delete">Delete</button>
                  </td>
                  <td><?php echo $ovetime->notes; ?></td>
                </tr>
              <?php
              }
            } else {
              ?><tr>
                <td colspan="6">No Overtime applied</td>
              </tr><?php
                  }
                    ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- Modal Pop up -->
<div class="modal fade" id="overtimeApplicationModal" tabindex="-1" role="dialog" aria-labelledby="userApplication" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-white text-white" id="userApplication">Apply Ovetime</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="addUserOvertime-form">
          <div class="form-row">
            <div class="card mb-3">
              <div class="card-body">
                <div id="time-range" class="form-row">
                  <div class="form-group col-md-12">
                    <select class="form-control select2 select2-full" id="biometricID" name="biometricID" required="required">
                      <option value="">Select Employee</option>
                      <?php
                      if (!empty($employees)) {
                        foreach ($employees  as $employee) {
                          if ($employee->access_level == 1) {
                            continue;
                          }
                      ?><option value="<?php echo $employee->id; ?>"><?php echo $employee->lname; ?>, <?php echo $employee->fname; ?></option><?php
                                                                                                                                            }
                                                                                                                                          } else {
                                                                                                                                              ?><option>NO Registered Employee Found</option><?php
                                                                                                                                                                                            }
                                                                                                                                                                                              ?>
                    </select>
                  </div>
                  <div class="form-group col-md-12">
                    <label for="date">Date</label>
                    <input type="text" class="form-control datepicker" id="overtimeDate" placeholder="yyyy-mm-dd" autocomplete="off">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="time-start">Time Start</label>
                    <input id="time-start" type="text" class="form-control time start" id="time-start" required="required">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="time-end">Time End</label>
                    <input id="time-end" type="text" class="form-control time end" id="time-end" required="required">
                  </div>
                  <div class="form-group col-md-12">
                    <label for="notes">Notes</label>
                    <textarea id="notes" class="form-control" required="required"></textarea>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary pm-blue btn-lg">Apply Overtime</button>
              </div>
            </div><!--  card  -->
          </div>
        </form> <!-- addUserSchedule-form  -->
      </div>
    </div>
    </form> <!-- Schedule Form -->
  </div>
</div>
<div class="modal fade" id="userLeaveApplicationModal" role="dialog" aria-labelledby="userApplication" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-white text-white" id="userApplication">Leave Application Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="addLeave-form">
          <div class="form-row">
            <div class="col-md-12 mb-6">
              <div class="card mb-3">
                <div class="card-body">
                  <div class="col-md-12 mb-3 select2-full">
                    <select id="employee" class="form-control select2" name="employee" required>
                      <option value="">--Select Employee--</option>
                      <?php
                      if (!empty($employees)) {
                        foreach ($employees as $employee) {
                          if ($employee->access_level == 1) {
                            continue;
                          }
                      ?><option value="<?php echo $employee->id; ?>"><?php echo $employee->lname . ', ' . $employee->fname; ?></option><?php
                                                                                                                                      }
                                                                                                                                    }
                                                                                                                                        ?>
                    </select>
                  </div>
                  <div class="col-md-12 mb-3">
                    <label class="sr-only" for="leaveDate">Date</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">Date</div>
                      </div>
                      <input type="text" class="form-control datepicker" id="leaveDate" name="leaveDate" placeholder="yyyy-mm-dd" value="" required="required" autocomplete="off">
                    </div>
                  </div>
                  <div class="col-md-12 mb-3">
                    <label class="sr-only" for="leave_type">Apply Leave</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">Apply Leave</div>
                      </div>
                      <select id="leave_type" class="form-control" name="leave_type" required>
                        <option value="">--Select Leave--</option>
                        <?php
                        foreach (work_status() as $_lkey => $_lvalue) {
                        ?><option value="<?php echo $_lkey; ?>"><?php echo $_lvalue; ?></option><?php
                                                                                              }
                                                                                                ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-12 mb-3">
                    <label class="sr-only" for="am_pm">AM/PM</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <div class="input-group-text">AM/PM</div>
                      </div>
                      <select id="am_pm" class="form-control" name="am_pm">
                        <option value="">Whole Day</option>
                        <?php
                        foreach (leave_am_pm() as $key => $value) {
                        ?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
                                                                                          }
                                                                                            ?>
                      </select>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary pm-blue btn-lg">Apply Leave</button>
                </div>
              </div><!--  card  -->
            </div>
          </div>
        </form> <!-- addUserLeave-form  -->
      </div>
      <div class="modal-footer pm-blue">
        <button type="button" class="btn btn-secondary _secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
    </form> <!-- Schedule Form -->
  </div>
</div>