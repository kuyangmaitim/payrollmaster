<?php include_once('header.php'); ?>
<?php
  $timesheet_name   = "timesheet-template.csv";
  $csv_file         = fopen($timesheet_name, "w");
  fputcsv( $csv_file, array('Biometric ID', 'Time In', 'Time Out', 'Time In', 'Time Out', 'OT In', 'OT Out' ) );
  fclose($csv_file);
?>
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Import Timesheets</li>
  </ol>
  <!-- Icon Cards-->
  <div id="import-form-wrapper" class="row">
      <div class="container">
        <div id="import-timecard-wrapper" class="col-md-8 col-md-offset-2">
          <h3>Import Timesheets</h3>
          <form id="import-timecard" method="POST" enctype="multipart/form-data">
            <!-- COMPONENT START -->
            <div class="form-group">
              <input id="uploadImage" type="file" accept="timecard/*" name="timecard" required="required" />
              <input type="hidden" name="import-timecard" value="2">
            </div>
            <div class="form-group">
              <label for="serial-number" class="col-form-label">Select Device Serial Number</label>
              <select id="serial-number" class="form-control" name="serial-number" required="required">
                <option value="">-- Serial Number --</option>
                <?php if( !empty( pcm_get_device_list() ) ): foreach ( pcm_get_device_list() as $key => $value ): ?>
                  <option value="<?php echo $value->id; ?>"><?php echo $value->serial_number; ?></option>
                <?php endforeach; endif; ?>
              </select>
            </div>
            <div class="form-group">
              <input class="btn btn-success pm-blue" type="submit" name="submit" value="Import CSV">
            </div>
          </form>
          <p class="mt-4 text-danger">Note: This only accepts .csv file from your device. Please download <a id="download-csv-timesheet" href="#">Timesheet</a> template. Time log must be YYYY/MM/DD HH:MM:SS (24 Hours) format</p>
        </div>
      </div>
  </div><!-- import-form-wrapper -->
</div><!-- .container-fluid -->    

<?php include_once('footer.php'); ?>
<script>
  jQuery(document).ready(function($) {
    $('#download-csv-timesheet').on('click', function(e){
      e.preventDefault();
      window.location='<?php echo $timesheet_name; ?>';
    });
  });
</script>