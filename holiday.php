<?php include_once('header.php'); ?>
<?php
  $holiday          =  new Holiday;
  $employee         =  new Employee;
  $work_holiday     = $holiday->get_holidays();
  $all_leaves       = $employee->get_all_leaves();
  $declared_holiday = array();

  if( !empty( $work_holiday ) ){
    foreach ( $work_holiday as $_date ) {
      $declared_holiday[] = array(
        'id'       => $_date->id,
        'type'     => $_date->type,
        'date'     => $_date->date,
        'title'    => work_holidays()[$_date->type],
        'backgroundColor' => '#343a40',
        'textColor' => '#FFF',
        'borderColor' => '#343a40',
      );
    }
  }
  if( !empty( $all_leaves ) ){
    foreach ( $all_leaves as $leave ) {
      $fname 		= $employee->get_user_data_by_id( $leave->uid, 'fname' );
      $lname 		= $employee->get_user_data_by_id( $leave->uid, 'lname' );
      $declared_holiday[] = array(
        'id'       => 'l-'.$leave->id,
        'type'     => 'leave',
        'date'     => $leave->date,
        'title'    => $lname.', '.$fname.' on '.work_status()[$leave->type],
        'backgroundColor' => '#17a2b8',
        'textColor' => '#FFF',
        'borderColor' => '#17a2b8',
      );
    }
  }
?>
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Holiday</li>
  </ol>
  <!-- Icon Cards-->
  <div id="holiday-wrapper" class="row">
    <div class="col-12 mb-3">
        <div class="card mb-3">
          <div class="card-header">
            Holiday Calendar
          </div> <!-- card Header -->
          <div class="card-body">
            <div id="calendar-holiday"></div>
          </div> <!--  card-body -->
        </div> <!-- Card wrapper -->
      </div> <!-- Content Wrapper -->
    </div> <!-- Column Wrapper -->
  </div> <!-- holiday-wrapper -->
</div><!-- /.container-fluid-->
<!-- Modal Pop up -->
<div class="modal fade" id="leaveInfoModal" tabindex="-1" role="dialog" aria-labelledby="leaveInfo" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="leaveInfo-form">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white" id="leaveInfo">Leave Information</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                <p class="leave-information"></p>
            </div>      
        </div>
      </div>
    </form> <!-- Schedule Form -->
  </div>
</div>
<div class="modal fade" id="addHolidayModal" tabindex="-1" role="dialog" aria-labelledby="addHoliday" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="addHoliday-form">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white" id="addHoliday">Add Holiday</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                <div id="date-label" class="form-group col-md-12">
                  <label for="userleave-date">Date : <span id="selected-date"></span></label>
                </div>
                <div class="col-md-12 mb-3">
                  <label class="sr-only" for="holiday_type">Holiday</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">Holiday</div>
                    </div>
                    <select id="holiday_type" class="form-control" name="holiday_type" required="required">
                      <option value="">--Select Holiday--</option>
                      <?php
                      foreach( work_holidays() as $key =>$value ){
                        ?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
                      }
                      ?>
                    </select>   
                  </div>
                </div>
            </div>      
        </div>
        <div class="modal-footer pm-blue">
          <input type="hidden" id="holidayDate" name="holidayDate" value="">
          <button type="button" class="btn btn-secondary btn-lg _secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btn-lg">Save Holiday</button>
        </div>
      </div>
    </form> <!-- Schedule Form -->
  </div>
</div>
<div class="modal fade" id="updateHolidayModal" tabindex="-1" role="dialog" aria-labelledby="updateHoliday" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="updateHoliday-form">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white" id="updateHoliday">Update Holiday</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                <div id="date-label" class="form-group col-md-12">
                  <label for="userleave-date">Date : <span id="selected-date"></span></label>
                </div>
                <div class="col-md-12 mb-3">
                  <label class="sr-only" for="holiday_type">Holiday</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">Holiday</div>
                    </div>
                    <select id="holiday_type" class="form-control" name="holiday_type" required="required">
                      <option value="">--Select Holiday--</option>
                      <?php
                      foreach( work_holidays() as $key =>$value ){
                        ?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
                      }
                      ?>
                    </select>   
                  </div>
                </div>
            </div>      
        </div>
        <div class="modal-footer">
          <input type="hidden" id="holidayID" name="holidayID" value="">
          <button type="button" class="btn btn-danger" data-id="" id="deleteHoliday">Delete Holiday</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update Holiday</button>
        </div>
      </div>
    </form> <!-- Schedule Form -->
  </div>
</div>
<?php include_once('footer.php'); ?>