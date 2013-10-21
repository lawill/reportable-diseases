<?php
    // ======================================================================================
    // Permission must be one of the following: 
    // 'Public', 'Basic', 'Transactional', 'Commercial', 'Secondary', 'Primary', 'Mayo Staff'
    // ======================================================================================
    $page_permission = 'Regional Manager';
    $specific_permission = 'Receive Reportable Diseases';

    require_once(''.'master.inc.php');
    
    $authorized_accounts = array();
    $get_authorized_accounts = mysql_query("SELECT clients.number ".
                                            "FROM clients ".
                                            "INNER JOIN clients_permission_assignments ".
                                            "ON clients_permission_assignments.client_id = clients.id ".
                                            "INNER JOIN permission_assignments ".
                                            "ON clients_permission_assignments.permission_assignment_id = permission_assignments.id ".
                                            "INNER JOIN permissions ".
                                            "ON permission_assignments.permission_id = permissions.id ".
                                            "WHERE permissions.label LIKE '$specific_permission' ".
                                            "AND permission_assignments.person_id = '{$_SESSION['user']['id']}' ", $profile_db);
    while(list($authorized_account) = mysql_fetch_row($get_authorized_accounts)){$authorized_accounts[]=trim($authorized_account,'C');}
   
    $admin_view = ($permissions[$_SESSION['user']['role']] >= $permissions['Regional Manager']);
    
    if(($admin_view && ($_POST['account'] || $_GET['page'] || $_GET['format'])) || (!$admin_view && count($authorized_accounts))) {
      $page_size    = 60;
      $page_number  = $_GET['page'] ? intval($_GET['page']) : 1;
      
      $where      = array();
      $joins      = '';
      $start_date = date('Y-m-d', $_SESSION['form_data']['start_date']);
      $end_date   = date('Y-m-d', $_SESSION['form_data']['end_date']);
      //$where[]    = "(date BETWEEN '$start_date' AND '$end_date')";
      if($admin_view) {
        if($_SESSION['form_data']['account']) {
          $account = trim($_SESSION['form_data']['account'],'C');
          $where[] = "AccountNumber = '$account'";
        }
      } else {
        $where[] = "(AccountNumber IN('".implode("','",$authorized_accounts)."'))";
      }

      $count_reports = mysql_query("SELECT COUNT(*) FROM quality_reports_dev WHERE ".implode(' AND ',$where), $invoice_db);

      list($result_count) = mysql_fetch_row($count_reports);
      $page_count = ceil($result_count / $page_size);
      if($page_number > $page_count) {
        $page_number = $page_count;
        $page_offset = $page_size * ($page_number-1);
      } else {
        $page_offset = 0;
      }
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><!-- InstanceBegin template="/Templates/second_level_template.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<script language="javascript" src="calendar/calendar.js"></script>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Reportable Diseases Portal - Mayo Medical Laboratories</title>
<meta name="keywords" content="Mayo Medical Laboratories, MML, mission, esoteric, testing, laboratory services, " />
<meta name="description" content="Our mission is to support the local delivery of laboratory services through the provision of exceptional reference laboratory services and by providing support services which facilitate and augment community integration efforts. " />
<!-- InstanceEndEditable -->
<link rel="stylesheet" type="text/css" href="../../css/print.css" media="print" />
<link rel="stylesheet" type="text/css" href="../../css/mml.css" media="screen" />
<link rel="stylesheet" type="text/css" href="../../css/main_navigation.css" media="screen" />
<link rel="stylesheet" type="text/css" href="../../css/callouts.css" media="all" />
<link rel="stylesheet" type="text/css" href="../../css/user_styles.css" media="all" />
<link rel="stylesheet" type="text/css" href="../../css/languages.css" media="all" />
<link rel="stylesheet" type="text/css" href="../../css/test_catalog.css" />
<link rel="stylesheet" type="text/css" href="../../css/quality_reports.css" />
<?php include(''.'javascript.inc.php'); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
</head>
<body>
<noscript>
<style type="text/css" media="screen">
	.menuGroup ul, .menuGroup ul ul, .menuGroupExpanded ul ul, .menuGroupActive ul ul {
		display: block;
	}
</style>
</noscript>
<div id="page">
  <a id="mobile-site-link" href="http://www.mayomedicallaboratories.com/mobile" rel="nofollow">Mobile Site &#155;</a>
  <div id="header">
    <form name="gs" id="site_search" method="get" action="/search.php">
      <span class="instructions">Search Mayo Medical Laboratories<br/>
      </span>
      <input type="text" name="q" maxlength="256" />
      <button type="submit" border="0" id="search_button">Search</button>
    </form>
    <table id="print_contact">
      <tr>
        <th scope="row">Web:</th>
        <td>MayoMedicalLaboratories.com</td>
      </tr>
      <tr>
        <th scope="row">Email:</th>
        <td>mml&#64;mayo.edu</td>
      </tr>
      <tr>
        <th scope="row">Telephone:</th>
        <td>800.533.1710</td>
      </tr>
      <tr>
        <th scope="row">International:</th>
        <td>507.266.5700</td>
      </tr>
      <tr>
        <td colspan="2" align="center"><i>Values are valid only on day
          of printing.</i></td>
      </tr>
    </table>
    <a href="../../index.html"><img src="../../images/siteframework/MAYO.gif" alt="Mayo Medical Laboratories" border="0" id="logo" /></a>
    <div id="access_buttons"> <a href="../../index.html" id="home_button">Home</a>
      <?php @include(''.'sign_in_status.inc.php'); ?>
      <?php if($authorized_user) { ?>
      <a href="/profile/index.html" id="profile_button">My Profile</a> <a href="/logout.php" id="logout_button">Sign
      Out</a>
      <?php } ?>
    </div>
  </div>
  <div id="content_area">
    <div id="primary_sidebar" class="column"> 
      <?php include_once(''.'navigation.inc.php'); ?>
      <!-- InstanceBeginEditable name="Left_Callout" --> <!-- InstanceEndEditable --> </div>
    <div id="main_content" class="column">  <!-- InstanceBeginEditable name="ThemeGraphic" -->  <!-- InstanceEndEditable --><!-- #BeginLibraryItem "/Library/pageLinks.lbi" -->
      <div class="page_links"> <a href="#" onclick="window.print();return false"><img src="../../images/siteframework/printButton.gif" alt="Print Page" /></a> <a id="emailPageLink" href="mailto:[Type%20recipient%20address]?subject=Mayo%20Medical%20Laboratories&amp;body=[Type%20your%20message%20here]%0D%0A%0D%0APage%20address:%20" onclick="if(this.href.indexOf(window.location.href)<0){this.href+=window.location.href}"><img src="../../images/siteframework/emailButton.gif" alt="Email Page" /></a> </div>
    <!-- #EndLibraryItem --><!-- InstanceBeginEditable name="pageHeading" -->
        <?php 
          if($_SESSION['error']) {
            echo "<div class=\"error\">$_SESSION[error]</div>";
          }
          if($_SESSION['message']) {
            echo "<div class=\"message\">$_SESSION[message]</div>";
          }
        ?>
      <?
      $test_list = links_from_tags('first_tag,second_tag', 3, 'FAQ,HotTopic,Algorithm');
      echo $test_list;
      ?>

      <h1>Reportable Diseases Portal</h1>
      
      <div id="callout_boxes">
        <!-- The following callout box is for About videos -->
        <!-- InstanceBeginEditable name="AboutCalloutBox" --><!-- InstanceEndEditable -->
        <!-- The following callout box is for internal navigation -->
        <!-- InstanceBeginEditable name="TopicNavCalloutBox" --><!-- InstanceEndEditable -->
        <!-- The following callout box is for the latest information -->
        <!-- InstanceBeginEditable name="WhatsnewCalloutBox" --><!-- InstanceEndEditable -->
        <!-- The following callout box is for Contact information -->
        <!-- InstanceBeginEditable name="ContactCalloutBox" --><!-- InstanceEndEditable -->
        <!-- The following callout box is for information related to this page -->
        <!-- InstanceBeginEditable name="RelatedInfoCalloutBox" -->
        <div id="callout_quality_office" class="callout_box">
          <h3>REQUIRED SOFTWARE</h3>
          <ul>
            <a href="http://get.adobe.com/reader/">Adobe Reader version 8 or higher</a> is required to view these reports. If you have problems viewing reports, first update your version of Reader.
          </ul> 
        </div>
        <div id="callout_notes" class="callout_box">
          <h3>Links</h3>
          <ul>
            <li><a href ="http://www.mayomedicallaboratories.com/about/quality/glossary.html">Glossary</a></li>
          </ul>        
        </div>
        <div class="callout_box" id="callout_faq">
          <h3>Frequently Asked Questions </h3>
          <?=links_from_tags("FAQ,quality-reports", 3);?>
        </div>
        <!-- InstanceEndEditable -->
        <!-- The following callout box is for Archives -->
        <!-- InstanceBeginEditable name="ArchiveCalloutBox" --><!-- InstanceEndEditable -->
        <!-- The following callout box is for accessing applications -->
        <!-- InstanceBeginEditable name="ApplicationCalloutBox" --><!-- InstanceEndEditable -->
        <!-- The following callout box is for advertising images -->
        <!-- InstanceBeginEditable name="AdvertisingCalloutBox" --><!-- InstanceEndEditable --> </div>
      <div id="pagebody"><!-- InstanceBeginEditable name="pageBody" -->

      <div id="notes">
      
      </div>
      <br>