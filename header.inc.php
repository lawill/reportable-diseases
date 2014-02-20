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
    $total_count = $total_count['count'];

    $account_name_query = "SELECT
                                mayoed_production.client_addresses.street,
                                mayoed_production.client_addresses.street2,
                                mayoed_production.client_addresses.street3,
                                mayoed_production.client_addresses.city,
                                mayoed_production.client_addresses.state,
                                mayoed_production.client_addresses.postal_code
                        FROM mayoed_production.client_addresses
                        INNER Join mayoed_production.client_addresses_clients
                        ON mayoed_production.client_addresses_clients.client_address_id = mayoed_production.client_addresses.id
                        INNER Join mayoed_production.clients
                        ON mayoed_production.clients.id = mayoed_production.client_addresses_clients.client_id
                        AND mayoed_production.clients.number = 'C".$account_number."'";

    //echo $account_name_query;
    //$pdf->Cell(500, 14, "$account_name_query", '', 0, 'L', 0, 0, 1, 0, '', 'C');
    $pdf->ln(14);
	
    $client_address_results = mysql_query($account_name_query, $profile_db);
    $client_address_info = mysql_fetch_array($client_address_results);
    $client_name = $client_address_info['name'];
    $address1 = $client_address_info['street'];
    $address2 = $client_address_info['street2'];
    $city = $client_address_info['city'];
    $state = $client_address_info['state'];
    $postal_code = $client_address_info['postal_code'];

    $pdf->Cell(500, 14, "$client_name", '', 0, 'L', 0, 0, 1, 0, '', 'C');
    $pdf->ln(14);
    $pdf->Cell(500, 14, "Client # $account_number", '', 0, 'L', 0, 0, 1, 0, '', 'C');
    $pdf->ln(14);
    $pdf->Cell(250, 14, "$address1", '', 0, 'L', 0, 0, 1, 0, '', 'C');
    if (!$summary) {
    $pdf->Cell(250, 14, "Report Total: Number Reported = $total_count", '', 0, 'L', 0, 0, 1, 0, '', 'C');
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