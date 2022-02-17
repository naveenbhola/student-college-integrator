<?php
if($scholarshipObj->getDeadLine()->getNumAwards() != ''){
?>
<h2 class="titl-main">Students Awards</h2>
<p>Number of students to be awarded: <strong><?php echo $scholarshipObj->getDeadline()->getNumAwards() == -1 ? 'Varies' : moneyAmountFormattor($scholarshipObj->getDeadLine()->getNumAwards(), 1, 1);?></strong></p>
<p><?php echo $scholarshipObj->getDeadLine()->getNumAwardsDescription();?></p>
<?php 
}
?>