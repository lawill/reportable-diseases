<?php

//$protect_from_forgery = true;
//$page_permission = 'Regional Manager';
$specific_permission = 'Receive Reportable Disease Reports';
require('' . 'master.inc.php');

require('authorize.inc.php');

$admin_view = ($permissions[$_SESSION['user']['role']] >= $permissions['Regional Manager']);

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

$account_where = array();

$account_where[] = "(Client_Account IN('" . implode("','", $authorized_accounts) . "'))";

$account_number = $_GET['account_number'];
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];
require('views_update.php');

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

while ($account_info = mysql_fetch_array($account_list_result)) {
    $account_number = $account_info['Client_Account'];
    require('header.inc.php');

    $agency_name_query = "SELECT Distinct Agency_Name, Agency_State FROM reportable_diseases
      where Reported_Date >= '$start_date'
       and Reported_Date <= '$end_date'
      and Client_Account = '$account_number'";

    $agency_name_result = mysql_query($agency_name_query, $invoice_db);
    $where = "AND Client_Account = '$account_number'";

    while ($agency_info = mysql_fetch_array($agency_name_result)) {


        $agency_name = $agency_info['Agency_Name'];
        $agency_state = $agency_info['Agency_State'];

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->ln(8);
        $pdf->Cell(500, 14, "$agency_name - $agency_state", '', 0, 'L', 0, 0, 1, 0, '', 'C');
        $pdf->ln(14);

        $pdf->ln(12);

        $test_name_query = "SELECT
      DISTINCT Test_Name from reportable_diseases 
       where Reported_Date >= '$start_date'
       and Reported_Date <= '$end_date'
       and Agency_Name = '$agency_name'
      $where order by Test_Name asc";

        $test_name_result = mysql_query($test_name_query, $invoice_db);

        while ($test_name = mysql_fetch_array($test_name_result)) {

            $Test_Name = $test_name['Test_Name'];

            $test_info_query = "SELECT Count(RecordID) as Count
    from reportable_diseases
     where Reported_Date >= '$start_date'
       and Reported_Date <= '$end_date'
      and Test_Name = '$Test_Name'
      and Agency_Name = '$agency_name'
      $where";


            $test_info_result = mysql_query($test_info_query, $invoice_db);

            $test_info = mysql_fetch_array($test_info_result);

            $number_reported = $test_info['Count'];

            //for each Test_Name
            $test_info_query = "SELECT * FROM reportable_diseases
                                where Reported_Date >= '$start_date'
                                and Reported_Date <= '$end_date'
                                and Test_Name = '$Test_Name'
                                and Agency_Name = '$agency_name'
                                $where
                                ORDER BY Mayo_Order_Number";

            $test_info_result = mysql_query($test_info_query);

            $total_reported = $total_reported + $number_reported;


            $pdf->SetFont('helvetica', '', 11);
            $pdf->Cell(350, 12, "$Test_Name", '', 0, 'L', 0, 0, 1, 0, '', 'C');
            $pdf->Cell(150, 12, "Number Reported:$number_reported ", '', 0, 'R', 0, 0, 1, 0, '', 'C');
            $pdf->ln(16);

            $pdf->SetFont('helvetica', '', 9);
            $pdf->Cell(60, 12, "MML Order#", '', 0, 'C', 0, 0, 1, 0, '', 'C');
            $pdf->Cell(150, 12, "Patient Name", '', 0, 'C', 0, 0, 1, 0, '', 'C');
            $pdf->Cell(60, 12, "Birth Date", '', 0, 'C', 0, 0, 1, 0, '', 'C');
            $pdf->Cell(60, 12, "Client Order #", '', 0, 'C', 0, 0, 1, 0, '', 'C');
            $pdf->Cell(85, 12, "Reporting Date", '', 0, 'C', 0, 0, 1, 0, '', 'C');
            $pdf->Cell(85, 12, "Collection Date", '', 0, 'C', 0, 0, 1, 0, '', 'C');
            $pdf->ln(12);


            //for each Test_Name
            $test_info_query = "SELECT RecordID, Agency_Name, Agency_State, Client_Account, 
                  aes_decrypt(Last_Name, LOAD_FILE('$db_aes_key')) as Last_Name, 
                  aes_decrypt(First_Name, LOAD_FILE('$db_aes_key')) as First_Name, 
                  Test_Name, Mayo_Order_Number, Client_Order_Number, 
                  aes_decrypt(Birth_Date, LOAD_FILE('$db_aes_key')) as Birth_Date, 
                  Reported_Date, Collection_Date, Mayo_Order_Number
                  FROM reportable_diseases 
                  where Reported_Date >= '$start_date'
                  and Reported_Date <= '$end_date'
                  and Test_Name = '$Test_Name'
                  and Agency_Name = '$agency_name'
                  $where
                  ORDER BY Mayo_Order_Number";
           


            $test_info_result = mysql_query($test_info_query, $invoice_db);

            while ($test_info = mysql_fetch_array($test_info_result)) {
                $mml_order_number = $test_info["Mayo_Order_Number"];
                $first_name = $test_info["First_Name"];
                $last_name = $test_info["Last_Name"];
                $patient_name = $last_name . ", " . $first_name;

                $birth_date = $test_info["Birth_Date"];
                $birth_date = strtotime($birth_date);
                $birth_date = date('m/d/Y', $birth_date);

                $client_order_number = $test_info["Client_Order_Number"];


                $reporting_date = $test_info["Reported_Date"];
                $reporting_date = strtotime($reporting_date);
                $reporting_date = date('m/d/Y', $reporting_date);

                $collection_date = $test_info["Collection_Date"];
                $collection_date = strtotime($collection_date);
                $collection_date = date('m/d/Y', $collection_date);


                $pdf->SetFont('helvetica', '', 9);

                $pdf->Cell(10, 16, "", '', 0, 'L', 0, 0, 1, 0, '', 'C');
                $pdf->Cell(60, 16, "$mml_order_number", '', 0, 'L', 0, 0, 1, 0, '', 'C');
                $pdf->Cell(150, 16, "$patient_name", '', 0, 'L', 0, 0, 1, 0, '', 'C');
                $pdf->Cell(60, 16, "$birth_date", '', 0, 'L', 0, 0, 1, 0, '', 'C');
                $pdf->Cell(60, 16, "$client_order_number", '', 0, 'L', 0, 0, 1, 0, '', 'C');
                $pdf->Cell(60, 16, "$reporting_date", '', 0, 'R', 0, 0, 1, 0, '', 'C');
                $pdf->Cell(85, 16, "$collection_date", '', 0, 'R', 0, 0, 1, 0, '', 'C');
                $pdf->ln(11);
            }

            $pdf->Cell(500, 10, "", 'B', 0, 'L', 0, 0, 1, 0, '', 'C');
            $pdf->ln(21);
        }
    }
}

$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(350, 16, "", '', 0, 'C', 0, 0, 1, 0, '', 'C');
$pdf->Cell(150, 16, "Grand Total Reported: $total_reported", '', 0, 'C', 0, 0, 1, 0, '', 'C');
$pdf->ln(21);

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

$account_number = $_GET['account_number'];
$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];

$pdf->Output($account_number . '-' . $start_date . '-' . $end_date . '.pdf', 'I');
?>
