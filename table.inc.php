<?php

function _date_is_valid($str) {
    if (substr_count($str, '-') == 2) {
        list($y, $m, $d) = explode('-', $str);
        return checkdate($m, $d, $y);
    }
    return false;
}

$failure = false;

if ((!_date_is_valid($end_date) && $end_date !="") || (!_date_is_valid($start_date) && $start_date != "")) {
    $failure = true;
}
if ($_GET['error'] === 'date') {
    echo "Please enter valid date information";
    $failure = true;
}

if (!$failure) {
    $where = "Client_Account IN('" . implode("','", $authorized_accounts) . "')";
    $account_list_query = "SELECT DISTINCT Client_Account
      FROM reportable_diseases
      where Reported_Date >= '$start_date'
             and " . $where . "
       and Reported_Date <= '$end_date'";

    $account_list_result = mysql_query($account_list_query, $invoice_db) or die(mysql_error($invoice_db));
//for each account_number
    $account_get = "all";
    $rows = mysql_num_rows($account_list_result);
    if ($rows == 0 and $start_date != "") {
        echo "No results found";
    } else {
        echo '<table width="75%" cellpadding="5" cellspacing="0" border="1">
        <tr style="background:#D8D8D8">
          <td><strong>Account Number</strong></td>
          <td><strong>Available Reports</strong></td>
        </tr>';
        if ($rows != 0 && ($account_get == "" || $account_get == "all")) {
            if($account_get == "" || $account_get == "all" && $rows > 1) {
            echo 
      '<tr>
        <td>All Accounts</td>
        <td><a href = "report-summary.php?account_number=all&amp;start_date='.$start_date.'&amp;end_date='.$end_date.'&amp;csrf_token="'.$csrf_token.'">Summary Report [PDF]</a>
            <br/>
        <a href = "generate-report.php?account_number=all&amp;start_date=;start_date='.$start_date.'&amp;end_date='.$end_date.'&amp;csrf_token="'.$csrf_token.'">Detailed Report [PDF]</a>
            <br/>
        <a href = "report-csv.php?account_number=all&amp;start_date=;start_date='.$start_date.'&amp;end_date='.$end_date.'&amp;csrf_token="'.$csrf_token.'">CSV Report</a>
            </td>
      </tr>';
        }
        }

        $bg = true;
        while ($account = mysql_fetch_array($account_list_result)) {
            $account_number = $account['Client_Account'];
            $summary_report_string = "report-summary.php?account_number=$account_number&amp;start_date=$start_date&amp;end_date=$end_date&amp;csrf_token=$csrf_token";
            $detailed_report_string = "generate-report.php?account_number=$account_number&amp;start_date=$start_date&amp;end_date=$end_date&amp;csrf_token=$csrf_token";
            $csv_string = "report-csv.php?account_number=$account_number&amp;start_date=$start_date&amp;end_date=$end_date&amp;csrf_token=$csrf_token";
            echo '
        <tr>
            <td>'.$account_number.'</td>
            <td><a href = "'.$summary_report_string.'">Summary Report [PDF]</a>'
                    . '<br/>'
                    . '<a href = "'.$detailed_report_string.'">Detailed Report [PDF]</a>'
                    . '<br/>'
                    . '<a href = "'.$csv_string.'">CSV Report</a></td>
        </tr>';
            //echo $link_string;
        }

        echo '</table>';
    }
} else {
    echo "Please enter valid Date information";
}
?>
       