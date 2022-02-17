<div class="col-bar">
    <h2 class="titl-main">Student Awards</h2>
    <p class="sub-titl f14-fnt"> Number of students to be awarded: <strong><?php echo $scholarshipObj->getDeadline()->getNumAwards() == -1 ? 'Varies' : moneyAmountFormattor($scholarshipObj->getDeadline()->getNumAwards(),1,1); ?></strong></p>
    <?php if($scholarshipObj->getDeadline()->getNumAwardsDescription() != ''){ ?>
    	<p><?php echo $scholarshipObj->getDeadline()->getNumAwardsDescription(); ?></p>
    <?php } ?>
</div>