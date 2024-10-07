<?php include_once('header.php'); ?>
<?php
  $employee  =  new Employee;
  $date 	 = pcm_current_date();
  $dateFrom  = isset( $_POST['dateFrom'] ) ? $_POST['dateFrom'] : pcm_date_first_day( $date );
  $dateTo 	 = isset( $_POST['dateTo'] ) ? $_POST['dateTo'] : pcm_date_last_day( $date );
  $emp_id    = isset( $_POST['employee'] ) ? (int)$_POST['employee'] : 0 ;
  $monthly_overtime = $employee->get_users_overtime( $dateFrom , $dateTo  );
  $employees 	    = $employee->get_all_employees( array( 'id', 'fname', 'lname', 'idd', 'access_level' ) );
?>
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Overtime</li>
  </ol>
  <!-- Icon Cards-->
  <div id="payroll-timecard-wrapper" class="row">
    <div class="col-12 mb-3">
        <div class="card mb-3">
          <div class="card-header">
            Search Overtime
          </div> <!-- card Header -->
          <div class="card-body">
            <form id="search-vacations-form" method="POST" action="overtime.php">
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
                  <select class="form-control select2" id="employeeList" name="employee">
                    <option value="">All Employee</option>
                    <?php
                    $get_all_employees = $employee->get_all_employees( array( 'fname', 'lname', 'idd', 'access_level' ) );
                    if( !empty( $get_all_employees ) ){
                      foreach ($get_all_employees as $_employee ) {
                        if( $_employee->access_level == 1 ){ continue; }
                        ?><option value="<?php echo $_employee->idd; ?>" <?php echo $_employee->idd == $emp_id ? 'selected' : '' ; ?> ><?php echo $_employee->lname; ?>, <?php echo $_employee->fname; ?></option><?php
                      }
                    }else{
                      ?><option >NO Registered Employee Found</option><?php
                    }
                    ?>
                  </select>
                </div>
                <div class="form-group col-auto mb-3">
                  <button type="submit" name="generate-vacations" class="btn btn-lg btn-primary pm-blue">Submit</button>
                </div>
              </div>
            </form>
          </div> <!--  card-body -->
      </div> <!-- Card wrapper -->
    </div> <!-- Content Wrapper -->
    <div class="col-12 mb-3">
      <div class="card mb-3">
        <div class="card-header">
          <strong>Overtime List</strong> From: <strong><?php echo $dateFrom; ?></strong> To: <strong><?php echo $dateTo; ?></strong>
          <button id="add-log" style="float: right;width: initial;" class="btn btn-sm _success btn-outline-success" data-toggle="modal" data-target="#overtimeApplicationModal">Add Overtime</button>
        </div> <!-- card Header -->
        <div class="card-body">
            <table class="table table-bordered" id="ovetimeList" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Employee Name</th>
                        <th>Time Range</th>
                        <th>Note</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                if( !empty( $monthly_overtime ) ){
                    foreach ($monthly_overtime as $ovetime ) {
                        $otID 	= $ovetime->id;
                        $_ot_userID 	= $ovetime->uid;
                        $_ot_date 		= $ovetime->date;
                        $_ot_status 	= $ovetime->status;
                        $_ot_fname 		= $employee->get_user_data_by_id( $_ot_userID, 'fname' );
                        $_ot_lname 		= $employee->get_user_data_by_id( $_ot_userID, 'lname' );
                        $_ot_fullname 	= $_ot_fname.' '.$_ot_lname;
                        $ot_status 		= 'Not approved';
                        $_ot_buttonLabel 	= 'Approved';
                        if( $_ot_status == 1 ){
                            $ot_status 			= 'Approved';
                            $_ot_buttonLabel 	= 'Not approved';
                        }
                        ?>
                    <tr id="ot-<?php echo $otID; ?>">
                        <td><?php echo $ovetime->date; ?></td>
                        <td><?php echo $_ot_fullname; ?></td>
                        <td><?php echo $ovetime->time_range; ?></td>
                        <td><?php echo $ovetime->notes; ?></td>
                        <td class="status"><?php echo $ot_status; ?></td>
                        <td class="action"><button type="button" data-id="<?php echo $otID; ?>"
                                data-status="<?php echo $_ot_status; ?>"
                                class="btn btn-secondary btn-sm _secondary approval"><?php echo $_ot_buttonLabel ?></button>
                            <button type="button" data-id="<?php echo $otID; ?>"
                                class="btn btn-danger btn-sm _danger delete">Delete</button></td>
                    </tr>
                    <?php
                    }
                }else{
                    ?><tr>
                        <td colspan="6">No Overtime applied</td>
                    </tr><?php
                }
                ?>
                </tbody>
            </table>
        </div> <!-- card body -->
      </div> <!-- .card -->
    </div> <!-- Card Wrapper -->
  </div> <!-- payroll-Timecard-wrapper -->
</div><!-- /.container-fluid-->
<!-- Modal -->
<!-- Modal Pop up -->
<div class="modal fade" id="userLeaveApplicationModal" role="dialog" aria-labelledby="userApplication" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white" id="userApplication">Leave Application Form</h5>
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
                          if( !empty( $employees ) ){
                          foreach( $employees as $employee ){
                            ?><option value="<?php echo $employee->id; ?>"><?php echo $employee->lname.', '.$employee->fname; ?></option><?php
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
                            <select id="am_pm" class="form-control" name="am_pm">
                              <option value="">Whole Day</option>
                              <?php
                              foreach ( leave_am_pm() as $key => $value) {
                                ?><option value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
                              }
                              ?>
                            </select>   
                          </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">Apply Leave</button>
                      </div>
                    </div><!--  card  --> 
                  </div>     
                </div>  
            </form>  <!-- addUserLeave-form  --> 
        </div>
      </div>
    </form> <!-- Schedule Form -->
  </div>
</div>
<!-- Modal Pop up -->
<div class="modal fade" id="overtimeApplicationModal" tabindex="-1" role="dialog" aria-labelledby="userApplication" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white" id="userApplication">Apply Overtime</h5>
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
                                        if( !empty( $employees  ) ){
                                            foreach ($employees  as $employee ) {
                                                ?><option value="<?php echo $employee->id; ?>" ><?php echo $employee->lname; ?>, <?php echo $employee->fname; ?></option><?php
                                            }
                                        }else{
                                        ?><option >NO Registered Employee Found</option><?php
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
                                <input id="time-end" type="text" class="form-control time end" id="time-end" required="required" >
                                </div>
                                <div class="form-group col-md-12">
                                <label for="notes">Notes</label>
                                <textarea id="notes" class="form-control" required="required"></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg pm-blue">Apply Overtime</button>
                        </div>
                    </div><!--  card  -->  
                </div>  
            </form>  <!-- addUserSchedule-form  --> 
        </div>
      </div>
    </form> <!-- Schedule Form -->
  </div>
</div>
<?php include_once('footer.php'); ?>