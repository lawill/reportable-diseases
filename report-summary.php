<?php

$protect_from_forgery = true;
$page_permission = 'Regional Manager';
$specific_permission = 'Receive Reportable Disease Reports';
require('' . 'master.inc.php');
require('authorize.inc.php');

$account_where = array();
$account_where[] = "(Client_Account IN('" . implode("','", $authorized_accounts) . "'))";

/* This script geneartes a report summary based on a date range and an account number */

// Use the TCPDF library to generate output
require('/home/htdocs/mayome/dev_html/include/tcpdf/tcpdf.php');
require('/home/htdocs/mayome/dev_html/include/tcpdf/config/lang/eng.php');

//Since the header and footer are generated a page at a time, the total number of pages 
//needs to be calculated ahead of time for the page information in the footer
// Extend the TCPDF class to create custom header and footer functions
class REQ_PDF extends TCPDF {

    function Header() {
        
    }

    function Footer() {

        global $mnemonic, $test_name, $unit_code, $company_name, $acct_id_footer;
        //$page_number = $this->PageNo();


        $this->SetY(745);
        $this->SetFont('helvetica', '', 8);
        $this->Cell(500, 8, "If you have questions regarding the information contained on this page, please contact Mayo Medical Laboratories, Reportable Disease", "", 0, 'L', 0, 0, 1, 0, '', 'C');
        $this->ln(9);
        $this->Cell(500, 8, "Unit at 800-533-1710 extension: 8-7260.", "", 0, 'L', 0, 0, 1, 0, '', 'C');
        $this->ln(9);
        $this->SetFont('helvetica', '', 7);
        $this->Cell(500, 7, "Date Printed: " . date("m/d/Y", strtotime("-0 month")), "", 0, 'L', 0, 0, 1, 0, '', 'C');
        $this->ln(7);
        $this->Cell(500, 7, "MML Reportable Disease Report", "", 0, 'L', 0, 0, 1, 0, '', 'C');
    }

}

$account_number = $_GET['account_number'];
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];
require("date_check.php");
require('views_update.php');

$pdf = new REQ_PDF('Portrait', 'pt', 'Letter', true, 'UTF-8', false);
$pdf->SetMargins(48, 50, 70);
$pdf->SetAutoPageBreak(TRUE, 48);
$pdf->AddPage();
$pdf->SetLineWidth(0.5);



$pdf->Image('shield.jpg', 48, 50, 172, 54, 'JPG', '', '', true, 150, '', false, false, 0, false, false, false);

$pdf->ln(54);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(500, 14, "Reportable Disease Summary", '', 0, 'L', 0, 0, 1, 0, '', 'C');
$pdf->ln(14);
$pdf->Cell(500, 14, "$start_date to $end_date", '', 0, 'L', 0, 0, 1, 0, '', 'C');
$pdf->ln(30);

if ($account_number == "all") {
    $account_list_query = "SELECT DISTINCT Client_Account
      FROM reportable_diseases
      where Reported_Date >= '$start_date'
             and " . implode(' AND ', $account_where) . "
       and Reported_Date <= '$end_date'";
} else {
    $account_list_query = "SELECT DISTINCT Client_Account
      FROM reportable_diseases
      where Reported_Date >= '$start_date'
             and Client_Account = $account_number
       and Reported_Date <= '$end_date'";
}

$account_list_result = mysql_query($account_list_query, $invoice_db) or die(mysql_error($invoice_db));


while ($account_info = mysql_fetch_array($account_list_result)) {
    $account_number = $account_info['Client_Account'];
    $where = "AND Client_Account = '$account_number'";
    require('header.inc.php');

    $pdf->ln(10);

    $agency_name_query = "SELECT Distinct Agency_Name, Agency_State FROM reportable_diseases
      where Reported_Date >= '$start_date'
       and Reported_Date <= '$end_date'
      $where"
            . "ORDER BY Agency_Name asc";

    $agency_name_result = mysql_query($agency_name_query, $invoice_db);

    while ($agency_info = mysql_fetch_array($agency_name_result)) {
        $pdf->ln(16);
        $agency_name = $agency_info['Agency_Name'];
        $agency_state = $agency_info['Agency_State'];

        $pdf->SetFont('helvetica', 'B', 12);

        $pdf->Cell(350, 12, "$agency_name - $agency_state", 'B', 0, 'L', 0, 0, 1, 0, '', 'C');
        
        $test_name_result = mysql_query($test_name_query, $invoice_db);

        
        $test_select = "SELECT Test_Name, Count(*) as count from(
                SELECT Test_Name, Count(*) FROM reportable_diseases 
      where Reported_Date >= '$start_date'
       and Reported_Date <= '$end_date'
       and Agency_Name = '$agency_name'
      $where" . ""
                    . "GROUP BY Test_Name, Mayo_Order_Number) as a group by Test_Name Order by Test_Name asc";

            $test_result = mysql_query($test_select, $invoice_db);
        
        $test_name_query = "SELECT SUM(count) as count from ($test_select) as a";

        $test_name_result = mysql_query($test_name_query, $invoice_db);

        
        
        while ($test_info = mysql_fetch_array($test_name_result)) {


            $count = $test_info['count'];
            $total_tests = $total_tests + $count;

            $pdf->Cell(80, 12, "$count ", 'B', 0, 'R', 0, 0, 1, 0, '', 'C');
            $pdf->Cell(70, 12, "", 'B', 0, 'R', 0, 0, 1, 0, '', 'C');
            $pdf->ln(22);

            $pdf->SetFont('helvetica', '', 12); 
            

            while ($test_data = mysql_fetch_array($test_result)) {

                $pdf->Cell(350, 14, $test_data['Test_Name'], '', 0, 'L', 0, 0, 1, 0, '', 'C');
                $pdf->Cell(77, 12, $test_data['count'], '', 0, 'R', 0, 0, 1, 0, '', 'C');
                $pdf->Cell(73, 12, "", '', 0, 'R', 0, 0, 1, 0, '', 'C');
                $pdf->ln(12);
            }
        }
    }
}
$pdf->ln(12);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(350, 12, "Total", '', 0, 'R', 0, 0, 1, 0, '', 'C');
$pdf->Cell(80, 12, "$total_tests ", '', 0, 'R', 0, 0, 1, 0, '', 'C');
$pdf->ln(12);
$pdf->ln(12);

$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(500, 10, "NOTE:", "", 0, 'L', 0, 0, 1, 0, '', 'C');
$pdf->ln(12);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(500, 10, "This is a list of test results Mayo Medical Laboratories reported to your State Health Department. It is not intended to replace", "", 0, 'L', 0, 0, 1, 0, '', 'C');
$pdf->ln(12);
$pdf->Cell(500, 10, "your obligation to report, nor is it a guarantee that all applicable test results were reported.", "", 0, 'L', 0, 0, 1, 0, '', 'C');

// Set PDF metadata
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Mayo Medical Laboratories');

header("Pragma: public");
header("Expires: 0");

$pdf->Output($account_number . '-' . $start_date . '-' . $end_date . '-summary.pdf', 'I');
?>