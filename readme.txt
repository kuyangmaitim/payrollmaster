Payroll Master v4.3.0

===== Change Logs =====
* VERSION 3.4 *
1. Fixed computation for Rest Day Premium
2. Added new table column called " Night Diff. Hours" on Timecard.

* VERSION 3.5 *
1. Fixed the computation for Overtime if time in or out is between 12:00am and 6:00am
2. Fixed the computation for Night Differential

* VERSION 3.6 *
1. Fixed the issue where night difference returns negative value if overtime in and out is set between 12:00am and 6:00am

* VERSION 3.7 *
1. Fixed the computation for overtime and night differential if pay type is Monthly
2. Fixed the computation for overtime and night differential if pay type is Monthly Fixed
3. Revised the computation for 13th Month Report ( added SIL amount )

* VERSION 3.8 *
1. Included Premium Rest Day time consumed on total work hours
2. Displayed 5 decimals on hourly rate on generated payroll if Pay Type is set to Monthly in order to get the exact computation of initial salary
3. Removed the "Allowances" fields from Salary/Benefits Info when Adding/Updating an employee/user
4. Removed Maternity and Paternity Leave and Special Leave Benefits for Women on Adding/Updating Leave
5. Removed Double Special non Working and Special and Regular holiday on Adding/Updating holiday
6. Fixed the computation for the 13th month if the pay type is set to Monthly
7. Did some revisions on getting the exact available SIL count as well as the computation
8. Removed Pera and Rice Allowance from View Payrolls and Payslip and Distribution Report
9. Added columns for Premium on View Payrolls and Payslip and Distribution Report

* VERSION 3.9 *
1. Improved grammars and spellings of the entire system
2. Modified some columns of CSV, Payslip and Distribution reports for Payroll and 13th Month
3. Fixed Download Timelog functionality under Time Card section

* VERSION 4.0.0 *
1. Fixed the update functionality issue on Personal Information 1
2. Added "Night Diff. Hours" table column on Timelog report
3. Added "Accumulated Hours" on the very bottom of the table of the Timelog report
4. Fixed manual import of attendance

* VERSION 4.0.1 *
1. Added logo and changed bootstrap theme on login page.

* VERSION 4.0.2 *
1. Fixed login issues.

* VERSION 4.0.3 *

Additional Feature Update:

1. Bulk Add/Update Schedule on Admin Dashboard
- admin can now update/add schedule via dashboard

2. Set Holiday/Leave amounts to Daily Rate if checked in Settings
- admin can now toggle between Daily Rate and Actual Leave/Holiday amount when generating Payroll by checking "Actual Rate" on Settings page

3. Deduction Options
- admin can now decide on what deduction to apply every cutoff

* VERSION 4.0.4 *

Fix:

1. Schedule Saving/Update
- users can now add/update schedules without filling out Morning Time Out and Afternoon Time In

2. Timecard
- users can now see the exact work hours for Flexi Time ( only for AM IN and PM OUT )

* VERSION 4.1.0 *

Additional:

1. Schedule Saving/Update
- added the ability to set/update a color for every schedule

2. Settings Page
- removed the Schedule Manager from Settings page

3. Side Navigation
- added a new module called "Schedules" with submenus ( "All Schedule" and "Employee Schedules" ) on side navigation.

4. All Schedule and Employee Schedules Page
- All Schedules page allows the admin to Add/Update/Delete Schedules
- Employee Schedules page allows admin to view all employee schedules displayed on a calendar.


* VERSION 4.1.1 *

Changes:

1. Overall UI
- set default button colors to blue

2. Loans, Leave & Schedules Page
- reflected schedule color on calendar under "Loans, Leave & Schedule" page

3. Dashboard Page
- added table pagination on users list under "Dashboard" page

4. Report Payroll Page
- changed "Neg. Salary Adj." label to "Other Deductions"
- set holiday fields as editable on Payroll Generation

Fixes:

1. Timecard Page
- fixed the issue where work hours exceeds 8hrs for flexitime ( AM IN & PM OUT )

* VERSION 4.1.2 *

Revisions:
- revised the computation for rest day premium, overtime, and night difference

Additional:
- added a new section for night difference under settings page

* VERSION 4.1.3 *

Fixes:
- fixed calendar library issue on globe ISP
- fixed the issue where afternoon IN and OUT does not calculate the correct number of hours

Additional:
- added overtime amount setting under settings page

* VERSION 4.1.4 *

Revisions:
- revised the computations for overtime, night difference, and rest day premium.

* VERSION 4.1.4 *

Revisions:
- revised the computations for overtime, night difference, and rest day premium.

* VERSION 4.1.5 *

Additional:
- allowed half day for leaves
- added overall totals row on generated payroll

* VERSION 4.1.6 *

- removed GSIS Contributions from Generated Payroll, Payslip, and Distribution

Fixes:
- fixed leave credit functionality
- removed thirteenth month section from settings page

Additional:
- added logo on payslip

* VERSION 4.1.7 * 

- Added function  pcm_get_under_spent  to calculate  undertime  
- Fixed  calculation with under time  spent in total working hours 

* VERSION 4.1.8 * 

- Added auto  add OT IN and OT Out log once the overtime approve 
- Added  auto delete oce the overtime is dis approve

* VERSION 4.1.9 * 

- Fixed night diff issue for overtime

* VERSION 4.2.0 *

- Increased font sizes on distribution report
- Added biometric id on timelog output
- Added leave history on employee profile
- Fixed issue on getting user leaves ( skipped unapproved leaves )

* VERSION 4.2.1 *

- Added bulk update dayoff feature

* VERSION 4.2.2 *

- Fixed night diff functionality

* VERSION 4.2.3 *

- finished new import employee module
- adjusted font sizes on printable documents

* VERSION 4.3.0 *

- added Semi Monthly salary type
- upgraded Import Employee Module
- added absent option when adding logs
- fixed 13th Month Module(based on generated payroll)
- fixed issues when printing pdf reports on PHP 8.x
