<html>
<head>
<link rel="stylesheet" href="//<?php echo CSSURL;?>/public/css/<?php echo getCSSWithVersion('exampages_cms'); ?>" />
<link rel="stylesheet" href="//<?php echo CSSURL;?>/public/css/<?php echo getCSSWithVersion('taggingcms'); ?>" />
<title><?=$title?></title>
</head>
<body>
	<?php
	if($page == 'edit'){
		$editClass = "active";
	}else if($page == 'add'){
		$addClass = 'active';
	}else if($page == 'view'){
		$viewClass = 'active';
	}else if($page == 'delete'){
		$deleteClass = 'active';
	}else if($page == 'addImpact'){
		$addImpClass = 'active';
	}
	?>
<div class='navigation_bar'>
  <ul>
    <li><a class="<?=$addClass;?>" href="<?php echo base_url()."Tagging/TaggingCMS/showAddTagsForm";?>">Add</a></li>
    <li><a class="<?=$addImpClass;?>" href="<?php echo base_url()."Tagging/TaggingCMS/showAddTagsImpact";?>">Add Impact</a></li>
    <li><a class="<?=$viewClass;?>" href="<?php echo base_url()."Tagging/TaggingCMS/viewTagsDetails";?>">View</a></li>
    <li><a class="<?=$editClass;?>" href="<?php echo base_url()."Tagging/TaggingCMS/showEditTagsForm";?>">Edit</a></li>
    <li><a class="<?=$deleteClass;?>" href="<?php echo base_url()."Tagging/TaggingCMS/showDeleteTagsForm";?>">Delete</a></li>
    <li><a class="<?=$tagpendingactions;?>" href="<?php echo base_url()."Tagging/TaggingCMS/tagPendingActions";?>">Pending Tag Actions</a></li>
  </ul>
</div>