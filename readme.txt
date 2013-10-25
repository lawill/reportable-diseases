Reportable Diseases

---------------------
Web

www1.mayomedicallaboratories.com

/home/htdocs/mayome/dev_html/customer-service/reportable-diseases

overview.html
	initial landing page for reportable diseases. Provides link to index.php

index.php
	Allows selection of date range using calendar.inc.php and shows a list of available reports 	which is displayed using table.inc.php

calendar.inc.php
	Based on inclusion parameters will create a start date/end date table with javascript calendar 	functionality

table.inc.php
	Based on date range information, as well as optional usage of an account number and then 	generates a list of URLs that can be used to view data from the specified date range

authorizie.inc.php
	Currently utilized in all pages to gather authorized account information to be used in permission 	checks for reports. Lyle would like this code to removed from include and put back into each 	file.

date_check.php
	Checks $start_date and $end_date to make sure that they are date strings. Redirects to index.php 	in case of error.

generate-report.php
	Creates detailed report based on account number and date range as passed in through GET 	parameters. If all authorized accounts are to be used, 'all' is passed as the account parameter
		GET parameters:
			start_date
			end_date
			account

report-summary.php
	Creates summary report based on account number and date range as passed in through GET 	parameters. If all authorized accounts are to be used, 'all' is passed as the account parameter
		GET parameters:
			start_date
			end_date
			account


report-csv.php
	Creates CSV report based on account number and date range as passed in through GET 	parameters. If all authorized accounts are to be used, 'all' is passed as the account parameter
		GET parameters:
			start_date
			end_date
			account

header.inc.php
	Used to generate headings for accounts within reports. Used once for each account being used 	in Detailed and Summary reports

calendarview.js
	Utilized by calendar.inc.php to create popup calendar

prototype.js
	Used to create popup calendar. Other versions of prototype on the system but I have yet to find 	a copy that still allows the calendar to function

shield.jpg
	Image used for top of summary and detailed reports

calendarview.css
	CSS for calendar

calendar-32.gif
	Image used for calendar button in calendar.inc.php


Cron job

app1.mayomedicallaboratories.com

/home/cron/reportable-diseases

copy-to-mysql.php
	Copies new data from MSSQL table ReportableDiseases and puts it into MySQL db 	reportable_diseases so it can be used by the web portion

notifications.php
	Checks MySQL table “reportable_diseases_views” and sends a notification to every account 	that has not viewed a report in the number of days specified in the MySQL table 	“variable_table” 

