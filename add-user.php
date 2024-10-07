<?php include_once('header.php'); ?>
<?php
  $settings =  new Settings;
?>
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Add New Employee</li>
  </ol>
  <!-- Icon Cards-->
  <div id="adduser-form" class="row">
    <div class="form-group col-12">
      <div class="card">
        <div class="card-header" style="color: #FF0000;">
          Note: Please put N/A if the field is not applicable to you.
        </div>
      </div>
    </div>
    <div class="col-12">
      <form id="add-employee" autocomplete="off">
        <div class="row">
          <div id="personal-info" class="col-md-6 wptf-section">
            <?php include_once(ABSPATH.'/templates/add-user/personal-info.php'); ?>
            <?php include_once(ABSPATH.'/templates/add-user/contact-info.php'); ?>
            <?php include_once(ABSPATH.'/templates/add-user/additional-contact-info.php'); ?>
            <?php include_once(ABSPATH.'/templates/add-user/assignment-info.php'); ?>
          </div> <!-- personal-info -->
          <div id="additional-info" class="col-md-6 wptf-section">
            <?php include_once(ABSPATH.'/templates/add-user/salary-info.php'); ?>
            <?php include_once(ABSPATH.'/templates/add-user/permanent-add-info.php'); ?>
            <?php include_once(ABSPATH.'/templates/add-user/current-add-info.php'); ?>
            <?php include_once(ABSPATH.'/templates/add-user/deductions-info.php'); ?>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-12 mb-3">
            <button type="submit" class="btn btn-primary btn-lg ml-3 pm-blue">Add New Employee</button>
          </div>
        </div>    
      </form>
    </div>
  </div> <!-- adduser-form -->
</div><!-- /.container-fluid-->
<script>
  jQuery(document).ready(function() {
    $('#autofill').on('click', function() {
        let This = $(this);
        let bldg = $('#bldg');
        let bldg1 = $('#bldg1');
        let street = $('#street');
        let street1 = $('#street1');
        let barangay = $('#barangay');
        let barangay1 = $('#barangay1');
        let city = $('#city');
        let city1 = $('#city1');
        let zipcode = $('#zipcode');
        let zipcode1 = $('#zipcode1');
        let province = $('#province');
        let province1 = $('#province1');
        let region = $('#region');
        let region1 = $('#region1');
        let country = $('#country');
        let country1 = $('#country1');
        // let address = $('#full_address');
        // let address1 = $('#full_address1');
        if (This.is(':checked')) {
            if (bldg.val() === '' || street.val() === '' || barangay.val() === '' || city.val() === '' || zipcode.val() === '' || province.val() === '' || region.val() === '' || country.val() === '') {
                alert('Please fill in the fields in Present address section first.');
                This.prop('checked', false);
            } else {
                bldg1.val(bldg.val());
                street1.val(street.val());
                barangay1.val(barangay.val());
                city1.val(city.val());
                zipcode1.val(zipcode.val());
                province1.val(province.val());
                region1.val(region.val());
                country1.val(country.val());
                // address1.val(address.val());
                This.prop('checked', true);
            }
        } else {
            bldg1.val('');
            street1.val('');
            barangay1.val('');
            city1.val('');
            zipcode1.val('');
            province1.val('');
            region1.val('');
            country1.val('');
            // address1.val('');
        }
    });
  });
</script>
<?php include_once('footer.php'); ?>