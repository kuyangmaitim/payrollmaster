<?php require_once('config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Payroll Master - Payroll Version <?php echo VERSION; ?></title>
  <!-- Favicon -->
  <link rel="shortcut icon" type="image/png" href="images/favicon.ico"/>
  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
   <!-- Table styles -->
   <link href="css/datatable-min.css" rel="stylesheet">
  <link href="css/select2-min.css" rel="stylesheet" />
  <!-- Custom fonts for this template-->
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
  <!-- styles for this template-->
  <link href="css/jquery.timepicker.css" rel="stylesheet">
  <!-- jQuery UI styles-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css">
  <!-- custom styles added by jaypee -->
  <link rel="stylesheet" href="css/custom-style.css">
 
  <?php
  if( $page_file == 'personal-information-template.php' || $page_file == 'files-memo.php' || $page_file == 'loans-leave.php' || $page_file == 'update-user.php' || $page_file == 'holiday.php' || $page_file == 'profile.php' || $page_file == 'employee-schedules.php' ){
    ?>
    <link href="css/fullcalendar.min.css" rel="stylesheet">
    <?php
  }
  if( $page_file == 'profile.php' || $page_file == 'settings.php' || $page_file == 'update-user.php' || $page_file == 'personal-info.php'){
    ?>
    <link href="css/croppie.css" rel="stylesheet">
    <?php
  }
  if( ( $page_file == 'personal-information-template.php' || $page_file == 'files-memo.php' || $page_file == 'loans-leave.php' || $page_file == 'users.php' || $page_file == 'update-user.php' || $page_file == 'profile.php' || $page_file == 'uploads.php' ) && ACCOUNT_TYPE < 3 ){
    ?><link href="css/dropzone.css" rel="stylesheet"><?php
  }
  if( $page_file == 'add-document.php' ){
    ?><link href="css/summernote-lite.css" rel="stylesheet"><?php
  }
  if( $page_file == 'signature.php' ){
    ?><link href="css/signature-pad.css" rel="stylesheet"><?php
  }
  ?>
  <link href="css/style.css" rel="stylesheet">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="https://f001.backblazeb2.com/file/buonzz-assets/jquery.ph-locations-v1.0.0.js"></script>
  <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <?php
  $current_user_info =  $employee->get_user( $sessionUserId );
  ?>
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="index.php">Welcome! <?php echo $current_user_info->fname; ?> <?php echo $current_user_info->lname; ?></a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <?php include_once('navbar-sidenav.php'); ?>
      <?php include_once('navbar-topnav.php'); ?>
    </div> <!-- navbarResponsive -->
  </nav>
<div class="content-wrapper">