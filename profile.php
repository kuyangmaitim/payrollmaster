<?php include_once('header.php'); ?>
<?php
  $settings     = new Settings;
  $employee     = new Employee;
  $files        = new EmpFiles;
  $memo         = new Memo;
  $leave_info   = new LeaveInformation;
  
  $userID       = $_SESSION["paycheckUserID"];
  $user_data    = $employee->get_user( intval( $userID  ) );
  $user_leaves  = $employee->get_user_leaves( intval( $userID  ) );
  $requested_sched  = $employee->get_user_requested_sched( intval( $userID  ) );
  $current_date     = pcm_current_date();
  $date_from        = pcm_date_first_day( $current_date );
  $date_to          = pcm_date_last_day( $current_date );
  $user_memos       = $memo->get_user_memo( $user_data->id );
  $day_off          = unserialize( $current_user_info->day_off );
  $record           = new Records( $user_data->idd, $date_from, $date_to );
  $user_files       = $files->user_files( $userID );
  $logs             = $record->all_datelogs();

  $can_update = false;
  if( in_array( 'update-user.php', $user_access ) ){
    $can_update = true;
  }

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
      $schedColor       = '';
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
        'title'     => $scheduleName,
        'status'    => $status,
        'dataType' => 'schedule',
        'color'     => $schedColor,
        'timeSchedule'  => $timeSchedule
      );
    }
  }
  $available_sil = $leave_info->get_user_sil( $userID ) ?: 0;
  $used_sil      = $leave_info->get_user_payable_leaves( $user_leaves ) ?: 0;
  $remaining_sil = $available_sil > $used_sil ? number_format( ( $available_sil - $used_sil ), 1 ) : 0;
?>
<style>
  table tbody tr.dt-row {
    display: table-row;
  }
</style>
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">My Profile</li>
  </ol>
  <!-- Icon Cards-->
  <div id="adduser-form" class="row">
    <div class="container">
      <div class="row my-2">
          <div class="col-lg-8 order-lg-2">
              <ul class="nav nav-tabs">
                  <li class="nav-item">
                      <a href="" data-target="#profile" data-toggle="tab" class="nav-link active">Profile</a>
                  </li>
                  <?php if( ACCOUNT_TYPE < 3 ): ?>
                    <?php if ( $user_data->access_level != 4 ): ?>
                    <li class="nav-item">
                      <a id="user-documents" href="#" data-target="#u_documents" data-toggle="tab"  class="nav-link"><i class="fa fa-fw fa-folder-open"></i> Files</a>
                    </li>
                      <!-- <li class="nav-item">
                        <a id="user-memo" href="#" data-target="#u_memos" data-toggle="tab" class="nav-link"><i class="fa fa-fw fa-file-text"></i> Documents</a>
                      </li> -->
                    <?php endif; ?>
                 <?php endif; ?>
                  <!-- <li class="nav-item">
                      <a id="download-resume" data-id="<?php // echo $user_data->id; ?>" href="#"  class="nav-link"><i class="fa fa-fw fa-download"></i> Resume</a>
                  </li> -->
                  <?php if( $can_update ): ?>
                  <li class="nav-item">
                      <a href="update-user.php?uid=<?php echo $user_data->id; ?>" class="nav-link">Edit</a>
                  </li>
                  <?php endif; ?>
              </ul>
              <div class="tab-content py-4">
                <div class="tab-pane active" id="profile">
                    <h5 class="mb-3">User Profile</h5>
                    <div class="row">
                        <div class="col-md-12">
                            <h6>About</h6>
                            <p class="mb-0">
                                <strong>Full name:</strong> <?php echo $user_data->fname.' '.$user_data->lname; ?>
                            </p>
                            <p class="mb-0">
                                <strong>Username:</strong> <?php echo $user_data->username; ?>
                            </p>
                            <p class="mb-0">
                                <strong>Contact number 1:</strong> <?php echo $user_data->phone; ?>
                            </p>
                            <p class="mb-0">
                                <strong>Contact number 2:</strong> <?php echo $user_data->phone2; ?>
                            </p>
                            <p class="mb-0">
                                <strong>Email:</strong> <?php echo $user_data->email; ?>
                            </p>
                            <p class="mb-0">
                                <strong>Address:</strong> <?php echo $user_data->address; ?>
                            </p>
                            <h6>In case of Emergency</h6>
                            <p class="mb-0">
                                <strong>Contact person's name:</strong> <?php echo $user_data->contact_name; ?>
                            </p>
                            <p class="mb-0">
                                <strong>Contact number:</strong> <?php echo $user_data->contact_phone; ?>
                            </p>
                            <p class="mb-0">
                                <strong>Email:</strong> <?php echo $user_data->contact_email; ?>
                            </p>
                            <p class="mb-0">
                                <strong>Address:</strong> <?php echo $user_data->contact_address; ?>
                            </p>
                            <h6>Others</h6>
                            <p class="mb-0">
                                <strong>Biometric ID:</strong> <?php echo $user_data->idd; ?>
                            </p>
                            <p class="mb-0">
                                <strong>Position:</strong> <?php echo pcm_user_access_level()[$user_data->access_level]; ?>
                            </p>
                            <p>
                                <strong>Employee Status:</strong> <?php echo $user_data->work_status ? 'Active' : 'Inactive' ; ?>
                            </p>
                        </div>
                        <div class="col-md-12">
                            <h5 class="my-2"><span class="fa fa-clock-o ion-clock"></span> Time Record <?php if( $can_update): ?><button type="button" id="openAddlogModal" data-id="<?php echo $user_data->idd; ?>" class="btn btn-outline-success btn-sm float-right _success" data-toggle="modal" data-target="#addLogModal">Add New Log</button><?php endif; ?></h5>
                            <p>From: <?php echo $date_from; ?> To: <?php echo $date_to; ?></p>
                            <table class="table table-sm table-hover table-striped my-4">
                              <thead>
                                <tr>
                                  <th>Date</th>
                                  <th>Schedule</th>
                                  <th>Log In(AM)</th>
                                  <th>Log Out(AM)</th>
                                  <th>Log In(PM)</th>
                                  <th>Log Out(PM)</th>
                                  <th>OT In</th>
                                  <th>OT Out</th>
                                </tr>
                              </thead>
                              <tfoot>
                                <tr>
                                  <th>Date</th>
                                  <th>Schedule</th>
                                  <th>Log In(AM)</th>
                                  <th>Log Out(AM)</th>
                                  <th>Log In(PM)</th>
                                  <th>Log Out(PM)</th>
                                  <th>OT In</th>
                                  <th>OT Out</th>
                                </tr>
                              </tfoot>
                              <tbody>
                                <?php
                                if( !empty( $logs ) ){
                                  $total_late = 0;
                                  $total_time = 0;
                                  $total_ot   = 0;
                                  foreach ($logs as $date_log => $log_value ) {
                                    $log_data       = $record->get_work_hours( $log_value, $date_log );
                                    $ot_data        = $record->get_ot_hours( $log_value, $date_log );
                                    $schedule_date  = $record->get_user_schedule_date( $user_data->idd, $date_log );
                                    $log_late       = unix_to_hour( $log_data['late'] );
                                    $log_time_spent = unix_to_hour( $log_data['time_consume'] );
                                    $ot_time_spent  = unix_to_hour( $ot_data );
                                    $total_late     = $total_late + $log_data['late'];
                                    $total_time     = $total_time + $log_data['time_consume'];
                                    $total_ot       = $total_ot + $ot_data;
                                    $schedule_name  = "Open Schedule";
                                    $mark_ftimeIn    = '';
                                    $mark_ftimeOut   = '';
                                    $mark_stimeIn    = '';
                                    $mark_stimeOut   = '';
                                    if( !empty( $schedule_date ) ){
                                      $schedule_name = $schedule_date['name'];
                                      $sched_ftimeIn    = pcm_time_to_unix( $date_log.' '.$schedule_date['ftimein'] );
                                      $sched_ftimeOut   = pcm_time_to_unix( $date_log.' '.$schedule_date['ftimeout'] );
                                      $sched_stimeIn    = pcm_time_to_unix( $date_log.' '.$schedule_date['stimein'] );
                                      $sched_stimeOut   = pcm_time_to_unix( $date_log.' '.$schedule_date['stimeout'] );
                                      // mark time log if late
                                      if( !empty( $log_value['ftimeIn']['time'] ) ):
                                        $ftimeIn = $log_value['ftimeIn']['time'];
                                        if( $sched_ftimeIn < $ftimeIn ){
                                        $mark_ftimeIn = 'warning';
                                        }
                                      endif;
                                      if( !empty( $log_value['ftimeOut']['time'] ) ):
                                        $ftimeOut = $log_value['ftimeOut']['time'];
                                        if( $sched_ftimeOut > $ftimeOut ){
                                        $mark_ftimeOut = 'warning';
                                        }
                                      endif;
                                      if( !empty( $log_value['stimeIn']['time'] ) ):
                                        $stimeIn = $log_value['stimeIn']['time'];
                                        if( $sched_stimeIn < $stimeIn ){
                                        $mark_stimeIn = 'warning';
                                        }
                                      endif;
                                      if( !empty( $log_value['stimeOut']['time'] ) ):
                                        $stimeOut = $log_value['stimeOut']['time'];
                                        if( $sched_stimeOut > $stimeOut ){
                                        $mark_stimeOut = 'warning';
                                        }
                                      endif;
                                    }
                                    ?>
                                    <tr>
                                      <td class="logDate"><?php echo $date_log; ?></td>
                                      <td><?php echo $schedule_name; ?></td>
                                      <td id="<?php echo $log_value['ftimeIn']['id']; ?>" class="<?php echo $mark_ftimeIn; ?>">
                                        <?php if( !empty( $log_value['ftimeIn']['time'] ) ): ?>
                                          <?php if( $can_update): ?> 
                                          <a href="#" class="update" data-toggle="modal" data-target="#updateLogModal" data-sched="ftimeIn" data-id="<?php echo $log_value['ftimeIn']['id']; ?>"><i class="fa fa-fw fa-edit" ></i></a>
                                        <?php endif; ?>
                                        <?php echo pcm_unix_to_time( $log_value['ftimeIn']['time'] ); ?>
                                        <?php endif; ?>
                                      </td>
                                      <td id="<?php echo $log_value['ftimeOut']['id']; ?>" class="<?php echo $mark_ftimeOut; ?>">
                                        <?php if( !empty( $log_value['ftimeOut']['time'] ) ): ?>
                                          <?php if( $can_update): ?> 
                                          <a href="#" class="update" data-toggle="modal" data-target="#updateLogModal" data-sched="ftimeOut" data-id="<?php echo $log_value['ftimeOut']['id']; ?>"><i class="fa fa-fw fa-edit" ></i></a>
                                          <?php endif; ?>
                                          <?php echo pcm_unix_to_time( $log_value['ftimeOut']['time'] ); ?>
                                        <?php endif; ?>
                                      </td>
                                      <td id="<?php echo $log_value['stimeIn']['id']; ?>" class="<?php echo $mark_stimeIn; ?>">
                                        <?php if( !empty( $log_value['stimeIn']['time'] ) ): ?>
                                          <?php if( $can_update): ?> 
                                            <a href="#" class="update" data-toggle="modal" data-target="#updateLogModal" data-sched="stimeIn" data-id="<?php echo $log_value['stimeIn']['id']; ?>"><i class="fa fa-fw fa-edit" ></i></a>
                                          <?php endif; ?>
                                        <?php echo pcm_unix_to_time( $log_value['stimeIn']['time'] ); ?>
                                        <?php endif; ?>
                                      </td>
                                      <td id="<?php echo $log_value['stimeOut']['id']; ?>" class="<?php echo $mark_stimeOut; ?>">
                                        <?php if( !empty( $log_value['stimeOut']['time'] ) ): ?>
                                          <?php if( $can_update): ?> 
                                            <a href="#" class="update" data-toggle="modal" data-target="#updateLogModal" data-sched="stimeOut" data-id="<?php echo $log_value['stimeOut']['id']; ?>"><i class="fa fa-fw fa-edit" ></i></a>
                                          <?php endif; ?>
                                          <?php echo pcm_unix_to_time( $log_value['stimeOut']['time'] ); ?>
                                        <?php endif; ?>
                                      </td>
                                      <td id="<?php echo $log_value['OTin']['id']; ?>">
                                        <?php if( !empty( $log_value['OTin']['time'] ) ): ?>
                                          <?php if( $can_update): ?> 
                                            <a href="#" class="update" data-toggle="modal" data-target="#updateLogModal" data-sched="OTin" data-id="<?php echo $log_value['OTin']['id']; ?>"><i class="fa fa-fw fa-edit" ></i></a>
                                          <?php endif; ?>
                                          <?php echo pcm_unix_to_time( $log_value['OTin']['time'] ); ?>
                                        <?php endif; ?>
                                      </td>
                                      <td id="<?php echo $log_value['OTout']['id']; ?>">
                                        <?php if( !empty( $log_value['OTout']['time'] ) ): ?>
                                          <?php if( $can_update): ?> 
                                            <a href="#" class="update" data-toggle="modal" data-target="#updateLogModal" data-sched="OTout" data-id="<?php echo $log_value['OTout']['id']; ?>"><i class="fa fa-fw fa-edit" ></i></a>
                                            <?php endif; ?>
                                          <?php echo pcm_unix_to_time( $log_value['OTout']['time'] ); ?>
                                        <?php endif; ?>
                                      </td>
                                    </tr>
                                    <?php
                                  }
                                }else{
                                  ?><tr><td colspan="8">No record Found</td></tr><?php
                                }
                                ?>                                    
                              </tbody>
                            </table>
                        </div>
                    </div><!--/row-->
                </div><!-- profile TAB -->
                <?php if( ACCOUNT_TYPE < 3 ): ?>
                  <div class="tab-pane" id="u_documents">
                    <h5 class="mb-3">Documents</h5>
                    <div class="card mb-3">
                      <div class="card-header">
                        Files  <a href="#" data-id="<?php echo $user_data->id; ?>" data-username="<?php echo $user_data->username; ?>" class="file-manager btn btn-info pm-blue btn-sm float-right" data-toggle="modal" data-target="#fileManagerModal"><i class="fa fa-fw fa-folder-open text-white"></i></a>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <input type="search" name="search_files" class="p-2 mb-2 border rounded" style="outline: none;" placeholder="Search..." />
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
                                      <?php $exploded_date = explode( ' ', $file->upload_date ); ?>
                                      <tr id="file-<?php echo $file->id; ?>">
                                        <td ><?php echo $file->name; ?></td>
                                        <!-- <td ><?php // echo $file->name; ?> <i class="fa fa-info-circle text-info ml-3" data-toggle="tooltip" data-placement="top" title="<?php // echo $file->description; ?>"></i></td> -->
                                        <td><?php echo $exploded_date[0]; ?></td>
                                        <td><?php echo $file->type; ?></td>
                                        <td>
                                          <i class="download-file fa fa-download mr-3 text-primary" data-id="<?php echo $file->id; ?>" aria-hidden="true"></i>
                                          <?php if( $file->uploaded_by == pa_get_current_user() ): ?>
                                          <i class="delete-file fa fa-trash mr-3 text-danger" data-id="<?php echo $file->id; ?>" aria-hidden="true"></i>
                                          <?php endif; ?>
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
                    </div> <!-- Card wrapper -->
                  </div><!-- Document TAB -->
                  <div class="tab-pane" id="u_memos">
                    <div class="card mb-3">
                      <div class="card-header">
                      <h5 class="mb-3">Documents</h5>
                      </div>
                      <div class="card-body">
                        <div class="row">
                          <div class="table-responsive">
                            <table id="user-memo-list" class="table">  
                              <thead>
                                <tr>
                                  <th scope="col">Subject</th>
                                  <th scope="col">Date Created</th>
                                  <th scope="col">Status</th>
                                  <th scope="col">Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php if( !empty( $user_memos ) ): ?>
                                    <?php foreach( $user_memos as $_memo ): ?>
                                      <tr id="memo-<?php echo $_memo->id; ?>">
                                        <td><?php echo $_memo->subject; ?></td>
                                        <td ><?php echo $_memo->created_date; ?></td>
                                        <td >
                                          <?php
                                            if( $_memo->signature ){
                                              ?><span class="text-success">Signed</span><?php
                                            }else{
                                              ?><span class="text-danger">Unsigned</span><?php
                                            }
                                          ?>
                                        </td>
                                        <td>
                                          <i class="download-memo fa fa-download mr-3 text-primary" data-id="<?php echo $_memo->id; ?>" aria-hidden="true"></i>
                                          <a href="<?php echo $siteHostAdmin.'signature.php?sign=1&id='.$_memo->id; ?>"><i class="sign-memo fa fa-pencil-square-o text-primary" data-id="<?php echo $_memo->id; ?>" aria-hidden="true"></i></a>
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
                  </div><!-- MEMO TAB -->
                <?php endif; ?>
              </div>
          </div>
          <div class="col-lg-4 order-lg-1 text-center">
              <img id="avatar" src="<?php echo pcm_get_avatar( $userID ); ?>" class="mx-auto img-fluid img-circle d-block" alt="avatar">
              <h6 id="upload-avatar" class="mt-2" data-toggle="modal" data-target="#uploadAvatar" data-id="<?php echo $userID; ?>"><span class="custom-file-control btn btn-outline-secondary btn-sm">Upload a different photo</span></h6>
          </div>
      </div>
    </div>
    <!-- User Loans and Calendar -->
    <div class="col-12">
      <div class="breadcrumb">
        Loan and Leave Information
      </div>
      <div class="row">
        <div id="leave-calendar-wrapper" class="col-md-6 wptf-section">
          <div class="card mb-3">
            <div class="card-header">
              User Leave Calendar / Requested Schedule
            </div>
            <div class="card-body">
              <div class="alert alert-success" role="alert">
                Click a specific date on the calendar to apply for Leave or Overtime.
              </div>
              <div id="employee-calendar"></div>             
            </div> <!-- Body Card -->
          </div> <!-- Card wrapper -->
        </div> <!-- leave-calendar-wrapper -->
        <div id="password-info" class="col-md-6 wptf-section">
          <div class="card mb-3">
            <div class="card-header">
              Login Information
            </div>
            <div class="card-body">
              <div class="col-md-12">
                <label class="sr-only" for="username">Username</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fa fa-fw fa-user"></i></div>
                  </div>
                  <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?php echo $user_data->username; ?>" required="required" disabled>
                </div>
              </div>
              <div class="col-md-12">
                <label class="sr-only" for="password">Password</label>
                <div class="input-group mb-2">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fa fa-fw fa-key"></i></div>
                  </div>
                  <input type="password" class="form-control" id="password" name="password" placeholder="*************">
                </div>
              </div>
              <div class="col-md-12">
                <div class="input-group mb-2">
                  <button id="change-password" class="btn btn-lg btn-primary text-white pm-blue">Change Password</button>
                </div>
              </div>
            </div>
          </div>
        </div> <!-- #leave-info -->
        <div id="leave-info" class="col-md-6 wptf-section">
          <div class="card mb-3">
            <div class="card-header">
              Leave Information
            </div>
            <div class="card-body">
              <div class="form-group row">
                <div class="col-md-4">
                  <label class="form-label" for="availSil">Total SIL</label>
                </div>
                <div class="col-md-6">
                  <input type="text" class="form-control" id="availSil" name="availSil" placeholder="0" value="<?php echo $available_sil; ?>" readonly>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-4">
                  <label class="form-label" for="usedSil">Total Availed SIL</label>
                </div>
                <div class="col-md-6">
                  <input type="text" class="form-control" id="usedSil" name="usedSil" placeholder="0" value="<?php echo ( $used_sil >= $available_sil ? $available_sil : $used_sil );; ?>" readonly>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-4">
                  <label class="form-label" for="remSil">Available SIL</label>
                </div>
                <div class="col-md-6">
                  <input type="text" class="form-control" id="remSil" name="remSil" placeholder="0" value="<?php echo $remaining_sil; ?>" readonly>
                </div>
              </div>
            </div>
          </div>
        </div> <!-- #leave-info -->
        <div id="leave-history-wrapper" class="col-md-6 wptf-section">
          <div class="card mb-3">
            <div class="card-header">
              Leave History
            </div>
            <div class="card-body">
              <table id="leave-histoy-table" class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Whole/Half Day</th>
                  </tr>
                </thead>
                <tbody>
                    <?php if( !empty( $user_leaves ) ): ?>
                      <?php foreach( $user_leaves as $leave ): ?>
                        <tr>
                          <td><?php echo $leave->date; ?></td>
                          <td><?php echo ( $leave->status == 1 ? 'Approved' : 'Not Approved' ); ?></td>
                          <td><?php echo ( !empty( $leave->am_pm )  ? 'Half Day' : 'Whole Day' ); ?></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="3">No leaves yet.</td>
                      </tr>
                    <?php endif; ?>
                </tbody>
              </table>
            </div> <!-- Body Card -->
          </div> <!-- Card wrapper -->
        </div> <!-- leave-history-wrapper -->
      </div>
    </div>
    <!-- END User Loans and Calendar -->
  </div> <!-- adduser-form -->
</div><!-- /.container-fluid-->
<!-- Modal Pop up -->
<div class="modal fade" id="userApplicationModal" tabindex="-1" role="dialog" aria-labelledby="userApplication" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white text-white" id="userApplication">Apply Leave / Ovetime</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label for="userleave-date">Application Date : <span id="selected-date"></span></label>
          <div class="tabs">
            <ul>
              <li><a href="#tabs-1">Leave Application</a></li>
              <li><a href="#tabs-2">Overtime Application</a></li>
            </ul>
            <div id="tabs-1">
              <form id="applyUserLeave-form">
                <div class="form-row">
                  <div class="col-md-12 mb-6">
                    <div class="card mb-3">
                      <div class="card-body">
                        <div class="col-md-12 mb-3">
                          <label class="sr-only" for="user_leave">Apply Leave</label>
                          <div class="input-group">
                            <div class="input-group-prepend">
                              <div class="input-group-text">Apply Leave</div>
                            </div>
                            <select id="user_leave" class="form-control leave-type-select" name="user_leave">
                              <option value="">--Select Leave--</option>
                              <?php
                              foreach ( work_status() as $_lkey => $_lvalue) {
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
                            <select id="am_pm" class="form-control leave-ampm-select" name="am_pm">
                              <option value="">Whole Day</option>
                              <?php
                              foreach ( leave_am_pm() as $key => $value) {
                                ?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
                              }
                              ?>
                            </select>   
                          </div>
                        </div>
                        <input type="hidden" id="leaveDate" name="leaveDate" value="">
                        <button type="submit" class="btn btn-primary text-white apply-leave-btn pm-blue">Apply Leave</button>
                      </div>
                    </div><!--  card  --> 
                  </div>     
                </div>  
              </form>  <!-- addUserLeave-form  --> 
            </div>
            <div id="tabs-2">
              <form id="applyUserOvertime-form">
                <div class="form-row">
                  <div class="col-md-12 mb-6">
                    <div class="card mb-3">
                      <div class="card-body">
                        <div id="time-range" class="form-row">
                          <div class="form-group col-md-6">
                            <label for="time-start">Time Start</label>
                            <input id="time-start" type="text" class="form-control time start" id="time-start" required="required">
                          </div>
                          <div class="form-group col-md-6">
                            <label for="time-end">Time End</label>
                            <input id="time-end" type="text" class="form-control time end" id="time-end" required="required" >
                          </div>
                          <div class="form-group col-md-12">
                            <label for="notes">Notes</label>
                            <textarea id="notes" class="form-control" required="required"></textarea>
                          </div>
                        </div>
                        <input type="hidden" id="overtimeDate" name="overtimeDate" value="">
                        <button type="submit" class="btn btn-primary text-white pm-blue">Apply Overtime</button>
                      </div>
                    </div><!--  card  --> 
                  </div>     
                </div>  
              </form>  <!-- addUserSchedule-form  --> 
            </div>
          </div>
        </div>
        <div class="modal-footer pm-blue">   
          <button type="button" class="btn btn-secondary pm-blue" data-dismiss="modal">Close</button>          
        </div>
      </div>
    </form> <!-- Schedule Form -->
  </div>
</div>
<!-- Update Leave -->
<div class="modal fade" id="viewLeaveModal" tabindex="-1" role="dialog" aria-labelledby="viewLeave" aria-hidden="true">
  <div id="view-user-leave" class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white text-white" id="viewLeave">View Leave Information</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                <div id="date-label" class="form-group col-md-12">
                  <label for="userleave-date">Leave Date : <span id="selected-date"></span></label>
                </div>
                <div class="col-md-12 mb-3">
                  <label class="sr-only" for="user_leave">Leave</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">Leave</div>
                    </div>
                    <select id="user_leave" class="form-control" disabled>
                      <option value="">--Select Leave--</option>
                      <?php
                        foreach ( work_status() as $_lkey => $_lvalue) {
                          ?><option value="<?php echo $_lkey; ?>"><?php echo $_lvalue; ?></option><?php
                        }
                      ?>
                    </select>   
                  </div>
                </div>
                <div class="col-md-12 mb-3">
                  <label class="sr-only" for="user_leave">Status</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">Status</div>
                    </div>
                    <input class="form-control" type="text" id="status" disabled> 
                  </div>
                </div>
            </div>      
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
  </div>
</div>
<!-- Update Schedule -->
<div class="modal fade" id="viewScheduleModal" tabindex="-1" role="dialog" aria-labelledby="viewSchedule" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div id="view-user-schedule">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white text-white" id="viewSchedule">View Schedule Information</h5>
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
                      <select id="schedule" class="form-control" name="schedule" required="required" disabled>
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
                <div class="col-md-12 mb-3">
                  <label class="sr-only" for="user_leave">Time</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">Time</div>
                    </div>
                    <input class="form-control" type="text" id="time" disabled> 
                  </div>
                </div>
            </div>      
        </div>
        <div class="modal-footer pm-blue">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div> <!-- Update Schedule Form -->
  </div>
</div>
<!-- Modal Time Record-->
<!-- Add new Time Log -->
<div class="modal fade" id="addLogModal" tabindex="-1" role="dialog" aria-labelledby="addLog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="addlog-form">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white text-white" id="addLog">Add New Time Log</h5>
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
<!-- update Time Log -->
<div class="modal fade" id="updateLogModal" tabindex="-1" role="dialog" aria-labelledby="updateLog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="updatelog-form">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white text-white" id="updateLog">Update Log</h5>
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
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <input type="hidden" id="logID" name="logID" value="">
          <input type="hidden" id="checkinSched" name="checkinSched" value="">
          <input type="submit" class="btn btn-danger" value="Confirm">
        </div>
      </div>
    </form>
  </div>
</div>
<!-- Upload Avatar -->
<div class="modal fade" id="uploadAvatar" tabindex="-1" role="dialog" aria-labelledby="upload-avatar" aria-hidden="true">
  <div id="view-user-leave" class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white text-white" id="upload-avatar">Upload new Avatar</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-row">
              <div id="change-avatar"></div>
              <div id="croppie-actions">
                  <input type="file" id="upload" class="btn actionUpload btn-primary text-white btn-sm" value="Upload Avatar" accept="image/*" />
                  <a class="button actionSave btn btn-success btn-sm" data-id="<?php echo $userID; ?>">Save Avatar</a>
              </div>
            </div>      
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
  </div>
</div>
<!-- Modal File Manager-->
<div id="fileManagerModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fileManagerModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-white text-white" id="fileManagerModalLabel"><i class="fa fa-fw fa-folder-open"></i> File Manager</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <section class="row">
            <div class="col-md-6">
                <form action="<?php echo $siteHostAdmin; ?>includes/common/ajax-handler.php" class="dropzone needsclick dz-clickable" id="filemanagerdropzoneform">
                    <div class="dz-message needsclick">
                        Drop files here or click to upload.<br/>
                        <span style="font-size:12px;">Accepted file type: <?php echo implode(', ', upload_accepted_filetype() ); ?><br/>
                        Max. Filesize: 4MB</span>
                    </div>
                    <input type='hidden' name='action' value='submit_dropzonejs'>
                    <input type='hidden' name='dirname' value=''>
                </form>
            </div>
            <div class="col-md-6">
            <form id="filemanager-form">
              <div class="form-group">
                <label class="sr-only" for="filename">File name</label>
                <input type="text" class="form-control" id="filename" aria-describedby="emailHelp" placeholder="File name" required>
              </div>
              <div class="form-group">
                <label class="sr-only" for="description">File Description</label>
                <textarea class="form-control" id="description" rows="3" placeholder="File Description"></textarea>
              </div>
              <input type='hidden' name='file_id' value="">
              <button type="submit" class="btn btn-primary pm-blue text-white">Save File</button>
            </form>
            </div>
        </section>
      </div>
    </div>
  </div>
</div>
<?php include_once('footer.php'); ?>