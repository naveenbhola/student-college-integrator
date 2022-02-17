<?php
if($instituteComplete->getJoinReason()->getDetails())
{
?>
<div class="other-details-wrap clear-width why-join-sec" >
            <h2 class="mb14">Why Join <?=html_escape($institute->getName())?></h2>
            <?php
                    $summary = new tidy();
                    $summary->parseString($instituteComplete->getJoinReason()->getDetails(),array('show-body-only'=>true),'utf8');
                    $summary->cleanRepair();
	    ?>
            <div class="inst-speciality" style="display: block; word-wrap: break-word;"><?=$summary?></div>            
</div>
<?php
}
?>