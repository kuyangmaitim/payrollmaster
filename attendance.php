<?php include_once('header.php'); ?>
<?php
$employee    = new Employee;
$settings 	 = new Settings;
$date 		 = (isset($_GET['date']) && strtotime($_GET['date'])) ? $_GET['date'] : pcm_current_date();
$employees 	 = $employee->get_all_employees(array('id', 'fname', 'lname', 'idd', 'access_level'));


$is_current_date = ($date >= pcm_current_date()) ? 'disabled' : 'active';
?>
<div class="container-fluid">
	<!-- Breadcrumbs-->
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="#">Dashboard</a>
		</li>
		<li class="breadcrumb-item active">Attendance</li>
	</ol>
	<div class="row">
		<div id="attendance" class="col-md-12">
			<div class="card mb-3 bg-light">
				<div class="card-header text-center">
					<input type="text" class="search-date form-control" style="float: left;width: initial;" placeholder="Search Date" value="<?php echo $date; ?>" />
					<span class="date-page active before" data-date="<?php echo pcm_get_date_before($date); ?>" title="<?php echo pcm_get_date_before($date); ?>"><span class="fa fa-fw fa-chevron-circle-left"></span></span>
					<span class="date-page current"><?php echo $date; ?> - <span class="day-name"><?php echo ucfirst(pcm_get_date_day($date)); ?></span></span>
					<span class="date-page <?php echo $is_current_date; ?> after" data-date="<?php echo pcm_get_date_after($date); ?>" title="<?php echo pcm_get_date_after($date); ?>"><span class="fa fa-fw fa-chevron-circle-right"></span></span>
					<button id="add-log" style="float: right;width: initial;" class="btn btn-outline-success _success" data-toggle="modal" data-target="#addLogModal">Add Log</button>
				</div>
				<div class="card-body">
					<table class="table table-bordered" id="timecardTable" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th class="fullname">Employee Name</th>
								<th>Schedule</th>
								<th>Log In (AM)</th>
								<th>Log Out (AM)</th>
								<th>Log In (PM)</th>
								<th>Log Out (PM)</th>
								<th>OT In</th>
								<th>OT Out</th>
								<th>OT Hours</th>
								<th>Late Hours</th>
								<th>Work Hours</th>
								<?php if ($_REQUEST['debug']) { ?> <th>Under Time</th>
								<?php
								}
								?>

							</tr>
						</thead>
						<tbody>
							<?php
							if (!empty($employees)) {
								$total_late = 0;
								$total_time = 0;
								$total_ot   = 0;
								foreach ($employees as $_employee) {
									if ($_employee->access_level == 1) {
										continue;
									} //skip all admin accounts
									$emp_id 			= $_employee->id;
									$emp_biometricID 	= $_employee->idd;
									$fullname 			= $_employee->lname . ', ' . $_employee->fname;
									$record 	 		= new Records($emp_biometricID, $date, $date);
									$logs 	 	 		= $record->all_datelogs();

									if (empty($logs)) {
										continue;
									}

									$log_value 		= $logs[$date];

									$log_data       = $record->get_work_hours($log_value, $date);
									$ot_data        = $record->get_ot_hours($log_value, $date);
									$schedule_date  = $record->get_user_schedule_date($emp_biometricID, $date);

									$log_late       = unix_to_hour($log_data['late']);
									$log_time_spent = unix_to_hour($log_data['time_consume']);
									$log_under_time = unix_to_hour($log_data['under_time']);
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
									$ftimeIn    = '';
									$ftimeOut   = '';
									$stimeIn    = '';
									$stimeOut   = '';
									$OTin       = '';
									$OTout      = '';
							?>
									<tr>
										<td class="fullname"><?php echo $fullname; ?></td>
										<td><?php echo $schedule_name; ?></td>
										<td id="<?php echo $log_value['ftimeIn']['id']; ?>" class="<?php echo $mark_ftimeIn; ?>">
											<?php if (!empty($log_value['ftimeIn']['time'])) : $ftimeIn = pcm_unix_to_time($log_value['ftimeIn']['time']); ?>
												<a href="#" class="update" data-toggle="modal" data-target="#updateLogModal" data-sched="ftimeIn" data-date="<?php echo $date; ?>" data-time="<?php echo get_log_time($log_value['ftimeIn']['time']); ?>" data-id="<?php echo $log_value['ftimeIn']['id']; ?>" data-comment="<?php echo $log_value['ftimeIn']['comment']; ?>"><i class="fa fa-fw fa-edit"></i></a>
												<?php if ($log_value['ftimeIn']['absent'] == '1') : ?>
													<span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $log_value['ftimeIn']['comment']; ?>"><?php echo 'Absent: ' . $log_value['ftimeIn']['comment']; ?>
													<?php else : ?>
														<?php if (empty($log_value['ftimeIn']['comment'])) : ?>
															<?php echo $ftimeIn; ?>
														<?php else : ?>
															<span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $log_value['ftimeIn']['comment']; ?>"><?php echo $ftimeIn; ?>
															<?php endif; ?>
														<?php endif; ?>
														<a href="#" class="delete-log text-danger"><i class="fa fa-fw fa-trash"></i></a>
													<?php else : ?>
														<a href="#" class="add-sched-log text-center text-success" data-toggle="modal" data-target="#addDateLogModal" data-date="<?php echo $date; ?>" data-sched="ftimeIn" data-id="<?php echo $emp_biometricID; ?>"><i class="fa fas fa-calendar"></i>
														<?php endif; ?>
										</td>
										<td id="<?php echo $log_value['ftimeOut']['id']; ?>" class="<?php echo $mark_ftimeOut; ?>">
											<?php if (!empty($log_value['ftimeOut']['time'])) : $ftimeOut = pcm_unix_to_time($log_value['ftimeOut']['time']); ?>
												<a href="#" class="update" data-toggle="modal" data-target="#updateLogModal" data-sched="ftimeOut" data-date="<?php echo $date; ?>" data-time="<?php echo get_log_time($log_value['ftimeOut']['time']); ?>" data-id="<?php echo $log_value['ftimeOut']['id']; ?>" data-comment="<?php echo $log_value['ftimeOut']['comment'] ?>"><i class="fa fa-fw fa-edit"></i></a>
												<?php if ($log_value['ftimeOut']['absent'] == '1') : ?>
													<span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $log_value['ftimeOut']['comment']; ?>"><?php echo 'Absent: ' . $log_value['ftimeOut']['comment']; ?>
													<?php else : ?>
														<?php if (empty($log_value['ftimeOut']['comment'])) : ?>
															<?php echo $ftimeOut; ?>
														<?php else : ?>
															<span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $log_value['ftimeOut']['comment']; ?>"><?php echo $ftimeOut; ?>
															<?php endif; ?>
														<?php endif; ?>
														<a href="#" class="delete-log text-danger"><i class="fa fa-fw fa-trash"></i></a>
													<?php else : ?>
														<a href="#" class="add-sched-log text-center text-success" data-toggle="modal" data-target="#addDateLogModal" data-date="<?php echo $date; ?>" data-sched="ftimeOut" data-id="<?php echo $emp_biometricID; ?>"><i class="fa fas fa-calendar"></i>
														<?php endif; ?>
										</td>
										<td id="<?php echo $log_value['stimeIn']['id']; ?>" class="<?php echo $mark_stimeIn; ?>">
											<?php if (!empty($log_value['stimeIn']['time'])) : $stimeIn = pcm_unix_to_time($log_value['stimeIn']['time']); ?>
												<a href="#" class="update" data-toggle="modal" data-target="#updateLogModal" data-sched="stimeIn" data-date="<?php echo $date; ?>" data-time="<?php echo get_log_time($log_value['stimeIn']['time']); ?>" data-id="<?php echo $log_value['stimeIn']['id']; ?>" data-comment="<?php echo $log_value['stimeIn']['comment'] ?>"><i class="fa fa-fw fa-edit"></i></a>
												<?php if ($log_value['stimeIn']['absent'] == '1') : ?>
													<span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $log_value['stimeIn']['comment']; ?>"><?php echo 'Absent: ' . $log_value['stimeIn']['comment']; ?>
													<?php else : ?>
														<?php if (empty($log_value['stimeIn']['comment'])) : ?>
															<?php echo $stimeIn; ?>
														<?php else : ?>
															<span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $log_value['stimeIn']['comment']; ?>"><?php echo $stimeIn; ?>
															<?php endif; ?>
														<?php endif; ?>
														<a href="#" class="delete-log text-danger"><i class="fa fa-fw fa-trash"></i></a>
													<?php else : ?>
														<a href="#" class="add-sched-log text-center text-success" data-toggle="modal" data-target="#addDateLogModal" data-date="<?php echo $date; ?>" data-sched="stimeIn" data-id="<?php echo $emp_biometricID; ?>"><i class="fa fas fa-calendar"></i>
														<?php endif; ?>
										</td>
										<td id="<?php echo $log_value['stimeOut']['id']; ?>" class="<?php echo $mark_stimeOut; ?>">
											<?php if (!empty($log_value['stimeOut']['time'])) : $stimeOut = pcm_unix_to_time($log_value['stimeOut']['time']); ?>
												<a href="#" class="update" data-toggle="modal" data-target="#updateLogModal" data-sched="stimeOut" data-date="<?php echo $date; ?>" data-time="<?php echo get_log_time($log_value['stimeOut']['time']); ?>" data-id="<?php echo $log_value['stimeOut']['id']; ?>" data-comment="<?php echo $log_value['stimeOut']['comment'] ?>"><i class="fa fa-fw fa-edit"></i></a>
												<?php if ($log_value['stimeOut']['absent'] == '1') : ?>
													<span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $log_value['stimeOut']['comment']; ?>"><?php echo 'Absent: ' . $log_value['stimeOut']['comment']; ?>
													<?php else : ?>
														<?php if (empty($log_value['stimeOut']['comment'])) : ?>
															<?php echo $stimeOut; ?>
														<?php else : ?>
															<span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $log_value['stimeOut']['comment']; ?>"><?php echo $stimeOut; ?>
															<?php endif; ?>
														<?php endif; ?>
														<a href="#" class="delete-log text-danger"><i class="fa fa-fw fa-trash"></i></a>
													<?php else : ?>
														<a href="#" class="add-sched-log text-center text-success" data-toggle="modal" data-target="#addDateLogModal" data-date="<?php echo $date; ?>" data-sched="stimeOut" data-id="<?php echo $emp_biometricID; ?>"><i class="fa fas fa-calendar"></i>
														<?php endif; ?>
										</td>
										<td id="<?php echo $log_value['OTin']['id']; ?>">
											<?php if (!empty($log_value['OTin']['time'])) : $OTin = pcm_unix_to_time($log_value['OTin']['time']); ?>
												<a href="#" class="update" data-toggle="modal" data-target="#updateLogModal" data-sched="OTin" data-date="<?php echo $date; ?>" data-time="<?php echo get_log_time($log_value['OTin']['time']); ?>" data-id="<?php echo $log_value['OTin']['id']; ?>" data-comment="<?php echo $log_value['OTin']['comment'] ?>"><i class="fa fa-fw fa-edit"></i></a>
												<?php if (empty($log_value['OTin']['comment'])) : ?>
													<?php echo $OTin; ?>
												<?php else : ?>
													<span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $log_value['OTin']['comment']; ?>"><?php echo $OTin; ?></span>
												<?php endif; ?>
												<a href="#" class="delete-log text-danger"><i class="fa fa-fw fa-trash"></i></a>
											<?php else : ?>
												<a href="#" class="add-sched-log text-center text-success" data-toggle="modal" data-target="#addDateLogModal" data-date="<?php echo $date; ?>" data-sched="OTin" data-id="<?php echo $emp_biometricID; ?>"><i class="fa fas fa-calendar"></i>
												<?php endif; ?>
										</td>
										<td id="<?php echo $log_value['OTout']['id']; ?>">
											<?php if (!empty($log_value['OTout']['time'])) : $OTout = pcm_unix_to_time($log_value['OTout']['time']); ?>
												<a href="#" class="update" data-toggle="modal" data-target="#updateLogModal" data-sched="OTout" data-date="<?php echo $date; ?>" data-time="<?php echo get_log_time($log_value['OTout']['time']); ?>" data-id="<?php echo $log_value['OTout']['id']; ?>" data-comment="<?php echo $log_value['OTout']['comment'] ?>"><i class="fa fa-fw fa-edit"></i></a>
												<?php if (empty($log_value['OTout']['comment'])) : ?>
													<?php echo $OTout; ?>
												<?php else : ?>
													<span class="text-info" data-toggle="tooltip" data-placement="top" title="<?php echo $log_value['OTout']['comment']; ?>"><?php echo $OTout; ?></span>
												<?php endif; ?>
												<a href="#" class="delete-log text-danger"><i class="fa fa-fw fa-trash"></i></a>
											<?php else : ?>
												<a href="#" class="add-sched-log text-center text-success" data-toggle="modal" data-target="#addDateLogModal" data-date="<?php echo $date; ?>" data-sched="OTout" data-id="<?php echo $emp_biometricID; ?>"><i class="fa fas fa-calendar"></i>
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
										<?php if ($_REQUEST['debug']) { ?> <td>
												<?php echo $log_under_time . '---' . $log_data['under_time'] . '---' //pcm_time_to_unix($log_data['log_out'])
													. $log_data['log_out']
													. '@@@' . $log_data['sched_out']; ?>
											</td>
										<?php
										}
										?>

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
							?>
								<tr>
									<td colspan="11">No record Found</td>
								</tr>
							<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div> <!-- User Leave -->
	</div>
</div>
<!-- Modal Pop up -->
<!-- Add new Time Log -->
<div class="modal fade" id="addLogModal" tabindex="-1" role="dialog" aria-labelledby="addLog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<form id="addlog-form">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-white" id="addLog">Add New Time Log</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="form-group col-md-12">
							<select class="form-control select2 select2-full" id="biometricID" name="biometricID" required="required">
								<option value="">Select Employee</option>
								<?php
								if (!empty($employees)) {
									foreach ($employees  as $employee) {
										if ($employee->access_level == 1) {
											continue;
										} //skip all admin accounts
								?><option value="<?php echo $employee->idd; ?>"><?php echo $employee->lname; ?>, <?php echo $employee->fname; ?></option><?php
																																																																				}
																																																																			} else {
																																																																					?><option>NO Registered Employee Found</option><?php
																																																																																												}
																																																																																													?>
							</select>
						</div>
						<div class="form-group col-md-12">
							<label for="date">Date</label>
							<input type="text" class="form-control datepicker" id="date" placeholder="yyyy-mm-dd" autocomplete="off">
						</div>
						<div class="form-group col-md-12">
							<label for="__absent">Absent</label>
							<input type="checkbox" id="__absent" />
						</div>
					</div>
					<div id="log-sections" class="row">
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
					<div id="reason-section" class="d-none row">
						<div class="form-group col-md-12">
							<label for="__absent_reason">Reason</label>
							<input type="text" class="form-control" id="__absent_reason" autocomplete="off" />
						</div>
					</div>
				</div>
				<div class="modal-footer pm-blue">
					<button type="button" class="btn btn-secondary _secondary" data-dismiss="modal">Close</button>
					<input type="submit" class="btn btn-danger _danger" value="Add Log">
				</div>
			</div>
		</form>
	</div>
</div>
<!-- Add new Date Log -->
<div class="modal fade" id="addDateLogModal" tabindex="-1" role="dialog" aria-labelledby="addLog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<form id="scheduleLog-form">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-white" id="addLog">Add Schedule Log</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6 mb-3">
							<label class="sr-only" for="schedDateLog">Date</label>
							<div class="input-group mb-2">
								<div class="input-group-prepend">
									<div class="input-group-text">Date</div>
								</div>
								<input type="text" class="form-control datepicker" id="schedDateLog" required="required" autocomplete="off">
							</div>
						</div>
						<div class="col-md-6 mb-3">
							<label class="sr-only" for="schedTimeLog">Time</label>
							<div class="input-group mb-2">
								<div class="input-group-prepend">
									<div class="input-group-text">Time</div>
								</div>
								<input type="text" class="form-control timepicker" id="schedTimeLog" required="required" autocomplete="off">
							</div>
						</div>
						<div class="col-md-12 mb-3">
							<label>Notes</label>
							<div class="input-group mb-2">
								<textarea name="notes" id="schedComment" class="form-control" rows="4" required="required" autocomplete="off"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary _secondary" data-dismiss="modal">Close</button>
					<input type="hidden" id="schedulelog" name="schedulelog" value="">
					<input type="hidden" id="biometricID" name="biometricID" value="">
					<input type="submit" class="btn btn-danger _danger" value="Add Schedule Log">
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
					<h5 class="modal-title text-white" id="updateLog">Update Log</h5>
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
						<div class="col-md-12 mb-3">
							<label>Notes</label>
							<div class="input-group mb-2">
								<textarea name="notes" id="comment" class="form-control" rows="4" required="required" autocomplete="off"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary _secondary" data-dismiss="modal">Close</button>
					<input type="hidden" id="logID" name="logID" value="">
					<input type="hidden" id="checkinSched" name="checkinSched" value="">
					<input type="submit" class="btn btn-danger _danger" value="Confirm">
				</div>
			</div>
		</form>
	</div>
</div>
<?php include_once('footer.php'); ?>