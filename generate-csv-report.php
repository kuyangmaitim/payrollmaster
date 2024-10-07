<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="'.$_POST['report_name'].'.csv"');
$report_data = $_POST['report_data'];
$action = $_POST['action'];
$headers = array();
if ($action == 'generate_thirteenth') {
	$headers = array('#','Employee\'s Name','No. Of Days','Daily Rate','Gross Salary','Deductions','Total Accumulated Income','13th Month Pay','Remaining SIL','Net Income');
} else {
	$headers = array('#','Employee\'s name','No. of Days','No. of Hours','Daily Rate','Initial Salary','Leave','Regular Holiday','Special Holiday','Premium','Overtime','Night Diff.','Adjustment','Gross Salary','SSS','HDMF','PhilHealth','Insurance','SSS LOAN','HDMF LOAN','Cash Advance','Absent','Late','Total Deductions','13th Month Pay','Net Income');

}
$formatted_values = array_map(function($arr){ return explode('!', $arr); },explode('&', $report_data));

$fp = fopen('php://output', 'w');
fputcsv($fp, $headers);
foreach ( $formatted_values as $value ) {
	fputcsv($fp, $value);
}
fclose($fp);