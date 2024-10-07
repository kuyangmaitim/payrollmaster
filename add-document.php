<?php include_once('header.php'); ?>
<?php
$employee     = new Employee;
$employees 	  = $employee->get_all_employees( array( 'id', 'fname', 'lname', 'idd' ) );
?>
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Employees</li>
  </ol>
  <!-- Icon Cards-->
  <div class="row">
    <div class="col-12 mb-3">
      <div class="row">
        <div class="col-md-12 wptf-section">
          <?php if( ACCOUNT_TYPE == 3 ): ?>
            <?php include_once(ABSPATH.'/templates/ads.php'); ?>
          <?php else: ?>
          <div class="card mb-3">
            <div class="card-header">
              Add new document
            </div> <!-- card Header -->
            <form id="memo-editor-form" action="templates/process-memo.php" method="post">
              <div id="memo-editor" class="card-body">
                <div class="row">
                  <div class="col-md-8 offset-md-2 ">
                    <div class="form-group row my-4">
                      <label for="assigned_employee" class="col-sm-2 col-form-label">Assigned Employee</label>
                      <div class="col-sm-8">
                        <select class="form-control select2 select2-full" id="assigned_employee" name="assigned_employee" required="required">
                          <option value="">Select Employee</option>
                          <?php
                          if( !empty( $employees  ) ){
                            foreach ($employees  as $employee ) {
                              ?><option value="<?php echo $employee->id; ?>" ><?php echo $employee->lname; ?>, <?php echo $employee->fname; ?></option><?php
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row my-4">
                      <label for="subject" class="col-sm-2 col-form-label">Subject</label>
                      <div class="col-sm-8">
                        <input type="text" name="subject" class="form-control" id="subject" placeholder="Document Subject" required="required">
                      </div>
                    </div>
                    <div class="form-group row my-4">
                      <label for="optTemplate" class="col-sm-2 col-form-label">Template</label>
                      <div class="col-sm-8">
                        <select class="form-control" id="optTemplate">
                          <option value="">Select template</option>
                          <option value="memo1">Template 1</option>
                          <option value="memo2">Template 2</option>
                          <option value="memo3">Template 3</option>
                        </select>
                      </div>
                    </div>
                    <div id="editor-wrapper" class="my-4">
                      <textarea id="summernote" name="summernote" required="required"></textarea>         
                    </div> <!-- #summernote -->
                    <input type="submit" class="btn btn-primary float-right" name="save_document" value="Save Document">
                  </div>
                </div>
              </div> <!-- #memo-editor -->
            </form>
          </div>
          <?php endif; ?>
        </div> <!-- Content wrapper -->
      </div> <!-- Row Wrapper -->
    </div>  
  </div>
</div><!-- /.container-fluid-->

<?php include_once('footer.php'); ?>
<script>
  jQuery(document).ready(function($){
    <?php if( isset( $_GET['submit'] ) && $_GET['submit'] == 'true' ): ?>
      <?php if( isset( $_GET['status'] ) && $_GET['status'] ): ?>
        $('body').prepend('<div class="notification-message alert alert-success">Memo has been successfully saved!</div>');
      <?php else: ?>
        $('body').prepend('<div class="notification-message alert alert-danger">Something went wrong while processing your request. Please try again.</div>');
      <?php endif; ?>
      setTimeout(function(){
        $('body .notification-message').remove();
      }, 3000);
    <?php endif; ?>
    
  });
</script>