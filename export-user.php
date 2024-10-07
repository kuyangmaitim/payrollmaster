<?php include_once('header.php'); ?>
<?php
$timesheet_name   = "employee-import-template.csv";
$csv_file         = fopen($timesheet_name, "w");
fputcsv(
  $csv_file,
  array_values(import_emprecord_template_header())
);
fclose($csv_file);
?>
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Import Employee</li>
  </ol>
  <!-- Icon Cards-->
  <div id="import-form-wrapper" class="row">
    <div class="container">
      <div id="import-emprecord-wrapper" class="col-md-8 col-md-offset-2">
        <h3>Import Employee</h3>
        <form id="import-emprecord" method="POST" enctype="multipart/form-data">
          <!-- COMPONENT START -->
          <div class="form-group">
            <input id="uploadImage" type="file" accept="emprecord/*" name="emprecord" required="required" />
            <input type="hidden" name="import-emprecord" value="1">
          </div>
          <div class="form-group">
            <input class="btn btn-success" type="submit" name="submit" value="Import CSV">
          </div>
        </form>
        <p class="mt-4 text-danger">Note: This only accepts .csv file from your device. Please download <a id="download-csv-emprecord" href="#">CSV Template</a>.</p>
      </div>
    </div>
  </div><!-- import-form-wrapper -->
</div><!-- .container-fluid -->
<?php include_once('footer.php'); ?>
<script>
  jQuery(document).ready(function($) {
    $('#download-csv-emprecord').on('click', function(e) {
      e.preventDefault();
      window.location = '<?php echo $timesheet_name; ?>';
    });
  });
</script>