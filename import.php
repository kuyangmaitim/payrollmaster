<?php include_once('header.php'); ?>
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Import Time Record (A6)</li>
  </ol>
  <!-- Icon Cards-->
  <div id="import-form-wrapper" class="row">
      <div class="container">
        <div id="import-timecard-wrapper" class="col-md-8 col-md-offset-2">
          <h3>Import Time Record (A6)</h3>
          <form id="import-timecard" method="POST" enctype="multipart/form-data">
            <!-- COMPONENT START -->
            <div class="form-group">
              <input id="uploadImage" type="file" accept="timecard/*" name="timecard" required="required" />
              <input type="hidden" name="import-timecard" value="1">
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
          <p class="m-4">Note: This only accepts .txt file from your device.</p>
        </div>
      </div>
  </div><!-- import-form-wrapper -->
</div><!-- .container-fluid -->    
<?php include_once('footer.php'); ?>