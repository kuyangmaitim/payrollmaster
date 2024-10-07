<?php include_once('header.php'); ?>
<?php
  $settings =  new Settings;
?>
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">All  Schedule</li>
  </ol>
  <!-- Icon Cards-->
  <div id="settings-wrapper" class="row">
    <div class="col-12">
      <div class="row">
        <div class="col-md-8 mb-6 wptf-section">
          
          <div class="card mb-3">
            <div class="card-header">
              Schedules
            </div> <!-- card Header -->
            <div class="card-body">
              <button type="submit" class="btn btn-primary btn-lg mb-3 pm-blue"  data-toggle="modal" data-target="#addScheduleModal">Add Schedule</button>
              <div id="schedule-list" class="table-responsive">
                <table class="table table-bordered" id="scheduleListTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Schedule name</th>
                      <th>Time In</th>
                      <th>Time Out</th>
                      <th>Time In</th>
                      <th>Time Out</th>
                      <th>Break</th>
                      <th>Color</th>
                      <th>Update</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Schedule name</th>
                      <th>Time In</th>
                      <th>Time Out</th>
                      <th>Time In</th>
                      <th>Time Out</th>
                      <th>Break</th>
                      <th>Color</th>
                      <th>Update</th>
                      <th>Delete</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php
                    $schedules = $settings->get_settings_by_name('schedules');
                    if( !empty( $schedules ) ){
                      foreach ($schedules as $schedule ) {
                        $scheduleID   = $schedule->id;
                        $schedule = unserialize( $schedule->value );
                        ?>
                        <tr id="sched-<?php echo $scheduleID; ?>">
                          <td class="name"><?php echo $schedule['name']; ?></td>
                          <td class="ftimein"><?php echo !empty( $schedule['ftimein'] ) ? pcm_covert_time( $schedule['ftimein'], false ) : ''; ?></td>
                          <td class="ftimeout"><?php echo !empty( $schedule['ftimeout'] ) ? pcm_covert_time( $schedule['ftimeout'], false ) : ''; ?></td>
                          <td class="stimein"><?php echo !empty( $schedule['stimein'] ) ? pcm_covert_time( $schedule['stimein'], false ) : ''; ?></td>
                          <td class="stimeout"><?php echo !empty( $schedule['stimeout'] ) ? pcm_covert_time( $schedule['stimeout'], false ) : ''; ?></td>
                          <td class="breakTime"><?php echo $schedule['break']; ?></td>
                          <td class="schedColor text-center"><div style="margin:auto;width:20px;height:20px;border:1px solid #eee;border-radius:50%;background-color:<?php echo $schedule['schedColor'] ?: '#4285F4'; ?>;"></div></td>
                          <td style="text-align:center;">
                            <a class="update" data-id="<?php echo $scheduleID; ?>" data-toggle="modal" data-target="#updatedScheduleModal"><span class="fa fa-fw fa-edit"></span></a>
                          </td>
                          
                          <td style="text-align:center;">
                            <a class="delete" data-id="<?php echo $scheduleID; ?>" data-toggle="modal" data-target="#deleteScheduleModal"><span style="color:red;" class="fa fa-fw fa-trash"></span></a>
                          </td>    
                        </tr>         
                        <?php
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div> <!-- Card Body -->
          </div> <!-- card @ Schedule Form -->
        </div> <!-- wptf-section -->
    <!-- POP UP MOdal for Add schedule -->
<!-- Modal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1" role="dialog" aria-labelledby="addSchedule" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <form id="schedule-form">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white" id="addSchedule">Add Schedule</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12">
                      <label for="schedule_name">Schedule Name</label>
                      <input type="text" class="form-control" id="schedule_name" placeholder="Schedule Name" name="schedule_name" required="required">
                    </div>
                    <div class="form-group col-md-6 d-flex align-items-baseline">
                      <label for="uncut_sched">Uncut Schedule</label>
                      <input type="checkbox" class="ml-2" id="uncut_sched" name="uncut_sched" />
                    </div>
                    <div class="form-group col-md-6"></div>
                    <div class="form-group col-md-6">
                      <label for="ftime_in">First Time In</label>
                      <input type="text" class="form-control timepicker" id="ftime_in" placeholder="0:00 am" name="ftime_in" required="required">
                    </div>
                    <div class="form-group col-md-6">
                      <label for="ftime_out">First Time Out</label>
                      <input type="text" class="form-control timepicker" id="ftime_out" placeholder="0:00 am" name="ftime_out" />
                    </div>
                    <div class="form-group col-md-6">
                      <label for="stime_in">Second Time In</label>
                      <input type="text" class="form-control timepicker" id="stime_in" placeholder="0:00 pm" name="stime_in" />
                    </div>
                    <div class="form-group col-md-6">
                      <label for="stime_out">Second Time Out</label>
                      <input type="text" class="form-control timepicker" id="stime_out" placeholder="0:00 pm" name="stime_out" required="required">
                    </div>
                   <div class="form-group col-md-6">
                      <label for="breakTime">Break Time (minutes)</label>
                      <input type="text" class="form-control" id="breakTime" placeholder="" name="breakTime" >
                    </div>
                <div class="form-group col-md-6">
                  <label for="schedColor">Color</label>
                  <input type="color" class="form-control " id="schedColor" placeholder="" name="schedColor" />
                </div>
                    <div class="form-group col-md-12">
                      <!-- <button type="submit" class="btn btn-primary create-sched-btn">Create Schedule</button> -->
                    </div>
                </div>
        </div>
        <div class="modal-footer pm-blue">
          <button type="button" class="btn btn-secondary btn-lg _secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btn-lg _create-sched-btn">Create Schedule</button>
        </div>
      </div>
      </form> <!-- Schedule Form -->
  </div>
</div>
<!-- POP UP MOdal for Update schedule -->
<!-- Modal -->
<div class="modal fade" id="updatedScheduleModal" tabindex="-1" role="dialog" aria-labelledby="updatedSchedule" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="update-schedule-form">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white" id="updatedSchedule">Update Schedule</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="schedule_name">Schedule Name</label>
                  <input type="text" class="form-control" id="_schedule_name" placeholder="Schedule Name" name="schedule_name" required="required">
                </div>
                <div class="form-group col-md-6 d-flex align-items-baseline">
                  <label for="uncut_sched">Uncut Schedule</label>
                  <input type="checkbox" class="ml-2" id="_uncut_sched" name="uncut_sched" />
                </div>
                <div class="col-md-6"></div>
                <div class="form-group col-md-6">
                  <label for="ftime_in">First Time In</label>
                  <input type="text" class="form-control timepicker" id="_ftime_in" placeholder="0:00 am" name="ftime_in" required="required">
                </div>
                <div class="form-group col-md-6">
                  <label for="ftime_out">First Time Out</label>
                  <input type="text" class="form-control timepicker" id="_ftime_out" placeholder="0:00 am" name="ftime_out" />
                </div>
                <div class="form-group col-md-6">
                  <label for="stime_in">Second Time In</label>
                  <input type="text" class="form-control timepicker" id="_stime_in" placeholder="0:00 pm" name="stime_in" />
                </div>
                <div class="form-group col-md-6">
                  <label for="stime_out">Second Time Out</label>
                  <input type="text" class="form-control timepicker" id="_stime_out" placeholder="0:00 pm" name="stime_out" required="required">
                </div>
                <div class="form-group col-md-6">
                  <label for="breakTime">Break Time (minutes)</label>
                  <input type="text" class="form-control " id="breakTime" placeholder="" name="breakTime" />
                </div>
                <div class="form-group col-md-6">
                  <label for="_schedColor">Color</label>
                  <input type="color" class="form-control " id="_schedColor" placeholder="" name="_schedColor" />
                </div>
            </div>      
        </div>
        <div class="modal-footer">
          <input type="hidden" id="scheduleID" name="scheduleID" value="">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </div>
    </form> <!-- Schedule Form -->
  </div>
</div>
<!-- POP UP MOdal for Delete schedule -->
<!-- Modal -->
<div class="modal fade" id="deleteScheduleModal" tabindex="-1" role="dialog" aria-labelledby="deleteSchedule" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header alert-danger">
        <h5 class="modal-title text-white" id="deleteSchedule">Alert</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this schedule</p>
        <p>This will affect Employee data who is assigned to this schedule.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="deleteScheduleButton" data-id="">Confirm</button>
      </div>
    </div>
  </div>
</div>

</div>
<?php include_once('footer.php'); ?>