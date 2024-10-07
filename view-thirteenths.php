<?php include_once('header.php'); ?>
<?php
$thirteenth = new Thirteenth;
$all_thirteenth_report = $thirteenth->all_thirteenth_report();
?>
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="<?php echo $siteHostAdmin; ?>">Dashboard</a>
    </li>
    <li class="breadcrumb-item">Reports</li>
    <li class="breadcrumb-item active">View 13th Month Report</li>
  </ol>
  <!-- Icon Cards-->
  <div id="view-payroll-wrapper" class="row">
    <div class="col-12 mb-3">
      <form method="post">
        <div class="form-row align-items-center">
          <div class="form-group col-md-4">
            <label class="sr-only" for="payroll-name">Report Name</label>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <div class="input-group-text">Report Name</div>
              </div>
              <select id="payroll-name" name="report_name" class="form-control" required="required">
                <option value="">Choose...</option>
                <?php
                if (!empty($all_thirteenth_report)) {
                  foreach ($all_thirteenth_report as $report_name) {
                ?><option value="<?php echo $report_name; ?>"><?php echo $report_name; ?></option><?php
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
          13th Month Data
        </div> <!-- card Header -->
        <div class="card-body">
          <div class="card-body-icon"><i class="fa fa-fw fa-credit-card text-success"></i></div>
          <!-- Check if Form is Submitted -->
          <?php if (isset($_POST['report_name'])) : $report_name = $_POST['report_name']; ?>
            <?php
            $thirteenth_reports_list  = $thirteenth->get_thirteenth_report($report_name);
            $_SESSION['nth_report'] = $thirteenth_reports_list;
            $thirteenth_reports_date = $thirteenth->get_thirteenth_report_dates($report_name);
            $values = array();
            $_counter                = 0; // user counter
            $_grand_total_gross      = 0; // total gross salary
            $_grand_total_acc_inc    = 0; // total accumulated income
            $_grand_total_thn_pay    = 0; // total 13th month pay
            $_grand_total_net_income = 0; // total net income
            foreach ($thirteenth_reports_list as $report) {
              $_counter++;
              $_fullname      = $report->employee;
              $_num_days      = $report->num_days;
              $_daily_rate    = number_format($report->daily_rate, 2, '.', '');
              $_gross_salary  = number_format($report->gross_salary, 2, '.', '');
              $_deductions    = number_format($report->deductions, 2, '.', '');
              $_total_acc_inc = number_format($report->total_accumulated_income, 2, '.', '');
              $_thirteenth_amt = number_format($report->thirteenth_amt, 2, '.', '');
              $_rem_sil       = $report->available_sil;
              $_net_income    = number_format($report->net_income, 2, '.', '');
              $temp_array = array(
                $_counter,
                $_fullname,
                $_num_days,
                number_format($_daily_rate, 2, '.', ','),
                number_format($_gross_salary, 2, '.', ','),
                number_format($_deductions, 2, '.', ','),
                number_format($_total_acc_inc, 2, '.', ','),
                number_format($_thirteenth_amt, 2, '.', ','),
                $_rem_sil,
                number_format($_net_income, 2, '.', ','),
              );
              $values[] = $temp_array;
              // calculate totals
              $_grand_total_gross += $_gross_salary; // total gross salary
              $_grand_total_acc_inc += $_total_acc_inc; // total accumulated income
              $_grand_total_thn_pay += $_thirteenth_amt; // total 13th month pay
              $_grand_total_net_income += $_net_income; // total net income
            }
            $values[] = array(
              '',
              '',
              '',
              '',
              '',
              '',
              '',
              '',
              '',
              ''
            );
            $values[] = array(
              'Totals',
              '',
              '',
              '',
              number_format($_grand_total_gross, 2, '.', ','),
              '',
              number_format($_grand_total_acc_inc, 2, '.', ','),
              number_format($_grand_total_thn_pay, 2, '.', ','),
              '',
              number_format($_grand_total_net_income, 2, '.', ',')
            );
            ?>
            <div id="payroll-data" class="row">
              <div class="col-md-6 mb-3">
                <h6>Report Name: <?php echo $report_name; ?></h6>
                <p>Date From: <?php echo $thirteenth_reports_date[0]->date_from; ?> Date To: <?php echo $thirteenth_reports_date[0]->date_to; ?></p>
              </div>
              <div class="col-md-6 mb-3">
                <div id="print-buttons" class="mb-3 d-flex ml-auto text-right" style="width: fit-content;">
                  <form action="generate-csv-report.php" method="post" style="width: fit-content;" class="mr-1">
                    <input type="hidden" name="report_name" value="<?php echo $report_name; ?>" />
                    <input type="hidden" name="report_data" value="<?php echo convert_multi_array($values); ?>" />
                    <input type="hidden" name="action" value="generate_thirteenth" />
                    <button type="submit" class="btn btn-success _success btn-sm mb-2"><i class="fa fa-fw fa-download"></i> CSV Report</button>
                  </form>
                  <a id="print-payslip" href="<?php echo $siteHostAdmin; ?>?print=1&name=<?php echo $report_name; ?>&temp=nth-payslip" class="btn btn-success _success btn-sm mb-2"><i class="fa fa-fw fa-download"></i> Payslip</a>
                  <a id="print-distribution" href="<?php echo $siteHostAdmin; ?>?print=1&name=<?php echo $report_name; ?>&temp=nth-distribution" class="btn btn-success _success btn-sm mb-2 ml-1"><i class="fa fa-fw fa-download"></i> Distribution Report</a>
                </div>
              </div>
            </div>
            <?php if (!empty($thirteenth_reports_list)) : ?>
              <div id="payrollListTable-wrapper">
                <table class="table table-bordered" id="payrollListTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <td>#</td>
                      <td rowspan="3" class="fullname">Employee's Name</td>
                      <td rowspan="3">No. of Days</td>
                      <td rowspan="3">Daily Rate</td>
                      <td rowspan="3">Gross Salary</td>
                      <td rowspan="3">Deductions</td>
                      <td rowspan="2">Total Accumulated Income</td>
                      <td rowspan="3">13th Month Pay</td>
                      <td rowspan="3">Remaining SIL</td>
                      <td rowspan="3">Net Income</td>
                    </tr>
                    <tr class="table-header">
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $counter                = 0; // user counter
                    $grand_total_gross      = 0; // total gross salary
                    $grand_total_acc_inc    = 0; // total accumulated income
                    $grand_total_thn_pay    = 0; // total 13th month pay
                    $grand_total_net_income = 0; // total net income
                    ?>
                    <?php foreach ($thirteenth_reports_list as $data) : ?>
                      <?php
                      $counter++;
                      $fullname     = $data->employee;
                      $num_days     = $data->num_days;
                      $daily_rate   = number_format($data->daily_rate, 2, '.', '');
                      $gross_salary = number_format($data->gross_salary, 2, '.', '');
                      $deductions   = number_format($data->deductions, 2, '.', '');
                      $acc_income   = number_format($data->total_accumulated_income, 2, '.', '');
                      $nth_pay      = number_format($data->thirteenth_amt, 2, '.', '');
                      $rem_sil      = $data->available_sil;
                      $net_nth_pay  = number_format($data->net_income, 2, '.', '');

                      // totals
                      $grand_total_gross      += $gross_salary; // total gross salary
                      $grand_total_acc_inc    += $acc_income; // total accumulated income
                      $grand_total_thn_pay    += $nth_pay; // total 13th month pay
                      $grand_total_net_income += $net_nth_pay; // total net income
                      ?>
                      <tr>
                        <td><?php echo $counter; ?></td>
                        <td class="fullname"><?php echo $fullname; ?></td>
                        <td><?php echo $num_days; ?></td>
                        <td><?php echo number_format($daily_rate, 2, '.', ','); ?></td>
                        <td><?php echo number_format($gross_salary, 2, '.', ','); ?></td>
                        <td><?php echo number_format($deductions, 2, '.', ','); ?></td>
                        <td><?php echo number_format($acc_income, 2, '.', ','); ?></td>
                        <td><?php echo number_format($nth_pay, 2, '.', ','); ?></td>
                        <td><?php echo $rem_sil; ?></td>
                        <td><?php echo number_format($net_nth_pay, 2, '.', ','); ?></td>
                      </tr>
                    <?php endforeach; ?>
                    <tr style="background-color: #f7f7f7;">
                      <td class="text-center" colspan="4">Totals</td>
                      <td><?php echo number_format($grand_total_gross, 2, '.', ','); ?></td> <!-- gross salary -->
                      <td></td> <!-- deductions -->
                      <td><?php echo number_format($grand_total_acc_inc, 2, '.', ','); ?></td> <!-- total accumulated income -->
                      <td><?php echo number_format($grand_total_thn_pay, 2, '.', ','); ?></td> <!-- 13th month pay -->
                      <td></td> <!-- remaining SIl -->
                      <td><?php echo number_format($grand_total_net_income, 2, '.', ','); ?></td> <!-- total net income -->
                    </tr>
                  </tbody>
                </table>
              </div>
            <?php else : ?>
              <h4>13th Month has no records.</h4>
            <?php endif; ?> <!-- End if the data is empty -->
          <?php else : ?>
            <div class="alert alert-success" role="alert">
              Select 13th Month Report name and submit to view 13th Month Report data
            </div>
          <?php endif; ?> <!-- End Check if Form is Submitted -->
        </div> <!--  card-body -->
      </div> <!-- Card wrapper -->
    </div> <!-- Content Wrapper -->
  </div> <!-- view-payroll-wrapper -->
</div><!-- /.container-fluid-->
<?php include_once('footer.php'); ?>