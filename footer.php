</div><!-- /.content-wrapper-->
  <footer class="sticky-footer">
    <div class="container">
      <div class="text-center">
        <small>Copyright © payrollmaster.ph <?php echo date('Y') . '. Version ' . VERSION . '.'; ?></small>
      </div>
    </div>
  </footer>
  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fa fa-angle-up"></i>
  </a>
  <!-- Logout Modal-->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-white" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <?php
  if( $page_file == 'add-document.php' ){
      ?><script src="js/jquery-slim.js"></script><?php
      ?><script src="js/summernote-lite.js"></script><?php
      ?><script src="js/document.js"></script><?php
  }
  ?>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <!-- Page level plugin JavaScript-->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js" integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
  <script src="vendor/datatables/jquery.dataTables.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin.min.js"></script>
  <!-- Custom scripts for this page-->
  <script src="js/sb-admin-datatables.min.js"></script>
  <!-- <script src="js/sb-admin-charts.min.js"></script> -->
  <!-- Jquery Datepicker scripts -->
  <script src="js/jquery-ui.js"></script>
  <script src="js/jquery-select2.js"></script>
  <script src="js/jquery.timepicker.js"></script>
  <script src="js/datepair.min.js"></script>
  <script src="js/fancyTable.js"></script>
  <script src="js/scripts.js"></script>
  <script src="js/settings-scripts.js"></script>
  <script src="js/timecard-scripts.js"></script>
  <script src="js/download.js"></script>
  <?php if( ( $page_file == 'attendance.php' || $page_file == 'leaves.php' ) && isset($_GET['act']) && strtolower($_GET['act']) == 'add' ): ?>
  <script>
	  jQuery(document).ready(function($){        
		  $('#addLogModal, #userLeaveApplicationModal').modal('show');
    }); 
  </script>
  <?php endif; ?>
  <?php
  if( $page_file == 'signature.php' ){
    ?>
    <script src="js/signature_pad.umd.js"></script>
    <script src="js/signature_app.js"></script>
    <?php
  }
  if( ( $page_file == 'users.php' || $page_file == 'files-memo' || $page_file == 'loans-leave.php' || $page_file == 'personal-info.php' || $page_file == 'profile.php' || $page_file == 'uploads.php' ) && ACCOUNT_TYPE < 3 ){
      ?><script src="js/dropzone.stln.js"></script><?php
      include_once('js/dropzone.custom-tpl.php');
  }
  if( $page_file == 'users.php' || $page_file == 'inactive-users.php' ){
    ?><script src="js/user.js"></script><?php
  }
  if( $page_file == 'profile.php' || $page_file == 'update-user.php' || $page_file == 'personal-info.php' ){
    ?>
    <script src="js/croppie.js"></script>
    <script src="js/profile.js"></script>
    <?php
  }
  if( $page_file == 'settings.php' ){
    ?>
    <script src="js/croppie.js"></script>
    <script src="js/setting-uploader.js"></script>
    <?php
  }
  if( $page_file == 'report-thirteenth-month.php' || $page_file == 'view-thirteenths.php' ){
    ?>
    <script src="js/thirteenth.js"></script>
    <?php
  }
  if( $page_file == 'update-user.php' || $page_file == 'files-memo.php' || $page_file == 'loans-leave.php' || $page_file == 'holiday.php' || $page_file == 'profile.php' || $page_file == 'employee-schedules.php'){
    ?>
    <script src="js/moment.min.js"></script>
    <script src="js/fullcalendar.min.js"></script>
    <script src="js/calendar.js"></script>
    <script type="text/javascript">
      jQuery(document).ready( function( $ ){
        // Add - Update Loan amortization
        $('.loan-amortization').on('submit', function( e ){
          e.preventDefault();
          var userID      = $(this).data('id');
          var loanAmount  = $(this).find('input[type="text"]').val();
          var loanName  = $(this).find('input[type="text"]').attr('name');
          if( loanName == 'sssLoan' ){
            var loanLabel = 'SSS';
          }else if( loanName == 'hmdfLoan' ){
            var loanLabel = 'HDMF';
          }else if( loanName == 'loan_policy' ){
            var loanLabel = 'Policy';
          }else if( loanName == 'loan_gsis' ){
            var loanLabel = 'GSIS Consol';
          }else if( loanName == 'ela_sos' ){
            var loanLabel = 'ELA/SOS/EL/CA ';
          }
          $.ajax({
              url: './includes/common/ajax-handler.php',
              type: "post",
              data: {
                  action : 'update-amortization',
                  userID : userID,
                  loanAmount : loanAmount, 
                  loanName : loanName,
              },
              beforeSend: function() {
                  $('body').append('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');
              },  
              success: function (response) {  
                  $( "body .lds-ellipsis" ).remove();
                  console.log('Response: '+response );
                  if( response >= 1 ){
                    $('body').prepend('<div class="notification-message alert alert-success">'+loanLabel+' Loan Amortization has been updated!</div>');          
                        
                  }else{
                     $('body').prepend('<div class="notification-message alert alert-danger">Something went wrong, Please reload the page and try again.</div>');
                  }  
                  setTimeout( function(){
                    $( "body .notification-message" ).remove();
                  }, 3000 );
              },
              error: function(jqXHR, textStatus, errorThrown) {
                alert('error');       
              }
            });
        });
        // Schedule Calendar
        console.log(<?php echo json_encode( $calendar_data ); ?>);
        $('#calendar-schedule').fullCalendar({
          dayClick: function(date, jsEvent, view) {
                  var workDate = date.format();
                  $('#userLeaveModal #selected-date').html( workDate );
                  $('#userLeaveModal #leaveDate').val( workDate );
                  $('#userLeaveModal #scheduleDate').val( workDate );
                  $('#userLeaveModal #user_leave').val( '' );
                  $('#userLeaveModal #schedule').val( 0 );
                  $( "#userLeaveModal" ).modal('toggle');
          },
          eventClick: function(calEvent, jsEvent, view) {
              var statDate  = calEvent.start._i;
              var type      = calEvent.type;
              var userID    = calEvent.uid;
              var statusID  = calEvent.id;
              var dataType  = calEvent.dataType;
              var status    = calEvent.status;
              var timeSchedule    = calEvent.timeSchedule;
              var am_pm    = calEvent.am_pm;
              if( dataType == 'leave' ){
                $('#updateUserLeave-form #user_leave').val( type );
                $('#updateUserLeave-form #am_pm').val( am_pm );
                $('#updateUserLeave-form #leaveID').val( statusID );
                $('#updateUserLeave-form #deleteLeave').attr( 'data-id', statusID );
                $('#updateUserLeave-form #selected-date').html( statDate );
                $('#updateUserLeave-form #leave-status').html( status );
                $( "#updateLeaveModal" ).modal('toggle');
              }else{
                $('#updateSchedule-form #schedule').val( type );
                $('#updateSchedule-form #scheduleID').val( statusID );
                $('#updateSchedule-form #deleteSchedule').attr( 'data-id', statusID );
                $('#updateSchedule-form #selected-date').html( statDate );
                $( "#updateScheduleModal" ).modal('toggle');
              }       
          },
          events : <?php echo json_encode( $calendar_data ); ?>,
          eventRender: function(event, element) {
              element.find('.fc-title'); 
          },
        });
        // HOliday Calendar
        $('#calendar-holiday').fullCalendar({
          header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,basicWeek,basicDay'
          },
          dayClick: function(date, jsEvent, view ) {
            var workDate = date.format();
            $('#addHolidayModal #selected-date').html( workDate );
            $('#addHolidayModal #holidayDate').val( workDate );
            $('#addHolidayModal #holiday_type').val( '' );
            $( "#addHolidayModal" ).modal('toggle');
          },
          eventClick: function(calEvent, jsEvent, view) {
              var statDate    = calEvent.start._i;
              var holidayType = calEvent.type;
              var holidayID    = calEvent.id;
              if( holidayType == 'leave'){
                $('#leaveInfoModal .leave-information').text( calEvent.title+ ' this ' +statDate );
                $( "#leaveInfoModal" ).modal('toggle');
              }else{
                $('#updateHoliday-form #holiday_type').val( holidayType );
                $('#updateHoliday-form #holidayID').val( holidayID );
                $('#updateHoliday-form #deleteHoliday').attr( 'data-id', holidayID );
                $('#updateHoliday-form #selected-date').html( statDate );
                $( "#updateHolidayModal" ).modal('toggle');
              }     
          },
          events : <?php echo json_encode( $declared_holiday ); ?>,
          eventRender: function(event, element) {
              element.find('.fc-title');
          },
        });
        let userDayOff = <?php echo json_encode( $day_off ); ?>;
        let daysOfTheWeek = {
          0: 'sunday',
          1: 'monday',
          2: 'tuesday',
          3: 'wednesday',
          4: 'thursday',
          5: 'friday',
          6: 'saturday',
        };
        // Employee Calendar
        $('#employee-calendar').fullCalendar({
          header: {
            left: 'prev,next today',
            right: 'title',
          },
          dayClick: function(date, jsEvent, view) {
            let textDate = date._d;
            let day = daysOfTheWeek[textDate.getDay()];
            if (moment().format('YYYY-MM-DD') === date.format('YYYY-MM-DD') || date.isAfter(moment())) {
              var workDate = date.format();
              $('#userApplicationModal #selected-date').html( workDate );
              $('#userApplicationModal #leaveDate').val( workDate );
              $('#userApplicationModal #overtimeDate').val( workDate );
              $('#userApplicationModal #time-start').val( '' );
              $('#userApplicationModal #time-end').val( '' );
              $('#userApplicationModal #notes').val( '' );
              $( "#userApplicationModal" ).modal('toggle');
            }
          },
          eventClick: function(calEvent, jsEvent, view) {
              var statDate  = calEvent.start._i;
              var type      = calEvent.type;
              var userID    = calEvent.uid;
              var statusID  = calEvent.id;
              var dataType  = calEvent.dataType;
              var status    = calEvent.status;
              var timeSchedule    = calEvent.timeSchedule;

              if( dataType == 'leave' ){
                $('#view-user-leave #user_leave').val( type );
                $('#view-user-leave #selected-date').html( statDate );
                $('#view-user-leave #status').val( status );                
                $( "#viewLeaveModal" ).modal('toggle');
              }else{
                $('#view-user-schedule #schedule').val( type );
                $('#view-user-schedule #time').val( timeSchedule );
                $('#view-user-schedule #selected-date').html( statDate );
                $( "#viewScheduleModal" ).modal('toggle');
              }       
          },
          events : <?php echo json_encode( $calendar_data ); ?>,
          eventRender: function(event, element) {
              element.find('.fc-title'); 
          },
        });
        $('#user-memo-list, #user-file-list, #leave-histoy-table').fancyTable({
          sortColumn:0,
          pagination: true,
          perPage:6,
          searchable:false,
          globalSearch:false
        });
      });
    </script>
    <?php
  }
  if ( $page_file == 'profile.php' || $page_file == 'licensehelper.php' || $page_file == 'update-user.php' || $page_file == 'personal-info.php' || $page_file == 'family.php' || $page_file == 'employee-history.php' || $page_file == 'qualifications.php' || $page_file == 'personal-info.php' || $page_file == 'salary-benefits.php' || $page_file == 'loans-leave.php' || $page_file == 'contract-terms.php' || $page_file == 'resignation.php' ) {
    ?>
      <script type="text/javascript" id="select-twos">
        $(document).ready(function() {
          // select 2
          setTimeout(() => {
            $('.custom-select-2').select2();
          }, 300)
        });
      </script>
      <script src="js/custom-ajax.js"></script>
    <?
  }
  ?>
  <?php
    if( $page_file == 'holiday.php' ){
      ?><script src="js/holiday-scripts.js"></script><?php
    }
    if( $page_file == 'import.php' || $page_file == 'import-timesheet.php' || $page_file == 'import-user.php' ){
      ?><script src="js/import.js"></script><?php
    }
  ?>
  <script src="js/required-indicator.js"></script>
</div>
</body>
</html>