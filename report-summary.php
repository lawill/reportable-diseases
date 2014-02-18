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
require('tcpdf/tcpdf.php');
require('tcpdf/config/lang/eng.php');

//Since the header and footer are generated a page at a time, the total number of pages 
//needs to be calculated ahead of time for the page information in the footer
// Extend the TCPDF class to create custom header and footer functions
class REQ_PDF extends TCPDF {

    function Header() {
        
    }

    function Footer() {
        //Removed the followwing Global - KGE
        //global $mnemonic, $test_name, $unit_code, $company_name, $acct_id_footer;

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
//require('views_update.php');

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
} else if($admin_view)  
{
    $account_list_query = "SELECT DISTINCT Client_Account
      FROM reportable_diseases
      where Reported_Date >= '$start_date'
             and Client_Account = '$account_number'
             
       and Reported_Date <= '$end_date'";
}
else {
    $account_list_query = "SELECT DISTINCT Client_Account
      FROM reportable_diseases
      where Reported_Date >= '$start_date'
             and Client_Account = '$account_number'
             and " . implode(' AND ', $account_where) . "
       and Reported_Date <= '$end_date'";
}

$account_list_result = mysql_query($account_list_query, $invoice_db) or die(mysql_error($invoice_db));

if(mysql_num_rows($account_list_result) < 1) {
    header("Location: index.php");
}

while ($account_info = mysql_fetch_array($account_list_result)) {
    $account_number = $account_info['Client_Account'];

    $summary = true;
    require('header.inc.php');

    //$pdf->ln(10);

    

        $pdf->SetFont('helvetica', 'B', 12);

        $test_result = mysql_query($test_select, $invoice_db);
        
        $test_name_query = "SELECT SUM(count) as count from ($test_select) as a";
        $test_name_result = mysql_query($test_name_query, $invoice_db);

        
        
        while ($test_info = mysql_fetch_array($test_name_result)) {


            $pdf->Cell(80, 12, "$total_count ", 'B', 0, 'R', 0, 0, 1, 0, '', 'C');
            $pdf->Cell(70, 12, "", 'B', 0, 'R', 0, 0, 1, 0, '', 'C');
            $pdf->ln(22);

            $pdf->SetFont('helvetica', '', 12);              
            
            $test_select = "SELECT Test_Name, COUNT(DISTINCT Mayo_Order_Number) AS Count
                            FROM reportable_diseases
                            WHERE Reported_Date BETWEEN '$start_date' AND '$end_date'
                                AND Client_Account = '$account_number'
                            GROUP BY Test_Name";
             
            while ($test_data = mysql_fetch_array($test_result)) {

                $pdf->Cell(350, 14, $test_data['Test_Name'], '', 0, 'L', 0, 0, 1, 0, '', 'C');
                $pdf->Cell(77, 12, $test_data['count'], '', 0, 'R', 0, 0, 1, 0, '', 'C');
                $pdf->Cell(73, 12, "", '', 0, 'R', 0, 0, 1, 0, '', 'C');
                $pdf->ln(12);
            }
        }
    }

$pdf->ln(12);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(350, 12, "Total", '', 0, 'R', 0, 0, 1, 0, '', 'C');
$pdf->Cell(80, 12, "$total_count ", '', 0, 'R', 0, 0, 1, 0, '', 'C');
$pdf->ln(12);
$pdf->ln(12);

$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(500, 10, "NOTE:", "", 0, 'L', 0, 0, 1, 0, '', 'C');
$pdf->ln(12);
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(500, 10, "This is a list of test results Mayo Medical Laboratories reported to your State Health Department. It is not intended to replace", "", 0, 'L', 0, 0, 1, 0, '', 'C');
$pdf->ln(12);
$pdf->Cell(500, 10, "your obligation to report, nor is it a guarantee that all applicable test results were reported.", "", 0, 'L', 0, 0, 1, 0, '', 'C');
//
// Insert users activity into security file
//

$account_number = $_GET['account_number'];

$view_insert = "INSERT INTO reportable_diseases_views (person_id, email, account_number, report_type) "
        . "VALUES ('".$_SESSION['user']['id']."', "
        . "'".$_SESSION['user']['email']."', "
        . "'$account_number', "
        . "'Summary Report')";
mysql_query($view_insert, $invoice_db);

// Set PDF metadata
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Mayo Medical Laboratories');

header("Pragma: public");
header("Expires: 0");

$pdf->Output($account_number . '-' . $start_date . '-' . $end_date . '-summary.pdf', 'I');
?>