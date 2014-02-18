<?php
// ======================================================================================
// Permission must be one of the following: 
// 'Public', 'Basic', 'Transactional', 'Commercial', 'Secondary', 'Primary', 'Mayo Staff'
// ======================================================================================
$page_permission = 'Regional Manager';
$specific_permission = 'Receive Reportable Disease Reports';

require_once('' . 'master.inc.php');

require_once('authorize.inc.php');
$start_date = date('Y-m-d', $_GET['form_data']['start_date']);
$end_date = date('Y-m-d', $_GET['form_data']['end_date']);

$account = trim($_GET['form_data']['account'], 'C');
$where = "(Client_Account IN('" . implode("','", $authorized_accounts) . "'))";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><!-- InstanceBegin template="/Templates/second_level_template.dwt" codeOutsideHTMLIsLocked="false" -->
    <head>
        <?php include(''.'javascript.inc.php'); ?>
        <!-- InstanceBeginEditable name="doctitle" -->
        <title>Reportable Diseases Portal - Mayo Medical Laboratories</title>
        <meta name="keywords" content="Mayo Medical Laboratories, MML, mission, esoteric, testing, laboratory services, " />
        <meta name="description" content="Our mission is to support the local delivery of laboratory services through the provision of exceptional reference laboratory services and by providing support services which facilitate and augment community integration efforts. " />
        
        <!-- These will need to get changed to include path -->
        <link rel="stylesheet" href="calendarview.css">
            <script src="calendarview.js"></script>
            
        <!-- InstanceEndEditable -->
        <link rel="stylesheet" type="text/css" href="../../css/print.css" media="print" />
        <link rel="stylesheet" type="text/css" href="../../css/mml.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="../../css/main_navigation.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="../../css/callouts.css" media="all" />
        <link rel="stylesheet" type="text/css" href="../../css/user_styles.css" media="all" />
        <link rel="stylesheet" type="text/css" href="../../css/languages.css" media="all" />
        <link rel="stylesheet" type="text/css" href="../../css/test_catalog.css" />
        

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
                    <?php @include('' . 'sign_in_status.inc.php'); ?>
                    <?php if ($authorized_user) { ?>
                        <a href="/profile/index.html" id="profile_button">My Profile</a> <a href="/logout.php" id="logout_button">Sign
                            Out</a>
                    <?php } ?>
                </div>
            </div>
            <div id="content_area">
                <div id="primary_sidebar" class="column"> 
                    <?php include_once('' . 'navigation.inc.php'); ?>
                    <!-- InstanceBeginEditable name="Left_Callout" --> <!-- InstanceEndEditable --> </div>
                <div id="main_content" class="column">  <!-- InstanceBeginEditable name="ThemeGraphic" -->  <!-- InstanceEndEditable --><!-- #BeginLibraryItem "/Library/pageLinks.lbi" -->
                    <div class="page_links"> <a href="#" onclick="window.print();
                                        return false"><img src="../../images/siteframework/printButton.gif" alt="Print Page" /></a> <a id="emailPageLink" href="mailto:[Type%20recipient%20address]?subject=Mayo%20Medical%20Laboratories&amp;body=[Type%20your%20message%20here]%0D%0A%0D%0APage%20address:%20" onclick="if (this.href.indexOf(window.location.href) < 0) {
                                                    this.href += window.location.href
                                                }"><img src="../../images/siteframework/emailButton.gif" alt="Email Page" /></a> </div>
                    <!-- #EndLibraryItem --><!-- InstanceBeginEditable name="pageHeading" -->
                    <?php
                    if ($_SESSION['error']) {
                        echo "<div class=\"error\">$_SESSION[error]</div>";
                    }
                    if ($_SESSION['message']) {
                        echo "<div class=\"message\">$_SESSION[message]</div>";
                    }
                    ?>

                    <h1>Reportable Disease Portal</h1>

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

                        <!-- InstanceEndEditable -->
                        <!-- The following callout box is for Archives -->
                        <!-- InstanceBeginEditable name="ArchiveCalloutBox" --><!-- InstanceEndEditable -->
                        <!-- The following callout box is for accessing applications -->
                        <!-- InstanceBeginEditable name="ApplicationCalloutBox" --><!-- InstanceEndEditable -->
                        <!-- The following callout box is for advertising images -->
                        <!-- InstanceBeginEditable name="AdvertisingCalloutBox" --><!-- InstanceEndEditable --> </div>
                    <div id="pagebody"><!-- InstanceBeginEditable name="pageBody" -->

                        <div id="notes">
                            <h3>Feedback &amp; Suggestions</h3>
                            Of the enhancements currently in development, many reflect input and suggestions directly from our clients. If you have comments or suggestions regarding these reports or any other MML services, contact your MML field service representative.

                        </div>
                        <br>

                            <form action="index.php" method="get" onsubmit="return checkDateValues();">
                                <?php
                                $alignment = "horizontal";
                                $font_size = "12";
                                include("calendar.inc.php");
                                ?>
                            </form>
                           
                            <?php
                            if($_GET['account'] != "" && $_GET['account']!='all' && $admin_view) {
                                $where = "Client_Account = '".$_GET['account']."'" ;
                                $authorized_accounts[] = $_GET['account'];
                               
                            }
                            else if($_GET['account'] != "" && $_GET['account']!='all' && in_array($_GET['account'], $authorized_accounts))
                            {
                                
                            $where = "(Client_Account IN('" . implode("','", $authorized_accounts) . "') AND Client_Account = '".$_GET['account']."')";

                            $authorized_accounts[] = $_GET['account'];

                            }
                            include('table.inc.php');
                            ?>
                            <!-- InstanceEndEditable --></div>
                </div>
                <hr />
                <div id="footer"> <a href="../../index.html">Home</a> | <a href="../contacts.html">Contact
                        Us</a> | <a href="../terms.html">Terms of Use</a> | <a href="../privacy.html">Privacy</a> | <a href="../../about/quality/framework/accreditation-regulatory-standards.html#hipaa">HIPAA</a> | <a href="http://www.mayoclinic.org/jobs" target="_blank">Careers</a><br />
                    Copyright &copy; 1995&ndash;<?= date('Y') ?> Mayo Foundation for Medical Education and Research. All Rights Reserved. </div>
                <div id="permissions_key"> 
                    <h3>Key</h3> 
                    <ul> 
                        <li><img src="../../images/icons/access-metal.gif" alt="Requires registration" width="12" height="9" /> <a href="../user/permissions.html">Requires
                                Registration</a></li> 
                        <li><img src="../../images/icons/access-blue.gif" alt="Requires Transactional status" width="12" height="9" /> <a href="../user/permissions.html">Requires
                                Transactional Access</a></li> 
                        <li><img src="../../images/icons/access-green.gif" alt="Requires Secondary status" width="12" height="9" /> <a href="../user/permissions.html">Requires
                                Secondary Access</a></li> 
                        <li><img src="../../images/icons/access-gold.gif" alt="Requires Primary status" width="12" height="9" /> <a href="../user/permissions.html">Requires
                                Primary Access</a></li> 
                        <li><img src="../../images/icons/external_link.gif" alt="External Link" width="16" height="13" /> External 
                            link</li> 
                        <li><img src="../../images/icons/pdficon_14x14.gif" alt="PDF document" width="14" height="14" /> PDF 
                            document</li> 
                        <li><img src="../../images/icons/excel_doc_16x16.gif" alt="Excel document" width="14" height="16" /> Excel 
                            document</li> 
                        <li><img src="../../images/icons/word_doc_16x16.gif" alt="Word document" width="14" height="16" /> Word 
                            document</li> 
                    </ul> 
                </div>
            </div>
        </div>
        <?php include_once('' . 'analytics.inc.php'); ?>
        <!-- InstanceBeginEditable name="AdditionalTrackingCode" --><!-- InstanceEndEditable -->
        <?php require_once('' . 'cleanup.inc.php'); ?>
    </body>
    <!-- InstanceEnd --></html>
