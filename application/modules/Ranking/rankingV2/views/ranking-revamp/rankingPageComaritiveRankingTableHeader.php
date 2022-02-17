<?php 
$sortClass = 'sorting-loader flRt';
$sortPosClass = '';
if($rankingPage->isUgTemplate()=='Yes') {
	$sortClass 	= 'sorting-loader-ug flRt';
	$sortPosClass = 'sort-pos-ug';
}
$columnCount = 0;
?>
<thead>
<tr class="header">
	<!--                     	<td class="col-1">Shortlist</td> -->
	<th class="col-2 header-text">College Name
		<div class="flRt">
			<i class="<?=$sortPosClass;?> ranking-sprite sort-up-arrw<?php echo ($sortColumnType == 'institute_name' && $sortOrder == 'asc'? '-a' : '');?>" type='collegeName' label='institute_name' order='asc'></i><br />
			<i class="<?=$sortPosClass;?> ranking-sprite sort-dwn-arrw<?php echo ($sortColumnType == 'institute_name' && $sortOrder == 'desc' ? '-a' : '');?>" type='collegeName' label='institute_name' order='desc'></i>
		</div>
		<div class="<?=$sortClass;?>"> <img src="//<?php echo IMGURL; ?>/public/images/small-loader.GIF" /> </div>
		<?php $columnCount++;?>
	</th>
	
	<?php
            foreach($rankingPageSources as $sourceRow)
		{
	?>
	<th class="rest-cols">
		<span class="flLt header-text"><?php echo $sourceRow->getName();?></span>
		<div class="flRt">
			<i class="<?=$sortPosClass;?> ranking-sprite sort-up-arrw<?php echo ($sortColumnType == 'source' && $sortOrder == 'asc' && $sortKeyValue == $sourceRow->getId()? '-a' : '');?>" type='source-<?=$sourceRow->getName()?>' order='asc' label='source' columnTypeVal=<?php echo $sourceRow->getId();?>></i><br />
			<i class="<?=$sortPosClass;?> ranking-sprite sort-dwn-arrw<?php echo ($sortColumnType == 'source' && $sortOrder == 'desc' && $sortKeyValue == $sourceRow->getId()? '-a' : '');?>" type='source-<?=$sourceRow->getName()?>' order='desc' label='source' columnTypeVal=<?php echo $sourceRow->getId();?>></i>
		</div>
		<div class="<?=$sortClass;?>"> <img src="//<?php echo IMGURL; ?>/public/images/small-loader.GIF" /> </div>
		<?php $columnCount++;?>
	</th>
	<?php
		}
	?>

	<?php 

	if($rankingPage->isUgTemplate()=='No') {
	?>
	<th class="rest-cols"><span class="flLt header-text">Exams & Eligibility
	</span>
		<div class="flRt">
			<i style="<?php echo ($examName ? '' : 'display:none;') ?>" class="ranking-sprite sort-up-arrw<?php echo ($sortColumnType == 'examScore' && $sortOrder == 'asc'? '-a' : '');?>" <?php echo ($examName ? "type='exam-{$examName}' label='examScore' order='asc' columnTypeVal='{$examName}' ": "")?>></i><br />
			<i style="<?php echo ($examName ? '' : 'display:none;') ?>" class="ranking-sprite sort-dwn-arrw<?php echo ($sortColumnType == 'examScore' && $sortOrder == 'desc'? '-a' : '');?>" <?php echo ($examName ? "type='exam-{$examName}' label='examScore' order='desc' columnTypeVal='{$examName}' " : "")?>></i>
		</div>
		<?php
		if($examName)
		{
		?>
		<div class="sorting-loader flRt"> <img src="//<?php echo IMGURL; ?>/public/images/small-loader.GIF" /> </div>
		<?php
		}
		?>
		<?php $columnCount++;?>
	</th>
	<th class="rest-cols"><span class="flLt header-text">Average Salary <span class="font-11">(Annual)</span></span>
		<div class="flRt">
			<i class="ranking-sprite sort-up-arrw<?php echo ($sortColumnType == 'salary' && $sortOrder == 'asc'? '-a' : '');?>" type='salary' label='salary' order='asc'></i><br />
			<i class="ranking-sprite sort-dwn-arrw<?php echo ($sortColumnType == 'salary' && $sortOrder == 'desc'? '-a' : '');?>" type='salary' label='salary' order='desc'></i>
		</div>
		<div class="sorting-loader flRt"> <img src="//<?php echo IMGURL; ?>/public/images/small-loader.GIF" /> </div>
		<?php $columnCount++;?>
	</th>
	<?php } ?>
	<th class="rest-cols"><span class="flLt header-text">Total Fees (INR) 	
	</span>
		<div class="flRt">
			<i class="<?=$sortPosClass;?> ranking-sprite sort-up-arrw<?php echo ($sortColumnType == 'fees' && $sortOrder == 'asc'? '-a' : '');?>" type='fees' label='fees' order='asc'></i><br />
			<i class="<?=$sortPosClass;?> ranking-sprite sort-dwn-arrw<?php echo ($sortColumnType == 'fees' && $sortOrder == 'desc'? '-a' : '');?>" type='fees' order='desc' label='fees'></i>
		</div>
		<div class="<?=$sortClass;?>"> <img src="//<?php echo IMGURL; ?>/public/images/small-loader.GIF" /> </div>
		<?php $columnCount++;?>
	</th>
	<th class="rest-cols rest-cols-last header-text">Compare<?php $columnCount++;?></th>
</tr>
</thead>
<?php 
$collegeWidth = 22;
$remainingWidth = 9;
if($columnCount  == 6) {
	$collegeWidth = 45;
	$remainingWidth = (100 - $collegeWidth)/($columnCount - 1);
}
?>
<style>
	.ranking-table tr td.col-2{width:<?=$collegeWidth?>%; vertical-align:top; text-align:left;}
	.ranking-table tr td.rest-cols{width:<?=$remainingWidth?>%;}
</style>