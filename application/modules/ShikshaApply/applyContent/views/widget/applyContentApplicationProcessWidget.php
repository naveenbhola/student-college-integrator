<?php if(count($learnApllicationProcessData)>0){?>
<div class="sop-app-process-sec">
	<p class="app-process-title">Learn more about application process</p>
	<ul class="app-process-block-list">
		<li>
		<?php
		$count = 1;
		foreach($learnApllicationProcessData as $key=>$tuple){ ?>
			<a href="<?= $tuple['contentURL'];?>" title="<?= $tuple['heading']?>">
				<div class="app-process-block <?= ($count%2==0)?"flRt":"flLt"?>">
					<i class="sop-sprite <?php echo $tuple['icon'];?>"></i>
					<div class="app-process-detail">
						<strong><?= $tuple['heading']?></strong><br>
						<span><?= $tuple['name']?></span>
					</div>
				</div>
			</a>
		<?= ($count%2==0 && $count!=count($learnApllicationProcessData))?"</li><li>":""?>	
		<?php $count++;} ?>
		</li>
	</ul>
</div>
<?php } ?>