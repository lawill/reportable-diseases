<?php
$pdf->SetFont('helvetica', 'B', 12);
$pdf->ln(20);
//these values are used for determining which year and month to search for within the SQL statements

    $test_select = "SELECT Test_Name, Count(*) as count from(
                SELECT Test_Name, Count(*) FROM reportable_diseases 
      where Reported_Date >= '$start_date'
       and Reported_Date <= '$end_date'
       and Client_Account = '$account_number'
       " . ""
                    . "GROUP BY Agency_Name, Test_Name, Mayo_Order_Number) as a group by Test_Name Order by Test_Name asc";


    $full_count_query = "SELECT SUM(count) as count from ($test_select) as a";

    
    
    $total_count_result = mysql_query($full_count_query, $invoice_db);
    $total_count = mysql_fetch_array($total_count_result);
    $count = $total_count['count'];

    /*
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
*/
    $account_name_query = "SELECT name, id
                         FROM clients
                         WHERE number = 'C$account_number'";
    $client_name_result = mysql_query($account_name_query, $profile_dev_db);
    $client_name_array = mysql_fetch_array($client_name_result);
    $client_name = $client_name_array['name'];
    $client_id = $client_name_array['id'];

    $client_address_id_query = "select client_address_id from client_addresses_clients where client_id = '$client_id'";
    $client_address_id_result = mysql_query($client_address_id_query, $profile_dev_db);
    $client_address_id_array = mysql_fetch_array($client_address_id_result);
    
    $client_address_info_query = "select * from client_addresses where id = '".$client_address_id_array['client_address_id']."'";
    $client_address_info_result = mysql_query($client_address_info_query, $profile_dev_db);
    $client_address_info = mysql_fetch_array($client_address_info_result);
    
    $address1 = $client_address_info['street'];
    $address2 = $client_address_info['street2'];
    $city = $client_address_info['city'];
    $state = $client_address_info['state'];
    $postal_code = $client_address_info['postal_code'];

    $pdf->Cell(500, 14, "$client_name", '', 0, 'L', 0, 0, 1, 0, '', 'C');
    $pdf->ln(14);
    $pdf->Cell(500, 14, "Client # $account_number", '', 0, 'L', 0, 0, 1, 0, '', 'C');
    $pdf->ln(14);
    $pdf->Cell(350, 14, "$address1", '', 0, 'L', 0, 0, 1, 0, '', 'C');
    if (!$summary) {
    $pdf->Cell(150, 14, "Report Total: Number Reported = $count", '', 0, 'L', 0, 0, 1, 0, '', 'C');
    }
    else {
        $pdf->Cell(150, 14, "", '', 0, 'L', 0, 0, 1, 0, '', 'C');
    }
    $pdf->ln(14);
    if (!empty($address2)) {
        $pdf->Cell(500, 14, "$address2", '', 0, 'L', 0, 0, 1, 0, '', 'C');
        $pdf->ln(14);
        if (!empty($address3)) {
            $pdf->Cell(500, 14, "$address3", '', 0, 'L', 0, 0, 1, 0, '', 'C');
            $pdf->ln(14);
        }
    }
    if (!$summary) {
        $pdf->Cell(500, 14, "$city, $state $postal_code", 'B', 0, 'L', 0, 0, 1, 0, '', 'C');
        $pdf->ln(14);
    }
    else {
         $pdf->Cell(350, 14, "$city, $state $postal_code", 'B', 0, 'L', 0, 0, 1, 0, '', 'C');
    }
        
    

?>