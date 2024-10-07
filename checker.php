<?php 
include_once('header.php'); 
$employee =  new Employee;
$settings =  new Settings;
$holiday  =  new Holiday;

$username = $_GET['username'];
$password = $_GET['password'];
echo 'AUTHENTICATE: '.$employee->authenticate_user( $username, $password );

?>