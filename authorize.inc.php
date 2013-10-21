<?php

$authorized_accounts = array();

$get_authorized_accounts = mysql_query("SELECT clients.number " .
        "FROM clients " .
        "INNER JOIN clients_permission_assignments " .
        "ON clients_permission_assignments.client_id = clients.id " .
        "INNER JOIN permission_assignments " .
        "ON clients_permission_assignments.permission_assignment_id = permission_assignments.id " .
        "INNER JOIN permissions " .
        "ON permission_assignments.permission_id = permissions.id " .
        "WHERE permissions.label LIKE '$specific_permission' " .
        "AND permission_assignments.person_id = '{$_SESSION['user']['id']}' ", $profile_dev_db);
while (list($authorized_account) = mysql_fetch_row($get_authorized_accounts)) {
    $authorized_accounts[] = trim($authorized_account, 'C');
}
$admin_view = ($permissions[$_SESSION['user']['role']] >= $permissions['Regional Manager']);
$where = $admin_view ? '' : " AND AccountNumber IN('" . implode("','", $authorized_accounts) . "')";

?>