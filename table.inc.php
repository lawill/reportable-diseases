<?php

function _date_is_valid($str) {
    if (substr_count($str, '-') == 2) {
        list($y, $m, $d) = explode('-', $str);
        return checkdate($m, $d, $y);
    }

    return false;
}

$failure = false;
if (!empty($_SERVER["QUERY_STRING"]) && !_date_is_valid($start_date)) {
    $failure = true;
}
if (!empty($_SERVER["QUERY_STRING"]) && !_date_is_valid($end_date)) {
    $failure = true;
}
if ($_GET['error'] === 'date') {
    echo "Please enter valid date information";
    $failure = true;
}
if (!$failure) {
    $account_list_query = "SELECT DISTINCT Client_Account
      FROM reportable_diseases
      where Reported_Date >= '$start_date'
             and " . implode(' AND ', $where) . "
       and Reported_Date <= '$end_date'";

    $account_list_result = mysql_query($account_list_query, $invoice_db) or die(mysql_error($invoice_db));
//for each account_number

    $rows = mysql_num_rows($account_list_result);
    if ($rows == 0 and $start_date != "") {
        echo "No results found";
    } else {
        echo '<table width="75%" cellpadding="5" cellspacing="0" border="1">
        <tr style="background:#D8D8D8">
          <td><strong>Account Number</strong></td>
          <td><strong>Summary</strong></td>
          <td><strong>Detailed</strong></td>
          <td><strong>CSV</strong></td>
        </tr>';

        echo '';


        if ($rows != 0) {
            echo "<tr>
      <td>All Accounts</td>
      <td><a href = 'report-summary.php?account_number=all&start_date=$start_date&end_date=$end_date&csrf_token=$csrf_token'>Download Report</a></td>
      <td><a href = 'generate-report.php?account_number=all&start_date=$start_date&end_date=$end_date&csrf_token=$csrf_token'>Download Report</a></td>
      <td><a href = 'report-csv.php?account_number=all&start_date=$start_date&end_date=$end_date&csrf_token=$csrf_token'>Download Report</a></td>
      </tr>";
        }


        while ($account = mysql_fetch_array($account_list_result)) {
            $account_number = $account['Client_Account'];
            $report_string = "report-summary.php?account_number=$account_number&start_date=$start_date&end_date=$end_date&csrf_token=$csrf_token>";
            $summary_string = "generate-report.php?account_number=$account_number&start_date=$start_date&end_date=$end_date&csrf_token=$csrf_token>";
            $csv_string = "report-csv.php?account_number=$account_number&start_date=$start_date&end_date=$end_date&csrf_token=$csrf_token>";
            echo "<tr>
        <td>$account_number</td>
        <td><a href = '$report_string'>Download Report</a></td>
        <td><a href = '$summary_string'>Download Report</a></td>
        <td><a href = '$csv_string'>Download Report</a></td>
        </tr>";
            //echo $link_string;
        }

        echo '</table>';
    }
} else {
    echo "Please enter valid Date information";
}
?>
       