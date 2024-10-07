<?php include_once('header.php'); ?>
<?php
$files          = new EmpFiles;
$memo           = new Memo;
$employee       = new Employee;
$current_page   = isset( $_GET['current'] ) ? (int)$_GET['current'] : 1;
$per_page       = 36;
$all_files      = $files->all( $current_page, $per_page );
$paginator      = new Paginator( $files->record_count( ), $per_page );
$user_data      = $employee->get_user( pa_get_current_user() );
$all_employees  = $employee->get_all_employees( array( 'id', 'fname', 'lname', 'idd' ) );

?>
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">All Uploads</li>
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
              Upload List <a href="#" class="upload-files btn btn-info btn-sm float-right" data-toggle="modal" data-target="#fileManagerModal"><i class="fa fa-fw fa-folder-open text-white"></i></a>
            </div> <!-- card Header -->
              <section class="row my-4 mx-2">
                  <div class="col-sm-12">
                      <table id="user-file-list" class="table searchable-table">  
                          <thead>
                          <tr>
                              <th scope="col">Name</th>
                              <th scope="col">Date Upload</th>
                              <th scope="col">Uploaded By</th>
                              <th scope="col">Assigned To</th>
                              <th scope="col">Type</th>
                              <th scope="col">Action</th>
                          </tr>
                          </thead>
                          <tbody>
                          <?php if( !empty( $all_files ) ): ?>
                              <?php foreach( $all_files as $_file ): ?>
                                  <?php
                                  $uploaded_by        = $_file->uploaded_by ? $employee->get_user_data_by_id( $_file->uploaded_by, 'lname' ) .', '.$employee->get_user_data_by_id( $_file->uploaded_by, 'fname' ) : '-' ;
                                  $assigned_to        = $_file->uid ? $employee->get_user_data_by_id( $_file->uid, 'lname' ) .', '.$employee->get_user_data_by_id( $_file->uid, 'fname' ) : '-' ;
                                  $file_name          = $_file->name ? $_file->name : '' ;
                                  $file_desc          = $_file->description ? '<i class="fa fa-info-circle text-info ml-3" data-toggle="tooltip" data-placement="top" title="'.$_file->description.'"></i>' : '';
                                  ?>
                                  <tr id="file-<?php echo $_file->id; ?>">
                                      <td><?php echo $file_name.$file_desc; ?> </td>
                                      <td ><?php echo $_file->upload_date; ?></td>
                                      <td ><?php echo $uploaded_by; ?></td>
                                      <td ><?php echo $assigned_to; ?></td>
                                      <td ><?php echo $_file->type; ?></td>
                                      <td>
                                          <i class="download-file fa fa-download mr-3 text-primary" data-id="<?php echo $_file->id; ?>" aria-hidden="true"></i>
                                          <i class="delete-file fa fa-trash mr-3 text-danger" data-id="<?php echo $_file->id; ?>" aria-hidden="true"></i>
                                      </td>
                                  </tr>
                              <?php endforeach; ?>
                          <?php else: ?>
                              <tr><td colspan="4">No files found.</td></tr>
                          <?php endif; ?>
                          </tbody>
                      </table>
                      <nav aria-label="Documents Page navigation">

                          <?php  $paginator->paginate(); ?>

                      </nav>
                  </div>
              </section>
          </div>
          <?php endif; ?>
        </div> <!-- Content wrapper -->
      </div> <!-- Row Wrapper -->
    </div>  
  </div>
</div><!-- /.container-fluid-->
<!-- Modal File Manager-->
<div id="fileManagerModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fileManagerModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-white" id="fileManagerModalLabel"><i class="fa fa-fw fa-folder-open"></i> File Manager</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">
        <section class="row">
            <div class="col-md-6">
                <form action="<?php echo $siteHostAdmin; ?>includes/common/ajax-handler.php" class="dropzone needsclick dz-clickable" id="filemanagerdropzoneform">
                    <div class="dz-message needsclick">
                        Drop files here or click to upload.<br/>
                        <span style="font-size:12px;">Accepted file type : <?php echo implode(', ', upload_accepted_filetype() ); ?><br/>
                        Max Filesize: 4MB.</span>
                    </div>
                    <input type='hidden' name='action' value='submit_dropzonejs'>
                    <input type='hidden' name='dirname' value=''>
                </form>
            </div>
            <div class="col-md-6">
            <form id="filemanager-form">
              <div class="form-group">
                <label class="sr-only" for="user_id">Assigned To</label>
                <select class="form-control select2 select2-full" id="user_id" name="user_id" required="required">
					<?php
					if( !empty( $all_employees  ) ){
                        ?><option value="">Select Employee</option><?php
						foreach ($all_employees  as $employee ) {
							?><option value="<?php echo $employee->id; ?>" ><?php echo $employee->lname; ?>, <?php echo $employee->fname; ?></option><?php
						}
					}else{
					    ?><option >NO Registered Employee Found</option><?php
					}
					?>
				</select>
			  </div>
              <div class="form-group">
                <label class="sr-only" for="filename">Filename</label>
                <input type="text" class="form-control" id="filename" aria-describedby="emailHelp" placeholder="Filename" required>
              </div>
              <div class="form-group">
                <label class="sr-only" for="description">File Description</label>
                <textarea class="form-control" id="description" rows="3" placeholder="File Description"></textarea>
              </div>
              <input type='hidden' name='file_id' value="">
              <button type="submit" class="btn btn-primary">Save File</button>
            </form>
            </div>
        </section>
      </div>
    </div>
  </div>
</div>
<?php include_once('footer.php'); ?>