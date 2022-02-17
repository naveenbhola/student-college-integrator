<?php 
if(!empty($finalImpDateData)){
?>
<h3 class="sub-titl mb15">Important Dates</h3>
<?php 
foreach ($finalImpDateData as $value)
{
?>
        <div class="date-sec">
            <span class="dt-stp"></span>
            <p class="dt-tl"><?php echo $value['timestamp'] > 0 ? date('F d, Y', $value['timestamp'] ) : 'Date Not Available'; ?></p>
        </div>
        <div class="dt-det">
            <strong><?php echo $value['heading'] ? $value['heading'] : 'Heading Not Available'  ?></strong>
            <p><?php echo $value['description'] ? $value['description'] : 'Description Not Available'; ?></p>
        </div>
<?php
}
?>
<span class="time-des">Please note that these dates could be in local country times and may not be IST (Indian standard time).</span>
<?php 
}
?>