<?php include_once('header.php'); ?>
<?php
$employee =  new Employee;
$settings =  new Settings;
$holiday  =  new Holiday;
$leave_info = new LeaveInformation;

$tax_period       = $settings->get_settings_by_name('tax-salary-period', true, 'value');
$late_amount      = $settings->get_settings_by_name('late-amount', true, 'value');
$holiday_amount   = $settings->get_settings_by_name('holiday-amount', true, 'value');
$gen_late_amount  = $late_amount ? $late_amount : 0;
$tax_period       =  $tax_period ? $tax_period : 4; // Default is monthly
$searched_date  = '';
$dateFrom       = '';
$dateTo         = '';
$selected_user  = array();
if (isset($_POST['generate-payroll'])) {
  $selected_user = array_key_exists('employees', $_POST) ? $_POST['employees']  : array();
  $dateFrom   = $_POST['dateFrom'];
  $dateTo     = $_POST['dateTo'];
  $searched_date = ' : ' . $dateFrom . ' - ' . $dateTo;
}
?>
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Generate 13th Month Report</li>
  </ol>
  <!-- Icon Cards-->
  <div id="payroll-report-wrapper" class="row">
    <div class="col-12 mb-3">
      <div class="card mb-3">
        <div class="card-header">
          13th Month Report Form
        </div> <!-- card Header -->
        <div class="card-body">
          <form id="payroll-report-form" method="POST">
            <div class="form-row align-items-center">
              <div class="col-md-4 mb-3">
                <label class="sr-only" for="designation">Designation</label>
                <select class="designation-option form-control employee-option" id="designation">
                  <option value="0">All Designation</option>
                  <?php
                  $designations = $settings->get_settings_by_name('designation');
                  if (!empty($designations)) {
                    foreach ($designations as $designation) {
                      $designationID   = $designation->id;
                      $designationName = $designation->value;
                  ?><option value="<?php echo $designationID; ?>"><?php echo $designationName; ?></option><?php
                                                                                                        }
                                                                                                      }
                                                                                                          ?>
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <label class="sr-only" for="group">Group</label>
                <select class="group-option form-control employee-option" id="group">
                  <option value="0">All Group</option>
                  <?php
                  $groups = $settings->get_settings_by_name('group');
                  if (!empty($groups)) {
                    foreach ($groups as $group) {
                      $groupID   = $group->id;
                      $groupName = $group->value;
                  ?><option value="<?php echo $groupID; ?>"><?php echo $groupName; ?></option><?php
                                                                                            }
                                                                                          }
                                                                                              ?>
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <label class="sr-only" for="team">Team</label>
                <select class="team-option form-control employee-option" id="team">
                  <option value="0">All Team</option>
                  <?php
                  $teams = $settings->get_settings_by_name('team');
                  if (!empty($teams)) {
                    foreach ($teams as $team) {
                      $teamID   = $team->id;
                      $teamName = $team->value;
                  ?><option value="<?php echo $teamID; ?>"><?php echo $teamName; ?></option><?php
                                                                                          }
                                                                                        }
                                                                                            ?>
                </select>
              </div>
              <div class="form-group col-md-12">
                <label for="employeeList">Employee List ( Select Employee to generate report )</label>
                <select class="form-control" id="employeeList" name="employees[]" multiple="multiple" size="12" required="required" style="height: auto;">
                  <?php
                  $get_all_employees = $employee->get_all_employees(array('fname', 'lname', 'idd', 'access_level'));
                  if (!empty($get_all_employees)) {
                    foreach ($get_all_employees as $employee) {
                      if ($employee->access_level == 1) {
                        continue;
                      } //skip all admin accounts
                  ?><option value="<?php echo $employee->idd; ?>" <?php echo in_array($employee->idd, $selected_user) ? 'selected' : ''; ?>><?php echo $employee->lname; ?>, <?php echo $employee->fname; ?></option><?php
                                                                                                                                                                                                                    }
                                                                                                                                                                                                                  } else {
                                                                                                                                                                                                                      ?><option>NO Registered Employee Found</option><?php
                                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                                      ?>
                </select>
              </div>
              <div class="col-md-5 mb-3">
                <label class="sr-only" for="dateFrom">From</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">From</div>
                  </div>
                  <input type="text" class="form-control datepicker" id="dateFrom" name="dateFrom" placeholder="yyyy-mm-dd" value="<?php echo $dateFrom; ?>" required="required" autocomplete="off">
                </div>
              </div>
              <div class="col-md-5 mb-3">
                <label class="sr-only" for="dateTo">To</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text">To</div>
                  </div>
                  <input type="text" class="form-control datepicker" id="dateTo" name="dateTo" placeholder="yyyy-mm-dd" value="<?php echo $dateTo; ?>" required="required" autocomplete="off">
                </div>
              </div>
              <div class="col-auto mb-3">
                <button type="submit" name="generate-payroll" class="btn btn-primary pm-blue btn-lg">Submit</button>
              </div>
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    Options
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-12">
                        <?php $is_sil_included = isset($_POST['include_sil']) ? true : false; ?>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" name="include_sil" id="include_sil" <?php echo $is_sil_included ? 'checked' : ''; ?> />
                          <label class="form-check-label" for="include_sil">
                            Include Remaining SIL( Converts Remaning SIL into <span class="text-danger" role="button" data-toggle="popover" data-trigger="focus" title="SIL Amount = (Remaining SIL x Daily Rate)">amount</span> and will be added to the total 13th Month Pay )
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div> <!--  card-body -->
      </div> <!-- Card wrapper -->
    </div> <!-- Content Wrapper -->
    <div class="col-12 mb-3 <?php echo isset($_POST['generate-payroll']) ? '' : 'd-none'; ?>">
      <div class="card mb-3">
        <div class="card-header">
          Generated Report <?php echo $searched_date; ?>
        </div> <!-- card Header -->
        <div class="card-body">
          <?php
          if (isset($_POST['generate-payroll'])) {
            $dateFrom   = $_POST['dateFrom'];
            $dateTo     = $_POST['dateTo'];
            $users      = $_POST['employees'];
          ?>
            <form id="generate-thirteenth-form" method="POST" action="#">
              <div id="thirteenthReportTable-wrapper">
                <table class="table table-bordered" id="thirteenthReportTable" width="100%" cellspacing="0">
                  <thead>
                    <tr class="table-header">
                      <td>#</td>
                      <td rowspan="3" class="fullname">Employee's Name</td>
                      <td rowspan="3">No. of Days</td>
                      <td rowspan="3">Daily Rate</td>
                      <td rowspan="3">Gross Salary</td>
                      <td rowspan="3">Deductions</td>
                      <td rowspan="3">Total Accumulated Income</td>
                      <td rowspan="3">13th Month Pay</td>
                      <td rowspan="3">Remaining SIL</td>
                      <td rowspan="3">Net Income</td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($users) {
                      $counter = 0;
                      $grand_total_gross      = 0; // total gross salary
                      $grand_total_acc_inc    = 0; // total accumulated income
                      $grand_total_thn_pay    = 0; // total 13th month pay
                      $grand_total_net_income = 0; // total net income

                      $declared_holidays = $holiday->get_holiday_by_range($dateFrom, $dateTo);
                      foreach ($users as $user) {
                        $record        = new Records($user, $dateFrom, $dateTo);
                        $_employee     = new Employee;

                        $logs          = $record->get_user_logs();
                        $date_logs     = $record->all_datelogs();

                        $userID        = $record->get_user_data_by_biometric($user, 'id');
                        $fname         = $record->get_user_data_by_biometric($user, 'fname');
                        $lname         = $record->get_user_data_by_biometric($user, 'lname');
                        $basic_pay     = $record->get_user_data_by_biometric($user, 'basic_pay');
                        $pay_type      = $record->get_user_data_by_biometric($user, 'pay_type');
                        $tax_exemption = $record->get_user_data_by_biometric($user, 'tax_exemption');
                        $tax_status    = $record->get_user_data_by_biometric($user, 'tax_status');
                        // Day Off
                        $day_off      = unserialize($record->get_user_data_by_biometric($user, 'day_off'));
                        $work_group   = $record->get_user_data_by_biometric($user, 'work_group');
                        $emp_group    = pcm_get_group_by_id($work_group);

                        // Contribution / Deductions
                        $contri_sss         = $record->get_user_data_by_biometric($user, 'contri_sss');
                        $contri_philhealth  = $record->get_user_data_by_biometric($user, 'contri_philhealth');
                        $contri_hmdf        = $record->get_user_data_by_biometric($user, 'contri_hmdf');
                        $contri_gsis        = $record->get_user_data_by_biometric($user, 'contri_gsis');
                        $deduction          = $record->get_user_data_by_biometric($user, 'deduction');

                        // Adjustment
                        $pera_allowance    = $record->get_user_data_by_biometric($user, 'allow_pera');
                        $rice_allowance    = $record->get_user_data_by_biometric($user, 'allow_rice');
                        $adjustment        = $record->get_user_data_by_biometric($user, 'adjustment');
                        $sss_amortization  = $record->get_user_data_by_biometric($user, 'loan_sss');
                        $hdmf_amortization = $record->get_user_data_by_biometric($user, 'loan_hdmf');
                        $policy_amortization  = $record->get_user_data_by_biometric($user, 'loan_policy');
                        $gsis_amortization    = $record->get_user_data_by_biometric($user, 'loan_gsis');
                        $elasos_amortization  = $record->get_user_data_by_biometric($user, 'ela_sos');
                        $emp_late_amount      = $record->get_user_data_by_biometric($user, 'late_amount');

                        $user_deductions = $contri_sss + $contri_philhealth + $contri_hmdf + $deduction + $contri_gsis;

                        $company_contri_sss         = $record->get_user_data_by_biometric($user, 'company_contri_sss');
                        $company_contri_philhealth  = $record->get_user_data_by_biometric($user, 'company_contri_philhealth');
                        $company_contri_hmdf        = $record->get_user_data_by_biometric($user, 'company_contri_hmdf');
                        $company_contri_gsis        = $record->get_user_data_by_biometric($user, 'company_contri_gsis');

                        $available_sil = $leave_info->get_user_sil($userID);

                        # Declared variable for calculation
                        $work_hours   = 0;
                        $work_lates   = 0;
                        $ot_hours     = 0;
                        $init_salary  = 0;
                        $gross_salary = 0;
                        $salary_deductions = 0;
                        $net_salary   = 0;
                        $ot_amount    = 0;
                        $premium      = 0;
                        $premium_amt  = 0;

                        $work_hours  = number_format(unix_to_num_hour($logs['time_consume'] + ($logs['premium'] * 3600) + $logs['late']), 2);

                        # Calculate Employee daily rate

                        $daily_rate = pcm_calculate_daily_rate($pay_type, $day_off, $basic_pay);
                        $rate_hour = format_value(($daily_rate / 8), 2);

                        if (!empty($logs)) {
                          $work_hours = unix_to_num_hour($logs['time_consume'] + ($logs['premium'] * 3600) + $logs['late']);
                          $work_lates = unix_to_num_minute($logs['late']);
                        }

                        # Calculate Late Amount
                        $late_amount_perMinute = ($emp_late_amount) ? $emp_late_amount : $gen_late_amount;
                        $late_amount      = $late_amount_perMinute * $work_lates;

                        # thirteenth month calculation
                        $_fullname      = $lname . ', ' . $fname;
                        $_logs_w_raises = get_logs_with_raises($userID, $date_logs, $basic_pay, $pay_type);
                        $_num_days      = get_num_days_by_work_hours($work_hours);
                        $_gross         = get_init_salary_by_pay_type($pay_type, $_logs_w_raises, $pay_type);
                        // $_deductions    = number_format($late_amount, 2, '.', '');
                        $_deductions    = number_format(0, 2, '.', '');
                        $_acc_income    = get_accumulated_income($_gross, $_deductions);
                        $_thn_month     = get_nth_amount_by_accumulated_income($_acc_income);
                        $_available_sil = $leave_info->get_user_sil($userID) ?: 0;
                        $_user_leaves   = $_employee->get_user_leaves($userID);
                        $_used_sil      = $leave_info->get_user_payable_leaves($_user_leaves) ?: 0;
                        $_remaining_sil = get_remaining_sil($_available_sil, $_used_sil);
                        $_total_sil_amt = get_sil_amount($_remaining_sil, $daily_rate, $is_sil_included);
                        $_net_thn_amt   = number_format(($_thn_month + $_total_sil_amt), 2, '.', '');

                        if($emp_group == 'trainee'){
                          $_num_days = 0;
                          $daily_rate = 0;
                          $_gross = 0;
                          $_deductions = 0;
                          $_acc_income = 0;
                          $_thn_month = 0;
                          $_remaining_sil = 0;
                          $_net_thn_amt = 0;
                        }

                    ?>
                        <tr id="data-<?php echo $counter; ?>">
                          <td><?php echo $counter + 1; ?></td>
                          <td class="fullname">
                            <?php echo $_fullname; ?>
                            <input type="hidden" class="employee" name="thirteenth[<?php echo $counter; ?>][employee]" value="<?php echo $_fullname; ?>">
                          </td>
                          <td data-toggle="tooltip" data-placement="top" title="" data-html="true" data-original-title="<?php echo $_fullname; ?> <br/> Number Of days">
                            <?php echo $_num_days; ?>
                            <input type="hidden" class="num_days" name="thirteenth[<?php echo $counter; ?>][num_days]" value="<?php echo $_num_days; ?>">
                          </td>
                          <td data-toggle="tooltip" data-placement="top" title="" data-html="true" data-original-title="<?php echo $_fullname; ?> <br/> Daily rate">
                            <?php echo number_format($daily_rate, 2, '.', ','); ?>
                            <input type="hidden" name="thirteenth[<?php echo $counter; ?>][daily_rate]" value="<?php echo $daily_rate; ?>">
                          </td>
                          <td data-toggle="tooltip" data-placement="top" title="" data-html="true" data-original-title="<?php echo $_fullname; ?> <br/> Gross Salary">
                            <span class="label_gross_salary"><?php echo number_format($_gross, 2, '.', ','); ?></span>
                            <input class="receivable" type="hidden" name="thirteenth[<?php echo $counter; ?>][gross_salary]" value="<?php echo $_gross; ?>">
                          </td>
                          <td data-toggle="tooltip" data-placement="top" title="" data-html="true" data-original-title="<?php echo $_fullname; ?> <br/> Deductions">
                            <span class="label_deductions"><?php echo number_format($_deductions, 2, '.', ','); ?></span>
                            <input type="hidden" name="thirteenth[<?php echo $counter; ?>][deductions]" value="<?php echo $_deductions; ?>">
                          </td>
                          <td data-toggle="tooltip" data-placement="top" title="" data-html="true" data-original-title="<?php echo $_fullname; ?> <br/> Total Accumulated Income">
                            <span class="label_total_accumulated_income"><?php echo number_format($_acc_income, 2, '.', ','); ?></span>
                            <input type="hidden" name="thirteenth[<?php echo $counter; ?>][total_accumulated_income]" value="<?php echo $_acc_income; ?>">
                          </td>
                          <td data-toggle="tooltip" data-placement="top" title="" data-html="true" data-original-title="<?php echo $_fullname; ?> <br/> 13th Month Pay">
                            <?php echo number_format($_thn_month, 2, '.', ','); ?>
                            <input class="receivable" type="hidden" name="thirteenth[<?php echo $counter; ?>][thn_month]" value="<?php echo $_thn_month; ?>">
                          </td>
                          <td data-toggle="tooltip" data-placement="top" title="" data-html="true" data-original-title="<?php echo $_fullname; ?> <br/> Remaining SIL">
                            <?php echo $_remaining_sil; ?>
                            <input class="receivable" type="hidden" name="thirteenth[<?php echo $counter; ?>][available_sil]" value="<?php echo $_remaining_sil; ?>">
                          </td>
                          <td data-toggle="tooltip" data-placement="top" title="" data-html="true" data-original-title="<?php echo $_fullname; ?> <br/> Net Income">
                            <?php echo number_format($_net_thn_amt, 2, '.', ','); ?>
                            <input class="receivable" type="hidden" name="thirteenth[<?php echo $counter; ?>][net_income]" value="<?php echo $_net_thn_amt; ?>">
                          </td>
                          <input type="hidden" name="thirteenth[<?php echo $counter; ?>][employee_id]" value="<?php echo $userID; ?>">
                          <input type="hidden" name="thirteenth[<?php echo $counter; ?>][date_from]" value="<?php echo $dateFrom; ?>">
                          <input type="hidden" name="thirteenth[<?php echo $counter; ?>][date_to]" value="<?php echo $dateTo; ?>">
                        </tr>
                    <?php
                        $grand_total_gross += $_gross; // total gross salary
                        $grand_total_acc_inc += $_acc_income; // total accumulated income
                        $grand_total_thn_pay += $_thn_month; // total 13th month pay
                        $grand_total_net_income += $_net_thn_amt; // total net income
                        $counter++;
                      } // End foreach
                    } // End If
                    ?>
                    <tr style="background-color: #f7f7f7;">
                      <td id="total_net_income" class="text-center" colspan="4">Totals</td>
                      <td><?php echo number_format($grand_total_gross, 2, '.', ','); ?></td> <!-- gross salary -->
                      <td></td> <!-- deductions -->
                      <td><?php echo number_format($grand_total_acc_inc, 2, '.', ','); ?></td> <!-- total accumulated income -->
                      <td><?php echo number_format($grand_total_thn_pay, 2, '.', ','); ?></td> <!-- 13th month pay -->
                      <td></td> <!-- remaining SIL -->
                      <td id="grand_total_net"><?php echo number_format($grand_total_net_income, 2, '.', ','); ?></td>
                    </tr>
                  </tbody>
                  </tbody>
                </table>
                <div class="col-md-6 mb-3">
                  <label class="sr-only" for="reportName">Report Name</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <div class="input-group-text">Report Name</div>
                    </div>
                    <input type="text" id="reportName" class="form-control" name="name" placeholder="Report Name" required="required" autocomplete="off">
                  </div>
                </div>
                <button type="submit" class="btn btn-primary pm-blue btn-lg" name="submit-report">Generate Report</button>
              </div>
            </form>
          <?php
          }
          ?>
        </div> <!-- card body -->
      </div> <!-- .card -->
    </div> <!-- Card Wrapper -->
  </div> <!-- payroll-report-wrapper -->
</div><!-- /.container-fluid-->
<?php include_once('footer.php'); ?>