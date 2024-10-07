<?php include_once('header.php'); ?>
<?php
  $settings     = new Settings;
  $employee     = new Employee;
  $files        = new EmpFiles;
  $memo         = new Memo;
  $leave_info   = new LeaveInformation;

  $user_id = $sessionUserId;
  $user_data    = $employee->get_user( (int)$user_id );
  $user_leaves  = $employee->get_user_leaves( (int)$user_id );
  $requested_sched  = $employee->get_all_user_requested_sched();
  $user_files   = $files->user_files( $user_data->id );
  $user_memos   = $memo->get_user_memo( $user_data->id );

  $file_type    = isset($_GET['type']) && !empty($_GET['type']) ? $_GET['type'] : false;

  $calendar_data = array();
  if( !empty( $user_leaves ) ){
    foreach ( $user_leaves as $leave ) {
      $status = $leave->status ? 'Approved' : 'Pending Approval';
      $calendar_data[] = array(
        'id'        => $leave->id,
        'uid'       => $leave->uid,
        'start'     => $leave->date,
        'type'      => $leave->type,
        'title'     => work_status()[$leave->type],
        'status'    => $status,
        'dataType'  => 'leave',
        'color'     => '#1eb8de',
        'timeSchedule'  => ''
      );
    }
  }
  if( !empty( $requested_sched ) ){
     // var_dump($requested_sched);
    foreach ( $requested_sched as $shed ) {
      $user_status  = $employee->get_user_data( $shed->uid, array( 'work_status' ) )[0]->work_status;
      if( $user_status == 0 ){ continue; }
      $userSchedule     = $settings->get_settings_data( $shed->schedule, true );
      $timeSchedule     = 'Open';
      $scheduleName     = '';
      $schedColor       = '';
      $color=array(1=>'#007bff',2=>'#fd7e14',3=>'#c0eb3c',4=>'#20c997',5=>'#e83e8c' );
      $colorv=rand(1,5);
      if( !empty( $userSchedule ) ){
        $userSchedule  = unserialize( $userSchedule );
        $scheduleName  = $userSchedule['name'];
        $timeSchedule  = $userSchedule['ftimein'].' to '.$userSchedule['stimeout'];
        $schedColor    = $userSchedule['schedColor'] ?: '#64e3bd';
      }
      if( $shed->schedule == 0 ){
        $scheduleName = 'Open Schedule';
        $schedColor   = '#64e3bd';
      }
      $status = $shed->status ? 'Approved' : 'Pending Approval';
      $calendar_data[] = array(
        'id'        => $shed->id,
        'uid'       => $shed->uid,
        'start'     => $shed->date,
        'type'      => $shed->schedule,
        'title'     =>  ucfirst( strtolower( $employee->get_user_data_by_id( $shed->uid, 'lname' ) ) ) .' - '. $scheduleName,
        'status'    => $status,
        'dataType'  => 'schedule',
        'color'     => $schedColor,
        'timeSchedule'  => $timeSchedule
      );
    }
  }
  $available_sil = $leave_info->get_user_sil( $user_data->id );
  $formatted_available_sil = $available_sil ? $available_sil : '';
  $used_leave_count = $leave_info->get_user_payable_leaves( $user_leaves );
  $formatted_leave_count = $used_leave_count > 0 ? $used_leave_count : 0;
?>
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Dashboard</a>
    </li>
    <li class="breadcrumb-item active"> Employee Schedules</li>
  </ol>

    <!-- User Loans and Calendar -->
    <div class="">

      <div class="row">
        <div id="leave-calendar-wrapper" class="col-md-12 wptf-section">
          <div class="card mb-3">
            <div class="card-header">
              User Leave / Requested Schedule Calendar
            </div>
            <div class="card-body">
              <div id="calendar-schedule"></div>
            </div> <!-- Body Card -->
          </div> <!-- Card wrapper -->
        </div> <!-- leave-calendar-wrapper -->
     

      </div>
    </div>
    <!-- END User Loans and Calendar -->
  </div> <!-- adduser-form -->
</div><!-- /.container-fluid-->
<!-- Modal Pop up -->
<div class="modal fade" id="userLeaveModal" tabindex="-1" role="dialog" aria-labelledby="userLeave" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white" id="userLeave">Add Leave / Schedule</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label>Leave Date : <span id="selected-date"></span></label>
          <form id="addUserLeave-form">
            <div class="form-row">
              <div class="col-md-12 mb-6">
                <div class="card mb-3">
                  <div class="card-header">
                    Leave 
                  </div>
                  <div class="card-body">
                    <div class="col-md-12 mb-3">
                      <label class="sr-only" for="user_leave">Leave</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">Leave</div>
                        </div>
                        <select id="user_leave" class="form-control" name="user_leave">
                          <option value="">--Select Leave--</option>
                          <?php
                          foreach ( work_status() as $key => $value) {
                            ?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
                          }
                          ?>
                        </select>   
                      </div>
                    </div>
                    <input type="hidden" id="userID" name="userID" value="<?php echo $user_data->id; ?>">
                    <input type="hidden" id="leaveDate" name="leaveDate" value="">
                    <button type="submit" class="btn btn-lg btn-primary pm-blue">Save Leave</button>
                  </div>
                </div><!--  card  --> 
              </div>     
            </div>  
          </form>  <!-- addUserLeave-form  --> 
          <form id="addUserSchedule-form">
            <div class="form-row">
              <div class="col-md-12 mb-6">
                <div class="card mb-3">
                  <div class="card-header">
                    Schedule
                  </div>
                  <div class="card-body">
                    <div class="col-md-12 mb-3">
                      <label class="sr-only" for="schedule">Schedule</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">Schedule</div>
                        </div>
                        <select id="schedule" class="form-control" name="schedule" required="required">
                          <option value="">Select Schedule</option>
                          <option value="0">Open Schedule</option>
                          <?php
                          $schedules = $settings->get_settings_by_name('schedules');
                          if( !empty( $schedules ) ){
                            foreach ($schedules as $schedule ) {
                              $scheduleID   = $schedule->id;
                              $schedule     = unserialize( $schedule->value );
                              ?><option value="<?php echo $scheduleID; ?>"><?php echo $schedule['name']; ?></option><?php
                            }
                          }
                          ?>
                        </select> 
                      </div>
                    </div>
                    <input type="hidden" id="userID" name="userID" value="<?php echo $user_data->id; ?>">
                    <input type="hidden" id="scheduleDate" name="scheduleDate" value="">
                    <button type="submit" class="btn btn-lg btn-primary pm-blue">Save Schedule</button>
                  </div>
                </div><!--  card  --> 
              </div>     
            </div>  
          </form>  <!-- addUserSchedule-form  --> 
            
        </div>
        <div class="modal-footer pm-blue">   
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>          
        </div>
      </div>
    </form> <!-- Schedule Form -->
  </div>
</div>
<!-- Update Leave -->
<div class="modal fade" id="updateLeaveModal" tabindex="-1" role="dialog" aria-labelledby="updateLeave" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="updateUserLeave-form">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white" id="updateLeave">Update Leave</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                <div id="date-label" class="form-group col-md-12">
                  <label>Leave Date : <span id="selected-date"></span></label>
                  <p>Leave Status: <span id="leave-status"></span></p>
                </div>
                <div class="col-md-12 mb-3">
                  <label class="sr-only" for="user_leave">Leave</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">Leave</div>
                    </div>
                    <select id="user_leave" class="form-control" name="user_leave">
                      <option value="">--Select Leave--</option>
                      <?php
                      foreach ( work_status() as $key => $value) {
                        ?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
                      }
                      ?>
                    </select>   
                  </div>
                </div>
            </div>      
        </div>
        <div class="modal-footer">
          <input type="hidden" id="leaveID" name="leaveID" value="">
          <button type="button" class="btn btn-danger" data-id="" id="deleteLeave">Delete Leave</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary pm-blue">Update / Approve Leave</button>
        </div>
      </div>
    </form> <!-- Update leave Form -->
  </div>
</div>
<!-- Update Schedule -->
<div class="modal fade" id="updateScheduleModal" tabindex="-1" role="dialog" aria-labelledby="updateSchedule" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="updateSchedule-form">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white" id="updateSchedule">Update Schedule</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                <div id="date-label" class="form-group col-md-12">
                  <label>Schedule Date : <span id="selected-date"></span></label>
                </div>
                <div class="col-md-12 mb-3">
                  <label class="sr-only" for="user_leave">Schedule</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">Schedule</div>
                    </div>
                      <select id="schedule" class="form-control" name="schedule" required="required">
                        <option value="">Select Schedule</option>
                        <option value="0">Open Schedule</option>
                        <?php
                        $schedules = $settings->get_settings_by_name('schedules');
                        if( !empty( $schedules ) ){
                          foreach ($schedules as $schedule ) {
                            $scheduleID   = $schedule->id;
                            $schedule     = unserialize( $schedule->value );
                            ?><option value="<?php echo $scheduleID; ?>"><?php echo $schedule['name']; ?></option><?php
                          }
                        }
                        ?>
                      </select> 
                  </div>
                </div>
            </div>      
        </div>
        <div class="modal-footer">
          <input type="hidden" id="scheduleID" name="scheduleID" value="">
          <button type="button" class="btn btn-danger" data-id="" id="deleteSchedule">Delete Schedule</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary pm-blue">Update Schedule</button>
        </div>
      </div>
    </form> <!-- Update Schedule Form -->
  </div>
</div>

<!-- Modal File Manager-->
<div id="fileManagerModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fileManagerModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-white" id="fileManagerModalLabel"><i class="fa fa-fw fa-folder-open"></i> File Manager</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <section class="row">
            <div class="col-md-6">
                <form action="<?php echo $siteHostAdmin; ?>includes/common/ajax-handler.php" class="dropzone needsclick dz-clickable" id="filemanagerdropzoneform">
                    <div class="dz-message needsclick">
                        Drop files here or click to upload.<br/>
                        <span style="font-size:12px;">Accepted file type : <?php echo implode(', ', upload_accepted_filetype() ); ?><br/>
                        Max Filesize: 4MB.</span>
                    </div>
                    <input type='hidden' name='action' value='submit_dropzonejs'>
                    <input type='hidden' name='dirname' value=''>
                </form>
            </div>
            <div class="col-md-6">
            <form id="filemanager-form">
              <div class="form-group">
                <label class="sr-only" for="filename">Filename</label>
                <input type="text" class="form-control" id="filename" aria-describedby="emailHelp" placeholder="Filename" required>
              </div>
              <div class="form-group">
                <label class="sr-only" for="description">File Description</label>
                <textarea class="form-control" id="description" rows="3" placeholder="File Description"></textarea>
              </div>
              <input type='hidden' name='file_id' value="">
              <button type="submit" class="btn btn-primary pm-blue">Save File</button>
            </form>
            </div>
        </section>
      </div>
    </div>
  </div>
</div>
<?php include_once('footer.php'); ?>