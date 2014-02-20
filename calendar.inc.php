

<script>
    function setupCalendars() {
        // Embedded Calendar


        // Popup Calendar
        Calendar.setup(
                {
                    dateField: 'start_date',
                    triggerElement: 'calendarButton'
                }
        )

        Calendar.setup(
                {
                    dateField: 'end_date',
                    triggerElement: 'calendarButton2'
                }
        )

    }

    function checkDateValues() {

        var startDate = document.getElementById('start_date').value;
        var endDate = document.getElementById('end_date').value;


        if (!/^\d{4}-\d{2}-\d{2}$/.test(startDate))
        {
            alert("Please enter a valid start date");
            return false;
        }
        if (!/^\d{4}-\d{2}-\d{2}$/.test(endDate))
        {
            alert("Please enter a valid end date");
            return false;
        }

        if (startDate > endDate) {
            alert("Start Date is later than End Date");
            return false;
        }
    }

    Event.observe(window, 'load', function() {
        setupCalendars()
    })
</script>
<style type="text/css">
    .cal_button {
        padding: 0px;
    }
    .cal_button { background: none; border: none; }

</style>

<span style="font-size:<?= $font_size ?>px">

    <?php
    $font_size;
    $start_date = $_GET["start_date"];
    $end_date = $_GET["end_date"];

    if($end_date == "") {
        $end_date = date('Y-m-d');
        
        $last_viewed_query = "SELECT MAX(created_at) as created_at from reportable_diseases_views where person_id = '".$_SESSION['user']['id']."'";
        
        $last_viewed_result = mysql_query($last_viewed_query, $invoice_db) or die(mysql_error($invoice_db));;
        $last_viewed = mysql_fetch_array($last_viewed_result);
        $start_date = date('Y-m-d', strtotime($last_viewed['created_at']));
        
    }
    //select max(created_at) from quality_reports_views where person_id = 96304
    
    if ($alignment == "vertical") {
        ?> 

        <table cellpadding="0" width="80%" cellspacing ="0" class="borderless"> 
            <tr>
                <td  width="50%">Start Date:</td>
                <td > <input size="15" type="text" name="start_date" id="start_date" value = "<?= $start_date ?>" readonly="readonly" /> </td>
                <td > <button type="button" id="calendarButton" title="Show Calendar" class="cal_button"><img src="calendar-32.gif" width="18" height="18" alt="Calendar" /></button></td>
            </tr>
            <tr >
                <td   width="50%">End Date:</td>
                <td > <input size="15" type="text" name="end_date" id="end_date" value = "<?= $end_date ?>" readonly="readonly" /> </td>
                <td > <button type="button" id="calendarButton2" title="Show Calendar" class="cal_button"><img src="calendar-32.gif" width="18" height="18" alt="Calendar" /></button></td>
            </tr>



            <?php
        } else {
            ?>

            <table cellpadding="5" width="60%" cellspacing ="0" class="borderless"> 
                <tr >
                    <td  >Start Date:</td>
                    <td > <input size="10" type="text" name="start_date" id="start_date" value = "<?= $start_date ?>" readonly="readonly" /> </td>
                    <td > <button type="button" id="calendarButton" title="Show Calendar" class="cal_button"><img src="calendar-32.gif" width="18" height="18" alt="Calendar" /></button></td>
                    <td  >&nbsp;</td>
                    <td  >End Date:</td>
                    <td > <input size="10" type="text" name="end_date" id="end_date" value = "<?= $end_date ?>" readonly="readonly" /> </td>
                    <td > <button type="button" id="calendarButton2" title="Show Calendar" class="cal_button"><img src="calendar-32.gif" width="18" height="18" alt="Calendar" /></button></td>

                </tr>
                <?php
            }
            /*
            echo "<tr> <td > Account Number </td>";

                
                if ($admin_view) {
                    echo '<td >';
                    
                    if ($_GET['account'] == 'all' or $_GET['account'] == "") {
                        echo '<input size="15" type="text" name="account"/></td>';
                    } else {
                        echo '<input size="15" type="text" name="account" value="' . $_GET['account'] . '"/></td>';
                    }
                } else {
                    echo '<td ><select name="account">';

                    echo "<option value='all'>All</option>";

                    foreach ($authorized_accounts as $account_number) {
                        echo '<option value="' . $account_number . '">' . $account_number . '</option>';
                    }
                    echo "</select></td>";
                }
             * 
             */
                ?>
            </tr>
        </table>
        <table width = "60%" cellpadding="0" cellspacing ="0" class="borderless">
            <tr  >
                <td>
                    <input type="Submit" value="Display Reports">
                </td>
            </tr>
        </table>

</span>
