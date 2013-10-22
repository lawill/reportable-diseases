<?php
$pdf->SetFont('helvetica', 'B', 12);

//these values are used for determining which year and month to search for within the SQL statements

    $total_for_account_query = "SELECT COUNT(*) as Count 
                               FROM reportable_diseases 
                               WHERE Client_Account = '$account_number' 
                               AND Reported_Date >= '$start_date'
                               AND Reported_Date <= '$end_date'";
    $total_count_result = mysql_query($total_for_account_query, $invoice_db);
    $total_count = mysql_fetch_array($total_count_result);
    $count = $total_count['Count'];

    $account_name_query = "SELECT name, client_id
                         FROM crm_accounts
                         WHERE number = '$account_number'";

    $name_result = mysql_query($account_name_query, $staging_db);

    $client_name_array = mysql_fetch_array($name_result);
    $client_name = $client_name_array['name'];

    $client_id = $client_name_array['client_id'];

    $address_query = "SELECT * FROM crm_addresses
                    WHERE id = $client_id";

    $address_result = mysql_query($address_query, $staging_db);
    $address_info = mysql_fetch_array($address_result);

    $address1 = $address_info['address1'];
    $address2 = $address_info['address2'];
    $address3 = $address_info['address3'];

    $city = $address_info['city'];
    $state = $address_info['state'];
    $postal_code = $address_info['postal_code'];

    $pdf->Cell(500, 14, "$client_name", '', 0, 'L', 0, 0, 1, 0, '', 'C');
    $pdf->ln(14);
    $pdf->Cell(500, 14, "Client # $account_number", '', 0, 'L', 0, 0, 1, 0, '', 'C');
    $pdf->ln(14);
    $pdf->Cell(350, 14, "$address1", '', 0, 'L', 0, 0, 1, 0, '', 'C');
    $pdf->Cell(150, 14, "Report Total: Number Reported = $count", '', 0, 'L', 0, 0, 1, 0, '', 'C');
    $pdf->ln(14);
    if (!empty($address2)) {
        $pdf->Cell(500, 14, "$address2", '', 0, 'L', 0, 0, 1, 0, '', 'C');
        $pdf->ln(14);
        if (!empty($address3)) {
            $pdf->Cell(500, 14, "$address3", '', 0, 'L', 0, 0, 1, 0, '', 'C');
            $pdf->ln(14);
        }
    }
    $pdf->Cell(500, 14, "$city, $state $postal_code", 'B', 0, 'L', 0, 0, 1, 0, '', 'C');
    $pdf->ln(14);

?>