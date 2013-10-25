

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

    if ($alignment == "vertical") {
        ?> 

        <table cellpadding="0" width="80%" cellspacing ="0" style="border: 0px solid transparent;"> 
            <tr style="border: 0px solid transparent;">
                <td style="border: 0px solid transparent;" width="150"> Start Date: </td>
                <td style="border: 0px solid transparent;"> <input size="15" type="text" name="start_date" id="start_date" value = '<?= $start_date ?>' readonly> </td>
                <td style="border: 0px solid transparent;"> <button type="button" id="calendarButton" title="Show Calendar" class="cal_button"><img src="calendar-32.gif" width="18" height="18" alt="Calendar" ></button></td>
            </tr>
            <tr style="border: 0px solid transparent;">
                <td style="border: 0px solid transparent;" width="150"> End Date: </td>
                <td style="border: 0px solid transparent;"> <input size="15" type="text" name="end_date" id="end_date" value = '<?= $end_date ?>' readonly> </td>
                <td style="border: 0px solid transparent;"> <button type="button" id="calendarButton2" title="Show Calendar" class="cal_button"><img src="calendar-32.gif" width="18" height="18" alt="Calendar" ></button></td>
            </tr>



            <?php
        } else {
            ?>

            <table cellpadding="0" width="60%" cellspacing ="0" style="border: 0px solid transparent;"> 
                <tr style="border: 0px solid transparent;">
                    <td style="border: 0px solid transparent;" width="150"> Start Date: </td>
                    <td style="border: 0px solid transparent;"> <input size="15" type="text" name="start_date" id="start_date" value = '<?= $start_date ?>' readonly> </td>
                    <td style="border: 0px solid transparent;"> <button type="button" id="calendarButton" title="Show Calendar" class="cal_button"><img src="calendar-32.gif" width="18" height="18" alt="Calendar" ></button></td>
                    <td style="border: 0px solid transparent;" width="50"></td>
                    <td style="border: 0px solid transparent;" width="150"> End Date: </td>
                    <td style="border: 0px solid transparent;"> <input size="15" type="text" name="end_date" id="end_date" value = '<?= $end_date ?>' readonly> </td>
                    <td style="border: 0px solid transparent;"> <button type="button" id="calendarButton2" title="Show Calendar" class="cal_button"><img src="calendar-32.gif" width="18" height="18" alt="Calendar" ></button></td>

                </tr>
                <?php
            }
            ?>
            <tr> <td style="border: 0px solid transparent;"> Account Number </td>

                <?php
                $permission_check_query = " SELECT COUNT(*) as count 
                                            FROM people_roles join roles on people_roles.role_id = roles.id 
                                            where (roles.name = 'Administrator' or roles.name = 'Regional Manager') 
                                            AND people_roles.person_id = '{$_SESSION['user']['id']}'";

                $regional_manager = mysql_query($permission_check_query, $profile_dev_db);

                $row = mysql_fetch_row($regional_manager);
                if ($row[0] > 0) {
                    echo '<td style="border: 0px solid transparent;">';
                    if ($_GET['account'] == 'all' or $_GET['account'] == "") {
                        echo '<input size="15" type="text" name="account"/></td>';
                    } else {
                        echo '<input size="15" type="text" name="account" value="' . $_GET['account'] . '"/></td>';
                    }
                } else {
                    echo '<td style="border: 0px solid transparent;"><select name="account">';

                    echo "<option value='all'>All</option>";

                    foreach ($authorized_accounts as $account_number) {
                        echo '<option value="' . $account_number . '">' . $account_number . '</option>';
                    }
                    echo "</select></td>";
                }
                ?>
            </tr>
        </table>
        <table width = "60%" cellpadding="0" cellspacing ="0" style="border: 0px solid transparent;">
            <tr  style="border: 0px solid transparent;">
                <td style="border: 0px solid transparent; text-align: center;">
                    <input type="Submit" value="Display Reports">
                </td>
            </tr>
        </table>

</span>
