<?php include_once('header.php'); ?>
<?php
$memo           = new Memo;
$employee       = new Employee;
$current_page   = isset( $_GET['current'] ) ? (int)$_GET['current'] : 1;
$per_page       = 36;
$all_memos      = $memo->all( $current_page, $per_page );
$paginator      = new Paginator( $memo->record_count( ), $per_page );
?>
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">All Documents</li>
  </ol>
  <!-- Icon Cards-->
  <div class="row">
    <div class="col-12 mb-3">
      <?php if( ACCOUNT_TYPE == 3 ): ?>
        <?php include_once(ABSPATH.'/templates/ads.php'); ?>
      <?php else: ?>
      <div class="row">
        <div class="col-md-12 wptf-section">
          <div class="card mb-3">
            <div class="card-header">
              Document List <a href="/add-document.php" class="upload-files btn btn-info btn-sm float-right"><i class="fa fa-fw fa-file-text text-white"></i></a>
            </div> <!-- card Header -->
                <section class="row my-4 mx-2">
                    <div class="col-sm-12">
                        <table id="user-memo-list" class="table searchable-table">  
                            <thead>
                            <tr>
                                <th scope="col">Subject</th>
                                <th scope="col">Date Created</th>
                                <th scope="col">Created By</th>
                                <th scope="col">Assigned To</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if( !empty( $all_memos ) ): ?>
                                <?php foreach( $all_memos as $_memo ): ?>
                                    <?php
                                    $created_by     = $employee->get_user_data_by_id( $_memo->created_by, 'lname' ) .', '.$employee->get_user_data_by_id( $_memo->created_by, 'fname' ) ;
                                    $assigned_to    = $employee->get_user_data_by_id( $_memo->assigned_to, 'lname' ) .', '.$employee->get_user_data_by_id( $_memo->assigned_to, 'fname' ) ;
                                    ?>
                                    <tr id="memo-<?php echo $_memo->id; ?>">
                                        <td><?php echo $_memo->subject; ?></td>
                                        <td ><?php echo $_memo->created_date; ?></td>
                                        <td ><?php echo $created_by; ?></td>
                                        <td ><?php echo $assigned_to; ?></td>
                                        <td >
                                          <?php
                                            if( $_memo->signature ){
                                              ?><span class="text-success">Signed</span><?php
                                            }else{
                                              ?><span class="text-danger">Unsigned</span><?php
                                            }
                                          ?>
                                        </td>
                                        <td>
                                            <i class="download-memo fa fa-download mr-3 text-primary" data-id="<?php echo $_memo->id; ?>" aria-hidden="true"></i>
                                            <?php if( in_array( 'documents.php', pcm_user_access( pa_get_current_user() ) ) ): ?>
                                            <i class="delete-memo fa fa-trash mr-3 text-danger" data-id="<?php echo $_memo->id; ?>" aria-hidden="true"></i>
                                            <?php endif; ?>
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
        </div> <!-- Content wrapper -->
      </div> <!-- Row Wrapper -->
      <?php endif; ?>
    </div>  
  </div>
</div><!-- /.container-fluid-->
<?php include_once('footer.php'); ?>