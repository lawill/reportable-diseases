<?php

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

//these values are used for determining which year and month to search for within the SQL statements


if ($account_number == "all") {
    $client_name = "All Accounts";

    $pdf->Cell(500, 14, "$client_name", '', 0, 'L', 0, 0, 1, 0, '', 'C');
    $pdf->ln(14);
} else {
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
    $pdf->Cell(500, 14, "$address1", '', 0, 'L', 0, 0, 1, 0, '', 'C');
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
}
?>