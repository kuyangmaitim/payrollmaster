<?php include_once('header.php'); ?>
<?php
  $payroll    =  new Payroll;
  $allPayroll = $payroll->all_payroll();
?>
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="<?php echo $siteHostAdmin; ?>">Dashboard</a>
    </li>
    <li class="breadcrumb-item">Reports</li>
    <li class="breadcrumb-item active">View Payrolls Report</li>
  </ol>
  <!-- Icon Cards-->
  <div id="view-payroll-wrapper" class="row">
    <div class="col-12 mb-3">
      <form method="post">
        <div class="form-row align-items-center">
          <div class="form-group col-md-4">
            <label class="sr-only" for="inlineFormInputGroup">Payroll Name</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text">Payroll Name</div>
              </div>
              <select id="payroll-name" name="payroll_name" class="form-control" required="required">
                <option value="">Choose...</option>
                <?php
                if( !empty( $allPayroll ) ){
                  foreach ($allPayroll as $payrollName ) {
                    ?><option value="<?php echo $payrollName ; ?>"><?php echo $payrollName ; ?></option><?php
                  }
                }
                ?>
              </select>
            </div>
          </div>
          <div class="form-group col-auto">
            <button type="submit" class="btn btn-primary btn-lg mb-2 pm-blue">Submit</button>
          </div>
        </div>
      </form>
      <div class="card mb-3 bg-light">
        <div class="card-header">
          Payroll Data
        </div> <!-- card Header -->
        <div class="card-body">
          <div class="card-body-icon"><i class="fa fa-fw fa-credit-card text-success"></i></div>
          <!-- Check if Form is Submitted -->
          <?php if( isset( $_POST['payroll_name'] ) ): $payrollName = $_POST['payroll_name']; ?>
            <?php
            $payrollList  = $payroll->get_payroll( $payrollName );
            $payroll_dates = $payroll->get_payroll_dates( $payrollName );
            $values = array();
            $_totals = array();
            $_total_init_salary      = 0;
            $_total_leave_amt        = 0;
            $_total_reg_amt          = 0;
            $_total_sp_amt           = 0;
            $_total_prem_amt         = 0;
            $_total_ot_amt           = 0;
            $_total_nd_amt           = 0;
            $_total_adj_amt          = 0;
            $_total_nth_amt          = 0;
            $_total_gross_amt        = 0;
            $_total_sss_amt          = 0;
            $_total_hdmf_amt         = 0;
            $_total_ph_amt           = 0;
            $_total_ins_amt          = 0;
            $_total_sss_loan_amt     = 0;
            $_total_hdmf_loan_amt    = 0;
            $_total_ca_amt           = 0;
            $_total_abs_amt          = 0;
            $_total_late_amt         = 0;
            $_total_deductions_amt   = 0;
            $_grand_total_net_income = 0;
            $_emp_count = 0;
            foreach ($payrollList as $list) {
              $_emp_count += 1;
              $consume_hour   = format_value( $list->consume_hour );
              $num_of_days    = number_format( ( $consume_hour / 8 ), 1 );
              $initial_salary = format_value( $list->initial_salary );
              $pay_type       = format_value( $list->pay_type );
              $rate_hour      = format_value( $list->rate_hour );
              $daily_rate     = format_value( $list->daily_rate );
              $leave_amount   = format_value( $list->leave_amount );
              $thn_month      = format_value( $list->thn_month );
              $reg_holiday    = format_value( $list->reg_holiday );
              $sp_holiday     = format_value( $list->sp_holiday );
              $prm_holiday    = format_value( $list->prm_holiday );
              $ot_amount      = format_value( $list->ot_amount );
              $night_diff     = format_value( $list->night_diff );
              $amt_cola       = format_value( $list->amt_cola );
              $allow_pera     = format_value( $list->allow_pera );
              $allow_rice     = format_value( $list->allow_rice );
              $adjustment     = format_value( $list->adjustment );
              $sss_cont       = format_value( $list->sss_cont );
              $hdmf_cont      = format_value( $list->hdmf_cont );
              $phil_cont      = format_value( $list->phil_cont );
              $gsis_cont      = format_value( $list->gsis_cont );
              $loan_sss       = format_value( $list->loan_sss );
              $loan_hdmf      = format_value( $list->loan_hdmf );
              $loan_policy    = format_value( $list->loan_policy );
              $loan_gsis      = format_value( $list->loan_gsis );
              $ela_sos        = format_value( $list->ela_sos );
              $uniform        = format_value( $list->uniform );
              $other_deduction = format_value( $list->other_deduction );
              $cash_advance   = format_value( $list->cash_advance );
              $absent_amount   = format_value( $list->absent_amount );
              $late_amount    = format_value( $list->late_amount );
              $withholding_tax = format_value( $list->withholding_tax );
              // initial Salary
              if( $pay_type != 2 ){
                $initial_salary = $consume_hour * $list->rate_hour;
              }
              // Gross Salary
              $gross_salary   = $initial_salary + $leave_amount + $reg_holiday + $sp_holiday + $prm_holiday + $ot_amount + $night_diff + $amt_cola + $adjustment + $allow_pera + $allow_rice;
              // Total Deductions
              $total_deduction  = $sss_cont + $hdmf_cont + $phil_cont + $loan_sss + $loan_hdmf + $uniform + $other_deduction + $cash_advance + $absent_amount + $late_amount + $withholding_tax + $loan_policy + $loan_gsis + $ela_sos + $gsis_cont;
              // NET Salary
              $net_salary = ($gross_salary - $total_deduction) + $thn_month;
              $temp_array = array(
                $_emp_count,
                $list->employee,
                $num_of_days,
                $consume_hour,
                $daily_rate,
                format_value($initial_salary),
                $leave_amount,
                $reg_holiday,
                $sp_holiday,
                $prm_holiday,
                $ot_amount,
                $night_diff,
                $adjustment,
                format_value($gross_salary),
                $sss_cont,
                $hdmf_cont,
                $phil_cont,
                $gsis_cont,
                $loan_sss,
                $loan_hdmf,
                $cash_advance,
                $absent_amount,
                $late_amount,
                format_value($total_deduction),
                $thn_month,
                format_value($net_salary)
              );
              $values[] = $temp_array;
              $_total_init_salary += $initial_salary;
              $_total_leave_amt += $leave_amount;
              $_total_reg_amt += $reg_holiday;
              $_total_sp_amt += $sp_holiday;
              $_total_prem_amt += $prm_holiday;
              $_total_ot_amt += $ot_amount;
              $_total_nd_amt += $night_diff;
              $_total_adj_amt += $adjustment;
              $_total_nth_amt += $thn_month;
              $_total_gross_amt += $gross_salary;
              $_total_sss_amt += $sss_cont;
              $_total_hdmf_amt += $hdmf_cont;
              $_total_ph_amt += $phil_cont;
              $_total_ins_amt += $gsis_cont;
              $_total_sss_loan_amt += $loan_sss;
              $_total_hdmf_loan_amt += $loan_hdmf;
              $_total_ca_amt += $cash_advance;
              $_total_abs_amt += $absent_amount;
              $_total_late_amt += $late_amount;
              $_total_deductions_amt += $total_deduction;
              $_grand_total_net_income += $net_salary;
            }
            $_totals = array(
              '',
              '',
              '',
              '',
              '',
              $_total_init_salary,
              $_total_leave_amt,
              $_total_reg_amt,
              $_total_sp_amt,
              $_total_prem_amt,
              $_total_ot_amt,
              $_total_nd_amt,
              $_total_adj_amt,
              $_total_gross_amt,
              $_total_sss_amt,
              $_total_hdmf_amt,
              $_total_ph_amt,
              $_total_ins_amt,
              $_total_sss_loan_amt,
              $_total_hdmf_loan_amt,
              $_total_ca_amt,
              $_total_abs_amt,
              $_total_late_amt,
              $_total_deductions_amt,
              $_total_nth_amt,
              $_grand_total_net_income
            );
            $values[] = $_totals;
            ?>
            <div id="payroll-data" class="row">
              <div class="col-md-6 mb-3">
                <h6>Payroll Name: <?php echo $payrollName; ?></h6>
                <p>Date From: <?php echo $payroll_dates[0]->date_from; ?> Date To: <?php echo $payroll_dates[0]->date_to; ?></p>
              </div>
              <div id="print-buttons" class="col-md-6 mb-3 text-right">
                <form action="generate-csv-report.php" class="d-inline" method="post">
                  <input type="hidden" name="report_name" value="<?php echo $payrollName; ?>" />
                  <textarea name="report_data" class="d-none" cols="30" rows="10"><?php echo convert_multi_array($values); ?></textarea>
                  <input type="hidden" name="action" value="generate_payroll" />
                  <button type="submit" class="btn btn-success _success btn-sm mb-2" ><i class="fa fa-fw fa-download"></i> CSV Report</button>
                </form>
                <a id="print-payslip" href="<?php echo $siteHostAdmin; ?>?print=1&name=<?php echo $payrollName; ?>&temp=payslip" class="btn btn-success _success btn-sm mb-2"><i class="fa fa-fw fa-download"></i> Payslip</a>
                <a id="print-distribution" href="<?php echo $siteHostAdmin; ?>?print=1&name=<?php echo $payrollName; ?>&temp=distribution" class="btn btn-success _success btn-sm mb-2"><i class="fa fa-fw fa-download"></i> Distribution Report</a>
              </div>
            </div>
            <?php if( !empty( $payrollList ) ): ?>
            <div id="payrollListTable-wrapper">
              <table class="table table-bordered" id="payrollListTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <td>#</td>
                    <td class="fullname">Employee Name</td>
                    <td>No. Of Days</td>
                    <td>No. Of Hours</td>
                    <td>Daily Rate</td>
                    <td>Initial Salary</td>
                    <td>Leave</td>
                    <td>Regular Holiday</td>
                    <td>Special Holiday</td>
                    <td>Premium</td>
                    <td>Overtime</td>
                    <td>Night Diff.</td>
                    <td>Adjustment</td>
                    <td>Gross Salary</td>
                    <td>SSS</td>
                    <td>HDMF</td>
                    <td>PhilHealth</td>
                    <td>Insurance</td>
                    <td>SSS LOAN</td>
                    <td>HDMF LOAN</td>
                    <td>Cash Advance</td>
                    <td>Absent</td>
                    <td>Late</td>
                    <td>Total Deductions</td>
                    <td>13th Month Pay</td>
                    <td>Net Income</td>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $grand_total_net_income = 0;
                    $total_init_salary      = 0;
                    $total_leave_amt        = 0;
                    $total_reg_amt          = 0;
                    $total_sp_amt           = 0;
                    $total_prem_amt         = 0;
                    $total_ot_amt           = 0;
                    $total_nd_amt           = 0;
                    $total_adj_amt          = 0;
                    $total_nth_amt          = 0;
                    $total_gross_amt        = 0;
                    $total_sss_amt          = 0;
                    $total_hdmf_amt         = 0;
                    $total_ph_amt           = 0;
                    $total_ins_amt          = 0;
                    $total_sss_loan_amt     = 0;
                    $total_hdmf_loan_amt    = 0;
                    $total_ca_amt           = 0;
                    $total_abs_amt          = 0;
                    $total_late_amt         = 0;
                    $total_deductions_amt   = 0;
                    $emp_count = 0;
                  ?>
                  <?php foreach( $payrollList as $list ): ?>
                    <?php
                    $emp_count += 1;
                    $consume_hour   = $list->consume_hour;
                    $initial_salary = format_value( $list->initial_salary );
                    $pay_type       = format_value( $list->pay_type );
                    $rate_hour      = format_value( $list->rate_hour );
                    $daily_rate     = format_value( $list->daily_rate );
                    $leave_amount   = format_value( $list->leave_amount );
                    $thn_month      = format_value( $list->thn_month );
                    $reg_holiday    = format_value( $list->reg_holiday );
                    $sp_holiday     = format_value( $list->sp_holiday );
                    $prm_holiday    = format_value( $list->prm_holiday );
                    $ot_amount      = format_value( $list->ot_amount );
                    $night_diff     = format_value( $list->night_diff );
                    $amt_cola       = format_value( $list->amt_cola );
                    $allow_pera     = format_value( $list->allow_pera );
                    $allow_rice     = format_value( $list->allow_rice );
                    $adjustment     = format_value( $list->adjustment );
                    $sss_cont       = format_value( $list->sss_cont );
                    $hdmf_cont      = format_value( $list->hdmf_cont );
                    $phil_cont      = format_value( $list->phil_cont );
                    $gsis_cont      = format_value( $list->gsis_cont );
                    $loan_sss       = format_value( $list->loan_sss );
                    $loan_hdmf      = format_value( $list->loan_hdmf );
                    $loan_policy    = format_value( $list->loan_policy );
                    $loan_gsis      = format_value( $list->loan_gsis );
                    $ela_sos        = format_value( $list->ela_sos );
                    $uniform        = format_value( $list->uniform );
                    $other_deduction = format_value( $list->other_deduction );
                    $cash_advance   = format_value( $list->cash_advance );
                    $absent_amount   = format_value( $list->absent_amount );
                    $late_amount    = format_value( $list->late_amount );
                    $withholding_tax = format_value( $list->withholding_tax );
                    // initial Salary
                    if( $pay_type != 2 ){
                      $initial_salary = $consume_hour * $list->rate_hour;
                    }
                    // Gross Salary
                    $gross_salary   = $initial_salary + $leave_amount + $reg_holiday + $sp_holiday + $prm_holiday + $ot_amount + $night_diff + $amt_cola + $adjustment + $allow_pera + $allow_rice;
                    // Total Deductions
                    $total_deduction  = $sss_cont + $hdmf_cont + $phil_cont + $loan_sss + $loan_hdmf + $uniform + $other_deduction + $cash_advance + $absent_amount + $late_amount + $withholding_tax + $loan_policy + $loan_gsis + $ela_sos + $gsis_cont;
                    // NET Salary
                    $net_salary     = ($gross_salary - $total_deduction) + $thn_month;
                    ?>
                    <tr>
                      <td><?php echo $emp_count; ?></td>
                      <td class="fullname"><?php echo $list->employee; ?></td>
                      <td><?php echo number_format( ( $consume_hour / 8 ), 1 ); ?></td>
                      <td><?php echo $consume_hour; ?></td>
                      <td><?php echo $daily_rate; ?></td>
                      <td><?php echo format_value( $initial_salary ); ?></td>
                      <td><?php echo $leave_amount; ?></td>
                      <td><?php echo $reg_holiday; ?></td>
                      <td><?php echo $sp_holiday; ?></td>
                      <td><?php echo $prm_holiday; ?></td>
                      <td><?php echo $ot_amount; ?></td>
                      <td><?php echo $night_diff; ?></td>
                      <td><?php echo $adjustment; ?></td>
                      <td><?php echo format_value( $gross_salary ); ?></td>
                      <td><?php echo $sss_cont; ?></td>
                      <td><?php echo $hdmf_cont; ?></td>
                      <td><?php echo $phil_cont; ?></td>
                      <td><?php echo $gsis_cont; ?></td>
                      <td><?php echo $loan_sss; ?></td>
                      <td><?php echo $loan_hdmf; ?></td>
                      <td><?php echo $cash_advance; ?></td>
                      <td><?php echo $absent_amount; ?></td>
                      <td><?php echo $late_amount; ?></td>
                      <td><?php echo format_value( $total_deduction ); ?></td>
                      <td><?php echo $thn_month; ?></td>
                      <td><?php echo format_value( $net_salary ); ?></td>
                    </tr>
                    <?php
                      $grand_total_net_income += $net_salary;
                      $total_init_salary += $initial_salary;
                      $total_leave_amt += $leave_amount;
                      $total_reg_amt += $reg_holiday;
                      $total_sp_amt += $sp_holiday;
                      $total_prem_amt += $prm_holiday;
                      $total_ot_amt += $ot_amount;
                      $total_nd_amt += $night_diff;
                      $total_adj_amt += $adjustment;
                      $total_nth_amt += $thn_month;
                      $total_gross_amt += $gross_salary;
                      $total_sss_amt += $sss_cont;
                      $total_hdmf_amt += $hdmf_cont;
                      $total_ph_amt += $phil_cont;
                      $total_ins_amt += $gsis_cont;
                      $total_sss_loan_amt += $loan_sss;
                      $total_hdmf_loan_amt += $loan_hdmf;
                      $total_ca_amt += $cash_advance;
                      $total_late_amt += $late_amount;
                      $total_deductions_amt += $total_deduction;
                    ?>
                  <?php  endforeach; ?>
                  <tr class="totals-row" style="background-color: #eee;">
                      <td></td> <!-- counter -->
                      <td></td> <!-- fullname -->
                      <td></td> <!-- num of days -->
                      <td></td> <!-- num of hours -->
                      <td></td> <!-- daily rate -->
                      <td><?php echo format_value( $total_init_salary ); ?></td> <!-- init salary -->
                      <td><?php echo format_value($total_leave_amt); ?></td> <!-- leave amount -->
                      <td><?php echo format_value($total_reg_amt); ?></td> <!-- reg holiday -->
                      <td><?php echo format_value($total_sp_amt); ?></td> <!-- sp holiday -->
                      <td><?php echo format_value($total_prem_amt); ?></td> <!-- rest day premium -->
                      <td><?php echo format_value($total_ot_amt); ?></td> <!-- overtime -->
                      <td><?php echo format_value($total_nd_amt); ?></td> <!-- night diff -->
                      <td><?php echo format_value($total_adj_amt); ?></td> <!-- adjustments -->
                      <td><?php echo format_value($total_gross_amt); ?></td> <!-- gross salary -->
                      <td><?php echo format_value($total_sss_amt); ?></td> <!-- sss -->
                      <td><?php echo format_value($total_hdmf_amt); ?></td> <!-- hdmf -->
                      <td><?php echo format_value($total_ph_amt); ?></td> <!-- philhealth -->
                      <td><?php echo format_value($total_ins_amt); ?></td> <!-- insurance -->
                      <td><?php echo format_value($total_sss_loan_amt); ?></td> <!-- sss loan -->
                      <td><?php echo format_value($total_hdmf_loan_amt); ?></td> <!-- hmdf loan -->
                      <td><?php echo format_value($total_ca_amt); ?></td> <!-- cash advance -->
                      <td><?php echo format_value($total_abs_amt); ?></td> <!-- absent amount -->
                      <td><?php echo format_value($total_late_amt); ?></td> <!-- late amount -->
                      <td><?php echo format_value($total_deductions_amt); ?></td> <!-- total deductions -->
                      <td><?php echo format_value($total_nth_amt); ?></td> <!-- thirteenth pay -->
                      <td><?php echo format_value( $grand_total_net_income ); ?></td> <!-- total net -->
                    </tr>
                </tbody>
              </table>
            </div>
            <?php else: ?>
              <h4>Payroll has no records.</h4>
            <?php endif; ?> <!-- End if the data is empty -->
          <?php else: ?>
              <div class="alert alert-success" role="alert">
                Select Payroll name and submit to view Payroll data
              </div>
          <?php endif; ?> <!-- End Check if Form is Submitted -->
        </div> <!--  card-body -->
      </div> <!-- Card wrapper -->
    </div> <!-- Content Wrapper -->
  </div> <!-- view-payroll-wrapper -->
</div><!-- /.container-fluid-->
<?php include_once('footer.php'); ?>