<ul class="navbar-nav navbar-topnav ml-auto">
  <?php if ($current_user_info->access_level != 4) : ?>
    <li class="nav-item">
      <a href="https://join.skype.com/ybFopz6m3QXo" target="_blank" class="nav-link mr-lg-2">
        <i class="fa-sharp fa-solid fa-comment"></i>
        Chat
      </a>
    </li>
    <li class="nav-item">
      <a href="https://payrollmaster.ph/demo/licensehelper.php" class="nav-link mr-lg-2">
        <i class="fa fa-credit-card"></i>
        License Helper
      </a>
    </li>
  <?php endif; ?>
  <?php if (!empty(array_intersect(array('settings.php', 'options.php', 'holiday.php'), $user_access))) : ?>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle mr-lg-2" id="settingsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-fw fa-gears"></i>
        Settings
      </a>
      <div class="dropdown-menu" aria-labelledby="settingsDropdown">
        <?php if (in_array('settings.php', $user_access)) : ?>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="settings.php">
            <span class="text-info">
              <strong>
                <i class="fa fa-gears fa-fw"></i> General Settings</strong>
            </span>
          </a>
        <?php endif; ?>
        <?php if (in_array('options.php', $user_access)) : ?>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="options.php">
            <span class="text-info">
              <strong>
                <i class="fa fa-gears fa-fw"></i> Manage Options</strong>
            </span>
          </a>
        <?php endif; ?>
      </div>
    </li>
  <?php endif; ?>
  <?php if (!empty(array_intersect(array('tools.php'), $user_access))) : ?>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle mr-lg-2" id="toolsDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-fw fa-wrench"></i>
        Tools
      </a>
      <div class="dropdown-menu" aria-labelledby="toolsDropdown">
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="import-timesheet.php">
          <span class="text-success">
            <strong>
              <i class="fa fa-upload fa-fw"></i> Import Timesheets</strong>
          </span>
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="import.php">
          <span class="text-success">
            <strong>
              <i class="fa fa-upload fa-fw"></i> Import Time Record (A6)</strong>
          </span>
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="import-user-2.php">
          <span class="text-success">
            <strong>
              <i class="fa fa-upload fa-fw"></i> Import Employee</strong>
          </span>
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#" id="download-employee">
          <span class="text-success">
            <strong>
              <i class="fa fa-download fa-fw"></i> Export All Employee</strong>
          </span>
        </a>
      </div>
    </li>
  <?php endif; ?>
  <?php if (!empty(array_intersect(array('tools.php'), $user_access))) : ?>
    <li class="nav-item dropdown">
      <a href="https://www.payrollmaster.ph/docs/" class="nav-link" target="_blank">
        <i class="fa fa-fw fa-lightbulb-o"></i>
        Helps
      </a>
    </li>
  <?php endif; ?>
  <li class="nav-item">
    <a style="display: inline-block;" class="nav-link" href="<?php echo $siteHostAdmin; ?>?logout=1"><i class="fa fa-fw fa-sign-out"></i>Logout</a><a href="profile.php"><img style="display: inline-block;" src="<?php echo pcm_get_avatar($sessionUserId); ?>" class="rounded-circle" alt="avatar" width="26"><a />
  </li>
</ul>