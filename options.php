<?php include_once('header.php'); ?>
<?php
  $options =  new Settings();
?>
<div class="container-fluid">
  <!-- Breadcrumbs-->
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="#">Dashboard</a>
    </li>
    <li class="breadcrumb-item active">Manage Options</li>
  </ol>
  <!-- Icon Cards-->
  <div id="settings-wrapper" class="row">
    <div class="col-12">
      <div class="row">
        <div class="col-md-4 mb-6 wptf-section">
          <div class="card mb-3">
            <div class="card-header">
              Designation
            </div> <!-- card Header -->
            <div class="card-body">
              <form id="designation-form" class="option-form">
                <div class="form-row">
                    <div class="form-group col-md-6">
                      <input type="text" class="form-control" id="designation" placeholder="Admin Department" name="designation" required="required">
                    </div>
                    <div class="form-group col-md-6">
                      <button type="submit" class="btn btn-primary pm-blue btn-lg">Create Designation</button>
                    </div>
                </div>
              </form> <!-- Designation Form -->
              <div id="designation-list" class="table-responsive"> 
                <table class="table table-bordered optionListTable" id="designationListTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Update</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Name</th>
                      <th>Update</th>
                      <th>Delete</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php
                    $designations = $options->get_settings_by_name('designation');
                    if( !empty( $designations ) ){
                      foreach ($designations as $designation ) {
                        $designationID   = $designation->id;
                        $designationName = $designation->value;
                        ?>
                        <tr id="sched-<?php echo $designationID; ?>">
                          <td class="name"><?php echo $designationName; ?></td>
                          <td style="text-align:center;">
                            <a class="update" data-id="<?php echo $designationID; ?>" data-toggle="modal" data-target="#updatedOptionModal"><span class="fa fa-fw fa-edit"></span></a>
                          </td>
                          <td style="text-align:center;">
                            <a class="delete" data-id="<?php echo $designationID; ?>" data-toggle="modal" data-target="#deleteOptionModal"><span style="color:red;" class="fa fa-fw fa-trash"></span></a>
                          </td>    
                        </tr>         
                        <?php
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div> <!--  Designation List -->
            </div> <!-- Card Body -->
          </div> <!-- card @ Schedule Form -->
        </div> <!-- wptf-section -->
        <div class="col-md-4 mb-6 wptf-section">
          <div class="card mb-3">
            <div class="card-header">
              Group
            </div> <!-- card Header -->
            <div class="card-body">
              <form id="group-form" class="option-form">
                <div class="form-row">
                    <div class="form-group col-md-6">
                      <input type="text" class="form-control" id="group" placeholder="Group ABC" name="group" required="required">
                    </div>
                    <div class="form-group col-md-6">
                      <button type="submit" class="btn btn-primary pm-blue btn-lg">Create Group</button>
                    </div>
                </div>
              </form> <!-- Group Form -->
              <div id="group-list" class="table-responsive"> 
                <table class="table table-bordered optionListTable" id="groupListTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Update</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Name</th>
                      <th>Update</th>
                      <th>Delete</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php
                    $groups = $options->get_settings_by_name('group');
                    if( !empty( $groups ) ){
                      foreach ($groups as $group ) {
                        $groupID   = $group->id;
                        $groupName = $group->value;
                        ?>
                        <tr id="sched-<?php echo $groupID; ?>">
                          <td class="name"><?php echo $groupName; ?></td>
                          <td style="text-align:center;">
                            <a class="update" data-id="<?php echo $groupID; ?>" data-toggle="modal" data-target="#updatedOptionModal"><span class="fa fa-fw fa-edit"></span></a>
                          </td>
                          <td style="text-align:center;">
                            <a class="delete" data-id="<?php echo $groupID; ?>" data-toggle="modal" data-target="#deleteOptionModal"><span style="color:red;" class="fa fa-fw fa-trash"></span></a>
                          </td>    
                        </tr>         
                        <?php
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div> <!--  Group List -->
            </div> <!-- Card Body -->
          </div> <!-- card @ Group Form -->
        </div> <!-- wptf-section -->
        <div class="col-md-4 mb-6 wptf-section">
          <div class="card mb-3">
            <div class="card-header">
              Team
            </div> <!-- card Header -->
            <div class="card-body">
              <form id="team-form" class="option-form">
                <div class="form-row">
                    <div class="form-group col-md-6">
                      <input type="text" class="form-control" id="team" placeholder="Team XYZ" name="team" required="required">
                    </div>
                    <div class="form-group col-md-6">
                      <button type="submit" class="btn btn-primary pm-blue btn-lg">Create Team</button>
                    </div>
                </div>
              </form> <!-- Group Form -->
              <div id="team-list" class="table-responsive"> 
                <table class="table table-bordered optionListTable" id="teamListTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Update</th>
                      <th>Delete</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Name</th>
                      <th>Update</th>
                      <th>Delete</th>
                    </tr>
                  </tfoot>
                  <tbody>
                    <?php
                    $teams = $options->get_settings_by_name('team');
                    if( !empty( $teams ) ){
                      foreach ($teams as $team ) {
                        $teamID   = $team->id;
                        $teamName = $team->value;
                        ?>
                        <tr id="sched-<?php echo $teamID; ?>">
                          <td class="name"><?php echo $teamName; ?></td>
                          <td style="text-align:center;">
                            <a class="update" data-id="<?php echo $teamID; ?>" data-toggle="modal" data-target="#updatedOptionModal"><span class="fa fa-fw fa-edit"></span></a>
                          </td>
                          <td style="text-align:center;">
                            <a class="delete" data-id="<?php echo $teamID; ?>" data-toggle="modal" data-target="#deleteOptionModal"><span style="color:red;" class="fa fa-fw fa-trash"></span></a>
                          </td>    
                        </tr>         
                        <?php
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div> <!--  Team List -->
            </div> <!-- Card Body -->
          </div> <!-- card @ Group Form -->
        </div> <!-- wptf-section -->
      </div>
    </div>
  </div> <!-- settings-wrappe -->
</div><!-- /.container-fluid-->
<!-- POP UP MOdal for Update Option -->
<!-- Modal -->
<div class="modal fade" id="updatedOptionModal" tabindex="-1" role="dialog" aria-labelledby="updatedOption" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="update-option-form">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white" id="updatedOption">Update Option</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="option_name">Option Name</label>
                  <input type="text" class="form-control" id="_option_name" placeholder="Option Name" name="option_name" required="required">
                </div>
            </div>      
        </div>
        <div class="modal-footer">
          <input type="hidden" id="optionID" name="optionID" value="">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary pm-blue btn-lg">Save Changes</button>
        </div>
      </div>
    </form> <!-- Schedule Form -->
  </div>
</div>
<!-- POP UP MOdal for Delete Option -->
<!-- Modal -->
<div class="modal fade" id="deleteOptionModal" tabindex="-1" role="dialog" aria-labelledby="deleteOption" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header alert-danger">
        <h5 class="modal-title text-white" id="deleteOption">Alert</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this Option</p>
        <p>This will affect Employee data who is assigned to this option.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" id="deleteOptionButton" data-id="">Confirm</button>
      </div>
    </div>
  </div>
</div>
<?php include_once('footer.php'); ?>