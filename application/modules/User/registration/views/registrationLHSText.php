<div class="regLft-col">
	<i class="icons bl-logo"></i>
	<div class="bg-tx-bx">
		<strong class="wy-sgpTit">Why Sign Up?</strong>
		<?php foreach ($customHelpText as $key => $customText) {
			if($customText['heading'] && $customText['body']){
	    ?>			
			<div class="lhs-txt">
				<p class="wy-sgn2"><?=$customText['heading'];?></p>
				<ul>
					<?php foreach($customText['body'] as $key=>$bodyText){ ?>
						<li><?php echo $bodyText; ?></li>
					<?php } ?>
				</ul>
			</div>
		 	<?php }?>
		 <?php }?>
	</div>

	<div>
		<?php echo $registrationShikshaStats;?>
	</div>
</div>