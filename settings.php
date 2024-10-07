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
    <li class="breadcrumb-item active">Settings</li>
  </ol>
  <!-- Icon Cards-->
  <div id="settings-wrapper" class="row">
    <div class="col-12">
      <div class="row">
        <div class="col-md-6 mb-6 wptf-section">
          <div id="administrative-emails" class="card mb-3">
            <div class="card-header">
              Administrative Emails
            </div> <!-- card Header -->
            <div class="card-body">
              <div class="form-row">
                <label class="sr-only" for="admin-email">Admin Email</label>
                <div class="input-group mb-2 col-md-8">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><span class="fa fa-fw fa-envelope"></span></div>
                  </div>
                  <input type="email" class="form-control" id="admin-email" placeholder="Admin Email" value="<?php echo $settings->get_settings_by_name('admin-email', true, 'value' ); ?>">
                </div>
                <button id="save-admin-email" class="btn btn-primary pm-blue btn-lg mb-2">Save</button>
              </div>
              <div class="form-row">
                <label class="sr-only" for="hr-email">HR Email</label>
                <div class="input-group mb-2 col-md-8">
                  <div class="input-group-prepend">
                    <div class="input-group-text"><span class="fa fa-fw fa-envelope"></span></div>
                  </div>
                  <input type="email" class="form-control" id="hr-email" placeholder="HR Email" value="<?php echo $settings->get_settings_by_name('hr-email', true, 'value' ); ?>">
                </div>
                <button id="save-hr-email" class="btn btn-primary pm-blue btn-lg mb-2">Save</button>
              </div>
            </div> <!-- Card Body -->
          </div> <!-- card @ Late Allowance Form -->
          <div class="card mb-3">
            <div class="card-header">
              Withholding Tax
            </div> <!-- card Header -->
            <div class="card-body">
              <div class="form-row">
                  <div class="form-check">
                    <label for="tax_salary_period">Withholding Tax Salary Deduction</label>
                    <select id="tax_salary_period" class="form-control" name="tax_salary_period">
                      <option value="">--Select One--</option>
                      <option value="1" <?php echo ( $settings->get_settings_by_name('tax-salary-period', true, 'value' ) == 1 ) ? 'selected' : '' ; ?> >Daily</option>
                      <option value="2" <?php echo ( $settings->get_settings_by_name('tax-salary-period', true, 'value' ) == 2 ) ? 'selected' : '' ; ?>>Weekly</option>
                      <option value="3" <?php echo ( $settings->get_settings_by_name('tax-salary-period', true, 'value' ) == 3 ) ? 'selected' : '' ; ?>>Semi-Monthly</option>
                      <option value="4" <?php echo ( $settings->get_settings_by_name('tax-salary-period', true, 'value' ) == 4 ) ? 'selected' : '' ; ?>>Monthly</option>  
                    </select>                                  
                  </div>
              </div>
            </div> <!-- Card Body -->
          </div> <!-- card @ Withholding Tax  Form -->
          <div class="card mb-3">
            <div class="card-header">
              Holiday Amount 
            </div> <!-- card Header -->
            <div class="card-body">
              <form id="holiday-amount-form">
                <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="holiday_amount">Set Holiday Amount</label>
                      <input type="text" class="form-control number" id="holiday_amount" placeholder="0" name="holiday_amount" value="<?php echo $settings->get_settings_by_name('holiday-amount', true, 'value' ); ?>" required="required">
                    </div>
                    <div class="form-group col-md-6">
                      <button type="submit" class="btn btn-primary pm-blue btn-lg">Set Amount</button>
                    </div>
                </div>
              </form> <!-- Holiday Amount  Form -->

              <!-- 4.0.3 changes - USE ACTUAL RATE - start -->
              <?php $is_holiday_actual_rate_set = $settings->get_settings_by_name( 'holiday-actual-rate', true, 'value' ); ?>
              <label for="holiday_amt_rate">Actual Rate</label>
              <input type="checkbox" id="holiday_amt_rate" name="holiday_amt_rate" <?php echo $is_holiday_actual_rate_set ? 'checked' : ''; ?> />
              <small class="text-danger">If checked, holiday amount will be based on daily rate.</small>
              <!-- 4.0.3 changes - end -->

            </div> <!-- Card Body -->
          </div> <!-- card @ Holiday Amount Form -->
          <div class="card mb-3">
            <div class="card-header">
              Night Diff 
            </div> <!-- card Header -->
            <div class="card-body">
              <form id="nightdiff-amount-form">
                <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="nightdiff_amount">Set Night Diff Amount</label>
                      <input type="text" class="form-control number" id="nightdiff_amount" placeholder="0" name="nightdiff_amount" value="<?php echo $settings->get_settings_by_name('nightdiff-amount', true, 'value' ); ?>" required="required">
                    </div>
                    <div class="form-group col-md-6">
                      <button type="submit" class="btn btn-primary pm-blue btn-lg">Set Amount</button>
                    </div>
                </div>
              </form> <!-- Night Diff Amount  Form -->

              <!-- 4.1.xxx changes - USE ACTUAL RATE - start -->
              <?php $is_nightdiff_actual_rate_set = $settings->get_settings_by_name( 'nightdiff-actual-rate', true, 'value' ); ?>
              <label for="nightdiff_amt_rate">Actual Rate</label>
              <input type="checkbox" id="nightdiff_amt_rate" name="nightdiff_amt_rate" <?php echo $is_nightdiff_actual_rate_set ? 'checked' : ''; ?> />
              <small class="text-danger">If checked, night diff amount will be based on hourly rate.</small>
              <!-- 4.1.xxx changes - end -->

            </div> <!-- Card Body -->
          </div> <!-- card @ Night Diff Amount Form -->
          <div class="card mb-3">
            <div class="card-header">
              Holiday
            </div> <!-- card Header -->
            <div class="card-body">
              <div class="form-row">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="holiday_pay" id="holiday_pay" value="1" <?php echo $settings->get_settings_by_name('sunday-holiday', true, 'value' ) ? 'checked' : '' ; ?> >
                    <label class="form-check-label" for="holiday_pay">
                       Enable Regular Holiday (Sunday) no work with pay?
                    </label>
                  </div>
              </div>
            </div> <!-- Card Body -->
          </div> <!-- card @ Holiday Setting  Form -->
        </div> <!-- wptf-section -->
        <div class="col-md-6 mb-6 wptf-section">
          <div class="card mb-3">
            <div class="card-header">
              Late Allowance
            </div> <!-- card Header -->
            <div class="card-body">
              <form id="late-allowance-form">
                <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="late_allowance">Set Late Allowance (in minutes)</label>
                      <input type="text" class="form-control number" id="late_allowance" placeholder="0" name="late_allowance" value="<?php echo $settings->get_settings_by_name('late-allowance', true, 'value' ); ?>" required="required">
                    </div>
                    <div class="form-group col-md-6">
                      <button type="submit" class="btn btn-primary pm-blue btn-lg">Set Allowance</button>
                    </div>
                </div>
              </form> <!-- Late Allowance Form -->
            </div> <!-- Card Body -->
          </div> <!-- card @ Late Allowance Form -->
          <div class="card mb-3">
            <div class="card-header">
              Late Amount 
            </div> <!-- card Header -->
            <div class="card-body">
              <form id="late-amount-form">
                <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="late_amount">Set Late Amount (per minute)</label>
                      <input type="text" class="form-control number" id="late_amount" placeholder="0" name="late_amount" value="<?php echo $settings->get_settings_by_name('late-amount', true, 'value' ); ?>" required="required">
                    </div>
                    <div class="form-group col-md-6">
                      <button type="submit" class="btn btn-primary pm-blue btn-lg">Set Amount</button>
                    </div>
                </div>
              </form> <!-- Late Late Amount  Form -->
            </div> <!-- Card Body -->
          </div> <!-- card @ Late Amount Form -->
          <div class="card mb-3">
            <div class="card-header">
              Leave Amount 
            </div> <!-- card Header -->
            <div class="card-body">
              <form id="leave-amount-form">
                <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="leave_amount">Set Leave Amount</label>
                      <input type="text" class="form-control number" id="leave_amount" placeholder="0" name="leave_amount" value="<?php echo $settings->get_settings_by_name('leave-amount', true, 'value' ); ?>" required="required">
                    </div>
                    <div class="form-group col-md-6">
                      <button type="submit" class="btn btn-primary pm-blue btn-lg">Set Amount</button>
                    </div>
                </div>
              </form> <!-- Leave Amount  Form -->

              <!-- 4.0.3 changes - USE ACTUAL RATE - start -->
              <?php $is_leave_actual_rate_set = $settings->get_settings_by_name( 'leave-actual-rate', true, 'value' ); ?>
              <label for="leave_amt_rate">Actual Rate</label>
              <input type="checkbox" id="leave_amt_rate" name="leave_amt_rate" <?php echo $is_leave_actual_rate_set ? 'checked' : ''; ?> />
              <small class="text-danger">If checked, leave amount will be based on daily rate.</small>
              <!-- 4.0.3 changes - end -->

            </div> <!-- Card Body -->
          </div> <!-- card @ Leave Amount Form -->
          <div class="card mb-3 d-none">
            <div class="card-header">
              13th Month 
            </div> <!-- card Header -->
            <div class="card-body">
              <form id="thirteenth-month-form">
                <div class="form-row">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="thirteenth_month" id="thirteenth_month" value="1" <?php echo $settings->get_settings_by_name('thn-month-cutoff', true, 'value' ) ? 'checked' : '' ; ?> >
                      <label class="form-check-label" for="thirteenth_month">
                         Enable 13th month pay every cut-off?
                      </label>
                    </div>
                </div>
              </form> <!-- Late 13th Month Setting   Form -->
            </div> <!-- Card Body -->
          </div> <!-- card @ 13th Month Setting  Form -->
          <div class="card mb-3">
            <div class="card-header">
              Overtime 
            </div> <!-- card Header -->
            <div class="card-body">
              <form id="overtime-amount-form">
                <div class="form-row">
                    <div class="form-group col-md-6">
                      <label for="overtime_amount">Set Overtime Amount</label>
                      <input type="text" class="form-control number" id="overtime_amount" placeholder="0" name="overtime_amount" value="<?php echo $settings->get_settings_by_name('overtime-amount', true, 'value' ); ?>" required="required">
                    </div>
                    <div class="form-group col-md-6">
                      <button type="submit" class="btn btn-primary pm-blue btn-lg">Set Amount</button>
                    </div>
                </div>
              </form> <!-- Overtime Amount  Form -->

              <!-- 4.1.xxx changes - USE ACTUAL RATE - start -->
              <?php $is_overtime_actual_rate_set = $settings->get_settings_by_name( 'overtime-actual-rate', true, 'value' ); ?>
              <label for="overtime_amt_rate">Actual Rate</label>
              <input type="checkbox" id="overtime_amt_rate" name="overtime_amt_rate" <?php echo $is_overtime_actual_rate_set ? 'checked' : ''; ?> />
              <small class="text-danger">If checked, overtime amount will be based on hourly rate.</small>
              <!-- 4.1.xxx changes - end -->

            </div> <!-- Card Body -->
          </div> <!-- card @ Overtime Diff Amount Form -->
        </div> <!-- wptf-section -->
      </div>
    </div>
    <div class="col-12 mb-6 Site-details">
      <div class="card mb-3">
        <div class="card-header">
          Company Information
        </div> <!-- card Header -->
        <div class="card-body">
          <div class="col-md-6 offset-md-3 text-center">
            <img id="company-logo" src="<?php echo pcm_get_company_logo( ); ?>" class="mx-auto img-fluid d-block" alt="avatar">
            <h6 id="upload-company-logo" class="mt-2" data-toggle="modal" data-target="#uploadCompanyLogo">
              <button type="button" class="custom-file-control btn btn-outline-secondary btn-lg">Upload a Company Logo</button>
            </h6>
            <small class="text-danger">Preferred image size: 190 x 60</small>
          </div>
          <form id="company-information" class="col-md-12">
            <div class="form-group">
              <label for="company-name">Company name:</label>
              <input type="text" class="form-control" id="company-name" value="<?php echo pcm_get_company_info()->name; ?>">
            </div>
            <div class="form-group">
              <label for="company-address">Address:</label>
              <textarea class="form-control" id="company-address" rows="3"><?php echo pcm_get_company_info()->address; ?></textarea>
            </div>
            <div class="form-group">
              <label for="company-email">Email:</label>
              <input type="email" class="form-control" id="company-email" placeholder="myemail@example.com" value="<?php echo pcm_get_company_info()->email; ?>">
            </div>
            <div class="form-group">
              <label for="company-phone">Phone Number:</label>
              <input type="text" class="form-control" id="company-phone" value="<?php echo pcm_get_company_info()->phone; ?>">
            </div>
            <div class="form-group">
              <label for="company-website">Website:</label>
              <input type="text" class="form-control" id="company-website" value="<?php echo pcm_get_company_info()->website; ?>">
            </div> 
            <div class="form-group">
              <button type="submit" class="btn btn-primary pm-blue btn-lg" id="submit-company-info">Save</button>
            </div>            
          </form>
        </div> <!-- Card Body -->
      </div> <!-- card @ Withholding Tax  Form -->
    </div>
  </div> <!-- settings-wrapper -->
</div><!-- /.container-fluid-->
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
                    <div class="form-group col-md-12">
                      <!-- <button type="submit" class="btn btn-primary pm-blue btn-lg create-sched-btn">Create Schedule</button> -->
                    </div>
                </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" id="scheduleID" name="scheduleID" value="">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary pm-blue btn-lg _create-sched-btn">Create Schedule</button>
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
                  <input type="text" class="form-control " id="breakTime" placeholder="" name="breakTime" >
                </div>
            </div>      
        </div>
        <div class="modal-footer">
          <input type="hidden" id="scheduleID" name="scheduleID" value="">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary pm-blue btn-lg">Save Changes</button>
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
<!-- Upload Avatar -->
<div class="modal fade" id="uploadCompanyLogo" tabindex="-1" role="dialog" aria-labelledby="upload-companyLogo" aria-hidden="true">
  <div id="view-user-leave" class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white" id="upload-companyLogo">Upload Company Logo</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-row">
              <div id="change-companyLogo"></div>
              <div id="croppie-actions">
                  <input type="file" id="upload" class="btn actionUpload btn-primary pm-blue btn-sm" value="Upload Company Logo" accept="image/*" />
                  <a class="button actionSave btn btn-success btn-sm" data-id="<?php echo $userID; ?>">Save Logo</a>
              </div>
            </div>      
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
  </div>
</div>
<?php include_once('footer.php'); ?>