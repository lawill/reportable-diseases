<?php


//filter/validate date information here

$account_number = $_GET['account_number'];

$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];



$protect_from_forgery = true;
$page_permission = 'Regional Manager';
$specific_permission = 'Receive Reportable Disease Reports';
require('' . 'master.inc.php');
require_once('authorize.inc.php');

require("date_check.php");

if($account_number != "all") {
    if (!in_array($account_number, $authorized_accounts))
    {
        echo "You do not have access to view that account";
        die();
    }
}

header('Content-Type: text/csv; charset=utf-8');
header("Content-Disposition: attachment; filename=$account_number-$start_date-$end_date.csv");

header("Pragma: public");
header("Expires: 0");

$where = $admin_view ? '' : " AND AccountNumber IN('" . implode("','", $authorized_accounts) . "')";
$views_insert = "INSERT into reportable_diseases_views
                   VALUES";

$account_where = array();
$account_where[] = "(Client_Account IN('" . implode("','", $authorized_accounts) . "'))";



/* This script creates a CSV report based on a date range and an account number */
// require(''.'master.inc.php');

$output = fopen('php://output', 'w');

fputcsv($output, array('Agency_Name', 'Agency_State', 'Client_Account', 'Last_Name', 'First_Name', 'Test_Name',
    'Mayo_Order_number', 'Client_Order_Number', 'Birth_Date', 'Reported_Date', 'Collection_Date'));


if ($account_number == "all") {
    $where = "AND" . implode(' AND ', $account_where);
} else {
    $where = "and Client_Account = '$account_number'";
}

$query_string = "SELECT Agency_Name, Agency_State, Client_Account, 
                  aes_decrypt(Last_Name, LOAD_FILE('$db_aes_key')) as Last_Name, 
                  aes_decrypt(First_Name, LOAD_FILE('$db_aes_key')) as First_Name, 
                  Test_Name, Mayo_Order_number, Client_Order_Number, 
                  aes_decrypt(Birth_Date, LOAD_FILE('$db_aes_key')) as Birth_Date, 
                  Reported_Date, Collection_Date
                  FROM reportable_diseases 
                  where Reported_Date >= '$start_date'
                  and Reported_Date <= '$end_date'
                  $where";

$data_result = mysql_query($query_string, $invoice_db);
while ($row = mysql_fetch_assoc($data_result))
    fputcsv($output, $row);
?>