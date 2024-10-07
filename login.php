<?php  
  session_start();
  define('ABSPATH', __DIR__);
  require_once( ABSPATH.'/includes/defined.php' );
  require_once( ABSPATH.'/includes/common/functions.php' );
  require_once( LIB_DIR.'class-employee.php' );

  $employee   = new Employee;
  $message    = '';
  $siteHostAdmin .= 'demo/';
  if( isset( $_POST['submit'] ) ){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $authenticate_user = $employee->authenticate_user( $username, $password );

    if( $authenticate_user ){
      generate_user_log('Logged in.', $authenticate_user );
      $employee->wp_generate_auth_cookie( $authenticate_user );
      
      header('Location: '.$siteHostAdmin );

    }else{
      $message = '<div class="payroll notification-message alert alert-danger" style="top:0;">Please check your username and password.</div>';
    }

  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" type="image/png" href="images/favicon.ico">
  <title>Payroll Master - Payroll Version <?php echo VERSION; ?></title>
  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
</head>
<?php echo $message; ?>
<body class="bg-light">  
  <div class="container">
    <div class="company-logo-wrapper ml-auto mr-auto mt-5 mb-5" style="width: fit-content;">
      <img src="./images/payroll-master-logo.png" alt="" style="width: 150px; height: 130px;" />
    </div>
    <div class="card card-login mx-auto mt-2">
      <div class="card-header">Login</div>
      <div class="card-body">
        <form id="paycheck-login" method="POST">
          <div class="col-md-12">
              <label class="sr-only" for="username">Username</label>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fa fa-fw fa-user"></i></div>
                </div>
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required="required">
              </div>
            </div>
            <div class="col-md-12">
              <label class="sr-only" for="password">Password</label>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <div class="input-group-text"><i class="fa fa-fw fa-key"></i></div>
                </div>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required="required">
              </div>
            </div>
          </div>
          <div class="col-md-12 mb-3">
            <button type="submit" class="btn btn-primary btn-lg btn-block pm-blue" name="submit">Login</button>
          </div>     
        </form>
        <div class="card-footer text-center">
          Version <?php echo VERSION; ?>
        </div>
      </div>
      
    </div>
  </div>
  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
</body>
</html>
