<?php
if (!defined('ABSPATH')) {
	header('Location: ' . $siteHostAdmin . '404.php');
	exit; //Exit if accessed directly
}
$date 		 = pcm_current_date();
$dateFrom 	 = pcm_date_first_day($date);
$dateTo 	 = pcm_date_last_day($date);
$current_month = date('F d, Y', strtotime($date));
$current_date  = date('Y-m-d');
$userID 	 = $sessionUserId;
$biometricID = $employee->get_user_data_by_id($userID, 'idd');
$record 	 = new Records($biometricID, $date, $dateTo);
$logs 	 	 = $record->all_datelogs();
// Get User Leave
$leaves 		 = $employee->get_user_leave_daterange($userID, $dateFrom, $dateTo);
// Get Ovetime 
$overtime_records = $employee->get_overtime($userID, $dateFrom, $dateTo);
?>
<div class="row">
	<div id="user-timecard" class="col-md-12">
		<div class="card mb-3 bg-light">
			<div class="card-header">
				<strong>Time Card Date: <?php echo $current_month; ?></strong>
			</div>
			<div class="card-body">
				<div class="card-body-icon"><i class="fa fa-fw fa-calendar"></i></div>
				<table class="table table-bordered filter-table" id="timecardTable" width="100%" cellspacing="0">
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
							<th>OT Hours</th>
							<th>Late Hours</th>
							<th>Work Hours</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (!empty($logs)) {
							$total_late = 0;
							$total_time = 0;
							$total_ot   = 0;
							foreach ($logs as $date_log => $log_value) {

								if ($date_log != $current_date) {
									continue;
								}

								$log_data       = $record->get_work_hours($log_value, $date_log);
								$ot_data        = $record->get_ot_hours($log_value, $date_log);
								$schedule_date  = $record->get_user_schedule_date($biometricID, $date_log);

								$log_late       = unix_to_hour($log_data['late']);
								$log_time_spent = unix_to_hour($log_data['time_consume']);
								$ot_time_spent  = unix_to_hour($ot_data);

								$total_late     = $total_late + $log_data['late'];
								$total_time     = $total_time + $log_data['time_consume'];
								$total_ot       = $total_ot + $ot_data;

								$schedule_name  = "Open Schedule";
								$mark_ftimeIn    = '';
								$mark_ftimeOut   = '';
								$mark_stimeIn    = '';
								$mark_stimeOut   = '';
								if (!empty($schedule_date)) {
									$schedule_name = $schedule_date['name'];
									$sched_ftimeIn    = pcm_time_to_unix($date . ' ' . $schedule_date['ftimein']);
									$sched_ftimeOut   = pcm_time_to_unix($date . ' ' . $schedule_date['ftimeout']);
									$sched_stimeIn    = pcm_time_to_unix($date . ' ' . $schedule_date['stimein']);
									$sched_stimeOut   = pcm_time_to_unix($date . ' ' . $schedule_date['stimeout']);
									// mark time log if late
									if (!empty($log_value['ftimeIn']['time'])) :
										$ftimeIn = $log_value['ftimeIn']['time'];
										if ($sched_ftimeIn < $ftimeIn) {
											$mark_ftimeIn = 'warning';
										}
									endif;
									if (!empty($log_value['ftimeOut']['time'])) :
										$ftimeOut = $log_value['ftimeOut']['time'];
										if ($sched_ftimeOut > $ftimeOut) {
											$mark_ftimeOut = 'warning';
										}
									endif;
									if (!empty($log_value['stimeIn']['time'])) :
										$stimeIn = $log_value['stimeIn']['time'];
										if ($sched_stimeIn < $stimeIn) {
											$mark_stimeIn = 'warning';
										}
									endif;
									if (!empty($log_value['stimeOut']['time'])) :
										$stimeOut = $log_value['stimeOut']['time'];
										if ($sched_stimeOut > $stimeOut) {
											$mark_stimeOut = 'warning';
										}
									endif;
								}

						?>
								<tr>
									<td class="logDate"><?php echo $date_log; ?></td>
									<td><?php echo $schedule_name; ?></td>
									<td id="<?php echo $log_value['ftimeIn']['id']; ?>" class="<?php echo $mark_ftimeIn; ?>">
										<?php if (!empty($log_value['ftimeIn']['time'])) : ?>
											<?php if ($log_value['ftimeIn']['absent'] == '1') : ?>
												<?php echo 'Absent: ' . $log_value['ftimeIn']['comment']; ?>
											<?php else : ?>
												<?php echo pcm_unix_to_time($log_value['ftimeIn']['time']); ?>
											<?php endif; ?>
										<?php endif; ?>
									</td>
									<td id="<?php echo $log_value['ftimeOut']['id']; ?>" class="<?php echo $mark_ftimeOut; ?>">
										<?php if (!empty($log_value['ftimeOut']['time'])) : ?>
											<?php if ($log_value['ftimeOut']['absent'] == '1') : ?>
												<?php echo 'Absent: ' . $log_value['ftimeOut']['comment']; ?>
											<?php else : ?>
												<?php echo pcm_unix_to_time($log_value['ftimeOut']['time']); ?>
											<?php endif; ?>
										<?php endif; ?>
									</td>
									<td id="<?php echo $log_value['stimeIn']['id']; ?>" class="<?php echo $mark_stimeIn; ?>">
										<?php if (!empty($log_value['stimeIn']['time'])) : ?>
											<?php if ($log_value['stimeIn']['absent'] == '1') : ?>
												<?php echo 'Absent: ' . $log_value['stimeIn']['comment']; ?>
											<?php else : ?>
												<?php echo pcm_unix_to_time($log_value['stimeIn']['time']); ?>
											<?php endif; ?>
										<?php endif; ?>
									</td>
									<td id="<?php echo $log_value['stimeOut']['id']; ?>" class="<?php echo $mark_stimeOut; ?>">
										<?php if (!empty($log_value['stimeOut']['time'])) : ?>
											<?php if ($log_value['stimeOut']['absent'] == '1') : ?>
												<?php echo 'Absent: ' . $log_value['stimeOut']['comment']; ?>
											<?php else : ?>
												<?php echo pcm_unix_to_time($log_value['stimeOut']['time']); ?>
											<?php endif; ?>
										<?php endif; ?>
									</td>
									<td id="<?php echo $log_value['OTin']['id']; ?>">
										<?php if (!empty($log_value['OTin']['time'])) : ?>
											<?php echo pcm_unix_to_time($log_value['OTin']['time']); ?>
										<?php endif; ?>
									</td>
									<td id="<?php echo $log_value['OTout']['id']; ?>">
										<?php if (!empty($log_value['OTout']['time'])) : ?>
											<?php echo pcm_unix_to_time($log_value['OTout']['time']); ?>
										<?php endif; ?>
									</td>
									<td>
										<?php echo $ot_time_spent; ?>
									</td>
									<td class="<?php echo ($log_data['late']) ? 'warning' : ''; ?>">
										<?php echo $log_late; ?>
									</td>
									<td>
										<?php echo $log_time_spent; ?>
									</td>
								</tr>
							<?php
							}
							?>
							<tr style="color: #28a745;">
								<th colspan="8" style="text-align: right;">Accumulated Records</th>
								<th><?php echo unix_to_hour($total_ot); ?></th>
								<th><?php echo unix_to_hour($total_late); ?></th>
								<th><?php echo unix_to_hour($total_time); ?> </th>
							</tr>
						<?php
						} else {
						?><tr>
								<td colspan="11">No record Found</td>
							</tr><?php
									}
										?>
					</tbody>
				</table>
			</div>
		</div>
	</div> <!-- User Leave -->
	<div id="user-leave" class="col-md-6">
		<div class="card mb-3 card text-white bg-primary">
			<div class="card-header">
				<strong>Applied Leave</strong> <a href="<?php echo $siteHostAdmin; ?>profile.php#leave-calendar-wrapper" class="btn btn-light">Apply Leave</a>
			</div>
			<div class="card-body">
				<div class="card-body-icon"><i class="fa fa-fw fa-plane"></i></div>
				<table class="table table-bordered" id="userLeaveTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>Date</th>
							<th>Leave Type</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($leaves) {
							foreach ($leaves as $leave) {
								$status = $leave->status ? 'Approved' : 'Pending Approval';
						?>
								<tr>
									<td><?php echo $leave->date; ?></td>
									<td><?php echo work_status()[$leave->type]; ?></td>
									<td><?php echo $status; ?></td>
								</tr>
							<?php
							}
						} else {
							?><tr>
								<td colspan="3">No Leave applied</td>
							</tr><?php
									}
										?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div id="user-overtime" class="col-md-6">
		<div class="card mb-3 card text-white bg-success">
			<div class="card-header">
				<strong>Applied Overtime</strong> <a href="<?php echo $siteHostAdmin; ?>profile.php#leave-calendar-wrapper" class="btn btn-light">Apply Overtime</a>
			</div>
			<div class="card-body">
				<div class="card-body-icon"><i class="fa fa-fw fa-clock-o"></i></div>
				<table class="table table-bordered" id="userLeaveTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>Date</th>
							<th>Time Range</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($overtime_records) {
							foreach ($overtime_records as $ovetime) {
								$status = $ovetime->status ? 'Approved' : 'Pending Approval';
						?>
								<tr>
									<td><?php echo $ovetime->date; ?></td>
									<td><?php echo $ovetime->time_range; ?></td>
									<td><?php echo $status; ?></td>
								</tr>
							<?php
							}
						} else {
							?><tr>
								<td colspan="3">No Overtime applied</td>
							</tr><?php
									}
										?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>