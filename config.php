<?php
session_start();
define('ABSPATH', __DIR__);
require_once( ABSPATH.'/includes/defined.php' );
require_once( ABSPATH.'/includes/server.php' );
$sessionUserId 	= 0;
$employee 		 = new Employee;
$siteHostAdmin .= 'demo/';
// $qualification = new Qualification;
if( !isset( $_SESSION["paycheckUserID"] ) ){
	header('Location: '.$siteHostAdmin.'login.php' );
}else{
	$sessionUserId = $_SESSION["paycheckUserID"];
}
if( isset( $_GET['logout'] ) ){
	generate_user_log('Logged out.');
	unset($_COOKIE['paycheck_master']);
	session_start();
	session_unset();
	session_destroy();
	header('Location: '.$siteHostAdmin.'login.php' );
}


if( $page_file == 'update-user.php' ){
	if( !isset( $_GET['uid'] ) ){
		header('Location: '.$siteHostAdmin.'404.php' );
        exit;
	}
	if( isset( $_GET['uid'] ) ){
		$uid 		= $employee->get_user_data( intval( $_GET['uid'] ) );
		if( !$uid ){
			header('Location: '.$siteHostAdmin.'404.php' );
            exit;
		}
	}
}

$user_access = pcm_user_access( $sessionUserId );

if( !in_array( $page_file, $user_access ) && $page_file != 'ajax-handler.php' ){
	header('Location: '.$siteHostAdmin.'404.php' );
}
new PDF_Maker( $user_access );