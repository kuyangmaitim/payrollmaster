<?php
$emp_name = $_POST['emp_name'];
$emp_bid  = $_POST['emp_bid'];
$emp_log  = unserialize( $_POST['emp_log'] );
$filename = $emp_name.'-'.$emp_bid;
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="'.$filename.'.csv"');
$headers = array('Date','Schedule','Login (AM)','Logout (AM)','Login (PM)','Logout (PM)','OT In','OT Out');
$fp = fopen('php://output', 'w');
fputcsv($fp, $headers);
if( !empty( $emp_log ) ) {
  foreach( $emp_log as $key => $value ) {
    fputcsv($fp, $value);
  }
}
fclose($fp);