

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
    $start_date = $_REQUEST["start_date"];
    $end_date = $_REQUEST["end_date"];

    if ($alignment == "vertical") {
        ?> 

        <table cellpadding="0" width="80%" cellspacing ="0" style="border: 0px solid transparent;"> 
            <tr style="border: 0px solid transparent;">
                <td style="border: 0px solid transparent;" width="150"> Start Date: </td>
                <td style="border: 0px solid transparent;"> <input size="15" type="text" name="start_date" id="start_date" value = '<?= $start_date ?>' > </td>
                <td style="border: 0px solid transparent;"> <button type="button" id="calendarButton" title="Show Calendar" class="cal_button"><img src="http://developer.yahoo.com/yui/examples/calendar/assets/calbtn.gif" width="18" height="18" alt="Calendar" ></button></td>
            </tr>
            <tr style="border: 0px solid transparent;">
                <td style="border: 0px solid transparent;" width="150"> End Date: </td>
                <td style="border: 0px solid transparent;"> <input size="15" type="text" name="end_date" id="end_date" value = '<?= $end_date ?>'/> </td>
                <td style="border: 0px solid transparent;"> <button type="button" id="calendarButton2" title="Show Calendar" class="cal_button"><img src="http://developer.yahoo.com/yui/examples/calendar/assets/calbtn.gif" width="18" height="18" alt="Calendar" ></button></td>
        </table>


        <?php
    } else {
        ?>

        <table cellpadding="0" width="60%" cellspacing ="0" style="border: 0px solid transparent;"> 
            <tr style="border: 0px solid transparent;">
                <td style="border: 0px solid transparent;" width="150"> Start Date: </td>
                <td style="border: 0px solid transparent;"> <input size="15" type="text" name="start_date" id="start_date" value = '<?= $start_date ?>' > </td>
                <td style="border: 0px solid transparent;"> <button type="button" id="calendarButton" title="Show Calendar" class="cal_button"><img src="http://developer.yahoo.com/yui/examples/calendar/assets/calbtn.gif" width="18" height="18" alt="Calendar" ></button></td>
                <td style="border: 0px solid transparent;" width="150"> End Date: </td>
                <td style="border: 0px solid transparent;"> <input size="15" type="text" name="end_date" id="end_date" value = '<?= $end_date ?>'/> </td>
                <td style="border: 0px solid transparent;"> <button type="button" id="calendarButton2" title="Show Calendar" class="cal_button"><img src="http://developer.yahoo.com/yui/examples/calendar/assets/calbtn.gif" width="18" height="18" alt="Calendar" ></button></td>

            </tr>
        </table>

    <?php
}
?>
    <table width = "60%" cellpadding="0" cellspacing ="0" style="border: 0px solid transparent;">
        <tr  style="border: 0px solid transparent;">
            <td style="border: 0px solid transparent; text-align: center;">
                <input type="Submit" value="Display Reports">
            </td>
        </tr>
    </table>

</span>
