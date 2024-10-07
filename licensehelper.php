<?php include_once('header.php');
$settings      = new Settings;
$id            = $settings->get_settings_by_name( 'license_key', true );
$data          = $settings->get_settings_data( $id, true ) ? $settings->get_settings_data( $id, true ) :'';
?>
<div class= "col-md-12 wptf-section">
    <h4 class = "text-center">PayrollMaster License Helper</h4>
    <table style="width:95%;" class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th scope="col" class = "text-center" id="paycheck_status">LICENSE KEY</th>
                <th scope="col" class = "text-center" id="paycheck_button">ACTION</th>
            </tr>
        </thead>
        <tbody>
            <form id="activate-license" action="" method="POST">
                <tr>
                    <td class = "text-center">
                        <input style = "width:100%;" type = "text" id = "paycheckcloud_licensekey" name = "paycheckcloud_licensekey" value = "<?php echo $data; ?>">
                    </td>
                    <td class = "text-center">
                        <input type="hidden" name="addon-name" id="addon-name" value = "Payroll Master"/>
                        <?php if( empty( $data ) ):?>
                            <input type="submit" class="activate button button-primary" name="activate" value = "Activate"/>
                        <?php else: ?>
                            <input type="submit" class="deactivate button button-primary" name="activate" value = "Deactivate"/>
                        <?php endif; ?>
                    </td>
                </tr>
            </form>
        </tbody>
    </table>
</div>
<?php include_once('footer.php'); ?>