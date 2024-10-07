<?php 
    require_once('config.php'); 
    $memo         = new Memo;
    $employee     = new Employee;
    if( !isset( $_GET['sign'] ) && $_GET['sign'] != '1' && !isset( $_GET['id'] ) ){
        header('Location: '.$siteHostAdmin.'404.php' );
    }
    $memo_details =  $memo->details( (int)$_GET['id'] );
    if( empty( $memo_details ) && $memo_details->assigned_to != pa_get_current_user() ){
        header('Location: '.$siteHostAdmin.'404.php' );
    }
    $user_data      = $employee->get_user( intval( pa_get_current_user() ) );
    $signature_url  = get_signature_url( $memo_details->signature );
?>
<?php include_once('header.php'); ?>

<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Signature</li>
  </ol>
  <!-- Icon Cards-->
  <div class="row">
    <div class="col-12 mb-3">
      <div class="row">
        <div class="col-md-12 wptf-section">
          <div class="card mb-3">
            <div class="card-header">
                Signature
            </div> <!-- card Header -->
            <div class="card-body" style="background-color: #d2d2d2;">
              <div class="memo col-lg-8 offset-lg-2" style="background-color: #fff;">
                <div class="memo-wrap p-4"> 
                  <?php echo $memo_details->content; ?>
                  <?php require_once('./templates/memo/signature.tpl.php');  ?>
                </div> <!-- memo -->
                <div id="signature-wrapper" class="mt-4 py-4 border-top">
                  <div id="signature-pad" class="signature-pad" style="width:100%;height:364px;margin: 0 auto;" >
                    <div class="signature-pad--body">
                      <canvas></canvas>
                    </div>
                    <div class="signature-pad--footer">
                      <div class="description">Sign above</div>
                      <div class="signature-pad--actions">
                        <div>
                          <button type="button" class="btn btn-sm btn-danger clear" data-action="clear">Clear</button>
                        </div>
                        <div>
                          <button type="button" class="btn btn-sm btn-primary save" data-action="save-svg" data-id="<?php echo $memo_details->id; ?>">Submit</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div> <!-- Signature -->      
              </div>
            </div> <!-- Card Body -->
          </div>
        </div> <!-- Content wrapper -->
      </div> <!-- Row Wrapper -->
    </div>  
  </div>
</div><!-- /.container-fluid-->
<?php include_once('footer.php'); ?>