<?php include_once('header.php'); ?>
<?php
  $settings     = new Settings;
  $employee     = new Employee;
  $files        = new EmpFiles;
  $memo         = new Memo;

  $user_data    = $employee->get_user( intval( $_GET['uid'] ) );
  $user_leaves  = $employee->get_user_leaves( intval( $_GET['uid'] ) );
  $requested_sched  = $employee->get_user_requested_sched( intval( $_GET['uid'] ) );
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
    foreach ( $requested_sched as $shed ) {
      $userSchedule     = $settings->get_settings_data( $shed->schedule, true );
      $timeSchedule     = 'Open';
      $scheduleName     = '';
      if( !empty( $userSchedule ) ){
        $userSchedule  = unserialize( $userSchedule );
        $scheduleName  = $userSchedule['name'];
        $timeSchedule  = $userSchedule['ftimein'].' to '.$userSchedule['stimeout'];
      }
      if( $shed->schedule == 0 ){
        $scheduleName = 'Open Schedule';
      }
      $status = $shed->status ? 'Approved' : 'Pending Approval';
      $calendar_data[] = array(
        'id'        => $shed->id,
        'uid'       => $shed->uid,
        'start'     => $shed->date,
        'type'      => $shed->schedule,
        'title'     => $scheduleName,
        'status'    => $status,
        'dataType' => 'schedule',
        'color'     => '#c0eb3c',
        'timeSchedule'  => $timeSchedule
      );
    }
  }
?>
<div class="col-12">
  <?php include_once(ABSPATH.'/templates/update-user/header2.php'); ?>
</div>
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Update Employee</li>
  </ol>
    <!-- User Loans and Calendar -->
    <div class="col-12">
      <div class="breadcrumb bg-info text-white">
        Files and Memo
      </div>
      <div class="row">
        <div id="loan-deduction-wrapper" class="col-md-6 wptf-section">
          <div class="card mb-3 d-none">
            <div class="card-header">
              Loans Deductions ( SSS / HMDF )
            </div>
            <div class="card-body">
              <div class="row">
                <form id="sssLoan-form" class="form-inline container loan-amortization" data-id="<?php echo $user_data->id; ?>">
                  <div class="form-group row">
                      <label for="sssLoan" class="col-sm-4 col-form-label" >SSS Loan<br/>Amortization</label>
                      <div class="col-sm-6">
                      <input type="text" class="form-control" id="sssLoan" name="sssLoan" placeholder="0.00" value="<?php echo $user_data->loan_sss; ?>">
                      </div>
                      <div class="col-sm-2">
                      <button id="submitSSSLoan" type="submit" class="btn btn-primary">Save</button>
                      </div>
                  </div>
                </form>
                <form id="hmdfLoan-form" class="form-inline container loan-amortization" data-id="<?php echo $user_data->id; ?>">
                  <div class="form-group row">
                      <label for="hmdfLoan" class="col-sm-4 col-form-label" >HMDF Loan<br/>Amortization</label>
                      <div class="col-sm-6">
                      <input type="text" class="form-control" id="hmdfLoan" name="hmdfLoan" placeholder="0.00" value="<?php echo $user_data->loan_hdmf; ?>">
                      </div>
                      <div class="col-sm-2">
                      <button id="submitHMDFLoan" type="submit" class="btn btn-primary">Save</button>
                      </div>
                  </div>
                </form>
                <form id="policyLoan-form" class="form-inline container loan-amortization" data-id="<?php echo $user_data->id; ?>">
                  <div class="form-group row">
                      <label for="policyLoan" class="col-sm-4 col-form-label" >GSIS<br/>Policy</br>Loan</label>
                      <div class="col-sm-6">
                      <input type="text" class="form-control" id="policyLoan" name="loan_policy" placeholder="0.00" value="<?php echo $user_data->loan_policy; ?>">
                      </div>
                      <div class="col-sm-2">
                      <button id="submitPolicyLoan" type="submit" class="btn btn-primary">Save</button>
                      </div>
                  </div>
                </form>
                <form id="gsisLoan-form" class="form-inline container loan-amortization" data-id="<?php echo $user_data->id; ?>">
                  <div class="form-group row">
                      <label for="gsisLoan" class="col-sm-4 col-form-label" >GSIS<br/>Emergency</br>Loan</label>
                      <div class="col-sm-6">
                      <input type="text" class="form-control" id="gsisLoan" name="loan_gsis" placeholder="0.00" value="<?php echo $user_data->loan_gsis; ?>">
                      </div>
                      <div class="col-sm-2">
                      <button id="submitGSISLoan" type="submit" class="btn btn-primary">Save</button>
                      </div>
                  </div>
                </form>
                <form id="elasosLoan-form" class="form-inline container loan-amortization" data-id="<?php echo $user_data->id; ?>">
                  <div class="form-group row">
                      <label for="elasosLoan" class="col-sm-4 col-form-label" >GSIS<br/>Consolidation</br>Loan</label>
                      <div class="col-sm-6">
                      <input type="text" class="form-control" id="elasosLoan" name="ela_sos" placeholder="0.00" value="<?php echo $user_data->ela_sos; ?>">
                      </div>
                      <div class="col-sm-2">
                      <button id="submitELASOSLoan" type="submit" class="btn btn-primary">Save</button>
                      </div>
                  </div>
                </form>
              </div>
            </div> <!-- Body Card -->
          </div> <!-- Card wrapper -->
          <?php if( ACCOUNT_TYPE < 3 ): ?>
          <div class="card mb-3">
            <div class="card-header">
              Files  <a href="#" data-id="<?php echo $user_data->id; ?>" data-username="<?php echo $user_data->username; ?>" class="file-manager btn btn-info btn-sm float-right" data-toggle="modal" data-target="#fileManagerModal"><i class="fa fa-fw fa-folder-open text-white"></i></a>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="table-responsive">
                  <table id="user-file-list" class="table">  
                    <thead>
                      <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Date Upload</th>
                        <th scope="col">Type</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if( !empty( $user_files ) ): ?>
                          <?php foreach( $user_files as $file ): ?>
                            <tr id="file-<?php echo $file->id; ?>">
                              <td ><?php echo $file->name; ?> <i class="fa fa-info-circle text-info ml-3" data-toggle="tooltip" data-placement="top" title="<?php echo $file->description; ?>"></i></td>
                              <td><?php echo $file->upload_date; ?></td>
                              <td><?php echo $file->type; ?></td>
                              <td>
                                <i class="download-file fa fa-download mr-3 text-primary" data-id="<?php echo $file->id; ?>" aria-hidden="true"></i>
                                <i class="delete-file fa fa-trash mr-3 text-danger" data-id="<?php echo $file->id; ?>" aria-hidden="true"></i>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                      <?php else: ?>
                        <tr><td colspan="4">No files found.</td></tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div> <!-- Body Card -->
          </div> <!-- Card wrapper # FILE -->
          <div class="card mb-3">
            <div class="card-header">
              Memo  <a href="<?php echo $siteHostAdmin.'documents.php'; ?>"  class="btn btn-info btn-sm float-right"><i class="fa fa-fw fa-file-text text-white"></i></a>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="table-responsive">
                  <table id="user-memo-list" class="table">  
                    <thead>
                      <tr>
                        <th scope="col">Subject</th>
                        <th scope="col">Date Created</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if( !empty( $user_memos ) ): ?>
                          <?php foreach( $user_memos as $_memo ): ?>
                            <tr id="memo-<?php echo $_memo->id; ?>">
                              <td><?php echo $_memo->subject; ?></td>
                              <td ><?php echo $_memo->created_date; ?></td>
                              <td>
                                <i class="download-memo fa fa-download mr-3 text-primary" data-id="<?php echo $_memo->id; ?>" aria-hidden="true"></i>
                                <i class="delete-memo fa fa-trash mr-3 text-danger" data-id="<?php echo $_memo->id; ?>" aria-hidden="true"></i>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                      <?php else: ?>
                        <tr><td colspan="4">No files found.</td></tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div> <!-- Body Card -->
          </div> <!-- Card wrapper MEMO -->
          <?php endif; ?>
        </div> <!-- loan-deduction-wrapper -->
        <div id="leave-calendar-wrapper" class="col-md-6 wptf-section d-none">
          <div class="card mb-3">
            <div class="card-header">
              User Leave Calendar / Requested Schedule
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
          <label for="userleave-date">Leave Date : <span id="selected-date"></span></label>
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
                    <button type="submit" class="btn btn-primary">Save Leave</button>
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
                    <button type="submit" class="btn btn-primary">Save Schedule</button>
                  </div>
                </div><!--  card  --> 
              </div>     
            </div>  
          </form>  <!-- addUserSchedule-form  --> 
            
        </div>
        <div class="modal-footer">   
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
                  <label for="userleave-date">Leave Date : <span id="selected-date"></span></label>
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
                <div class="col-md-12 mb-3">
                          <label class="sr-only" for="am_pm">AM/PM</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <div class="input-group-text">AM/PM</div>
                            </div>
                            <select id="am_pm" class="form-control" name="am_pm">
                              <option value="">--Select--</option>
                              <?php
                              foreach ( leave_am_pm() as $key => $value) {
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
          <button type="submit" class="btn btn-primary">Update / Approve Leave</button>
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
                  <label for="userleave-date">Schedule Date : <span id="selected-date"></span></label>
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
          <button type="submit" class="btn btn-primary">Update Schedule</button>
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
              <button type="submit" class="btn btn-primary">Save File</button>
            </form>
            </div>
        </section>
      </div>
    </div>
  </div>
</div>


<?php include_once('footer.php'); ?>