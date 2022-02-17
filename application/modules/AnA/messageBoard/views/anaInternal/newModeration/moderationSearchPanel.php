<?php
	if(!isset($_COOKIE['cmpSearchPanel']) || (isset($_COOKIE['cmpSearchPanel']) && $_COOKIE['cmpSearchPanel'] == '1'))
	{
		$showSearchPanel = '';
		$iconSearchPanel = 'glyphicon-minus';
	}
	else
	{
		$showSearchPanel = 'display:none;';
		$iconSearchPanel = 'glyphicon-plus';
	}
	?>
<div class="row" id="searchPanel">
	<div id="toggleSearchPanelInner" class="pull-right">
		<button type="button" class="btn btn-default" aria-label="Left Align">
			<span class="glyphicon <?=$iconSearchPanel?>" id="searchPanelIcon" aria-hidden="true"></span> <span class="text-info">Search Panel</span>
		</button>
	</div>
	<div style="<?=$showSearchPanel?>" id="searchPanelInner" class="col-md-offset-3 col-md-6 style-form">
		<div class="clearfix"></div>
		<form class="form-horizontal" id="dataForm" action="/messageBoard/MessageBoardInternal/newCafeModerationPanel" onsubmit="" method="post" autocomplete="off" accept-charset="utf-8">
		  <?php
		  if($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3):
		  ?>
		  <div class="form-group">
		    <label for="date" class="col-md-5 control-label">From Date (yyyy-mm-dd)</label>
		    <div class="col-md-6">
		      <input type="text" id="date" name="date" value="<?=$_POST['date']?>" class="form-control" />
		    </div>
		  </div>
		  <?php endif; ?>
		  <?php
		  if($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3):
		  ?>
		  <div class="form-group">
		    <label for="dateTo" class="col-md-5 control-label">To Date (yyyy-mm-dd)</label>
		    <div class="col-md-6">
		      <input type="text" id="dateTo" name="dateTo" value="<?=$_POST['dateTo']?>" class="form-control" />
		    </div>
		  </div>
		  <?php endif ;?>
		  <?php
		  if($hasModeratorAccess == 1 || $hasModeratorAccess == 2):
		  ?>
		  <div class="form-group">
		    <label for="email" class="col-md-5 control-label">Email</label>
		    <div class="col-md-6">
		      <input type="text" id="email" name="email" value="<?=$_POST['email']?>" class="form-control" />
		    </div>
		  </div>
		  <?php endif ;?>
		  <?php
		  if($hasModeratorAccess == 1 || $hasModeratorAccess == 2):
		  ?>
		  <div class="form-group">
		    <label for="name" class="col-md-5 control-label">Displayname</label>
		    <div class="col-md-6">
		      <input type="text" id="name" name="name" value="<?=$_POST['name']?>" class="form-control" />
		    </div>
		  </div>
		  <?php endif ;?>
		  <?php
		  if($hasModeratorAccess == 1 || $hasModeratorAccess == 2):
		  ?>
		  <div class="form-group">
		    <label for="entity" class="col-md-5 control-label">Question/Discussion Id</label>
		    <div class="col-md-6">
		      <input type="text" id="entity" name="entity" value="<?=$_POST['entity']?>" class="form-control" />
		    </div>
		  </div>
		  <?php endif ;?>
		  <?php
		  if($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3):
		  ?>
		  <div class="form-group">
		    <label for="tag_search" class="col-md-5 control-label">Enter Tag</label>
		    <div class="col-md-6">
		      <input type="text" id="tag_search" name="tag_search" value="<?=$_POST['tag_search']?>" class="form-control tags" />
		      <input type="hidden" id="tagValue" name="tagValue" value="<?=$_POST['tagValue']?>" />
		    </div>
		  </div>
		  <?php endif ;?>
		  <?php
		  if($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3):
		  ?>
		  <div class="form-group">
		    <label for="instituteId" class="col-md-5 control-label">Institute Id</label>
		    <div class="col-md-6">
		      <input type="text" id="instituteId" name="instituteId" value="<?=$_POST['instituteId']?>" class="form-control" />
		    </div>
		  </div>
		  <?php endif ;?>
		   <?php
		  if($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3):
		  ?>
		  <div class="form-group">
		    <label for="entityType" class="col-md-5 control-label">Entity Type</label>
		    <div class="col-md-6">
		      <select name='entityType' id='entityType' class="form-control" onchange="toogleAnsweredByField(this);">
		      	<optgroup label="Cafe Entities">
				<option value="">All Cafe Entities</option>
				<?php
				foreach ($entityTypeArray as $key => $value) {
				?>
				<option value="<?=$key?>" <?=($_POST['entityType']==$key)?'selected="selected"':''?> ><?=$value?></option>
				<?php
				}
				?>
				</optgroup>
				<optgroup label="Listing Entities">
				<option value="lques"<?=($_POST['entityType']=='lques')?'selected="selected"':''?> >Listing Questions (CR or KR)</option>
				<option value="lanswer"<?=($_POST['entityType']=='lanswer')?'selected="selected"':''?> >Listing Answers (CR or KR)</option>
				</optgroup>
			  </select>
			  <input type="hidden" id="start" name="start" value="<?=isset($_POST['start'])?$_POST['start']:0?>"/>
			  <input type="hidden" id="count" name="count" value="<?=isset($_POST['count'])?$_POST['count']:50 ?>"/>
		    </div>
		  </div>
		  <div class="form-group" style="<?=($_POST['entityType']!='lanswer')?'display:none;':''?>" id="answeredByContainer">
		  	<label class="col-md-5 control-label" for="answeredBy">Answered By</label>
		  	<div class="col-md-6">
		  		<select class="form-control" name="answeredBy" id="answeredBy">
		  			<option <?=($_POST['answeredBy']=='all')?'selected="selected"':''?> value="all">All</option>
		  			<option <?=($_POST['answeredBy']=='cr')?'selected="selected"':''?> value="cr">Answers by CR</option>
		  			<option <?=($_POST['answeredBy']=='other')?'selected="selected"':''?> value="other">Answers by Others</option>
		  		</select>
		  	</div>
		  </div>
		  <?php endif ;?>
		  <?php
		  if($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3):
		  ?>
  		  <div class="form-group" id="streamBasedSearchOption">
		    <label for="tagSelect" class="col-md-5 control-label">Select Stream</label>
		    <div class="col-md-6">
		    <select class="form-control" name="tagSelect" id="tagSelect">
		    	<option value="">All</option>
		    	<option value="-1" <?=($_POST['tagSelect'] == -1)?'selected="selected"':''?>>Miscellaneous</option>
				<?php
				$cat = $_POST['tagSelect'];
				foreach($streamList as $streamTags)
				{
				        if($streamTags['id'] == $cat)
					{
				?>
					<option value="<?=$streamTags['id']?>" selected="selected"><?=$streamTags['tags']?></option>	
				<?php
					}
					else
					{
				?>
				    <option value="<?=$streamTags['id']?>"><?=$streamTags['tags']?></option>
				<?php
					}
				}
				?>
		    </select>  
		    </div>
		  </div>
		  <?php endif ;?>
		 
		  <?php
		  if($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3):
		  ?>
		  <div class="form-group">
		    <label for="sortOrder" class="col-md-5 control-label">Sort order</label>
		    <div class="col-md-6">
		      <select name='sortOrder' id='sortOrder' class="form-control">
				<option value="oldFirst" <?=(!isset($_POST['sortOrder']) || $_POST['sortOrder']=='oldFirst')?'selected="selected"':''?>>Oldest first</option>
				<option value="newFirst" <?=$_POST['sortOrder']=='newFirst'?'selected="selected"':''?>>Newest first</option>
			  </select>
		    </div>
		  </div>
		  <?php endif ;?>
		  <?php
		  if($hasModeratorAccess == 1 || $hasModeratorAccess == 2):
		  ?>
		  <div class="form-group">
		    <label for="tag_search" class="col-md-5 control-label">Moderation Status</label>
		    <div class="col-md-6">
		      <select class="form-control" name="moderationStatus" id="moderationStatus">
		      	<option value="">All</option>
		      	<option value="complete" <?=$_POST['moderationStatus']=='complete'?'selected="selected"':''?>>Completed Moderation</option>
		      	<option value="pending" <?=$_POST['moderationStatus']=='pending'?'selected="selected"':''?>>Pending Moderation</option>
		      </select>
		    </div>
		  </div>
		  <?php endif ;?>
		  <?php
		  if($hasModeratorAccess == 1 || $hasModeratorAccess == 2):
		  ?>
		  <div class="form-group">
		    <label for="tag_search" class="col-md-5 control-label">Moderated By</label>
		    <div class="col-md-6">
		      <select class="form-control" name="moderationBy" id="moderationBy">
		      	<option value="">Select</option>
		      	<?php
		      	if($hasModeratorAccess == 1)
		      	{
		      	?>
		      	<option value="<?=$validateuser[0]['userid']?>" <?=$_POST['moderationBy']==$validateuser[0]['userid']?'selected="selected"':''?>><?=$validateuser[0]['email']?></option>
		      	<?php
		      	}
		      	foreach ($allModerators as $key => $value) {
		      	?>
		      	<option value="<?=$value['userid']?>" <?=$_POST['moderationBy']==$value['userid']?'selected="selected"':''?>><?=$value['email']?></option>
		      	<?php
		      	}
		      	?>
		      </select>
		    </div>
		  </div>
		  <?php endif ;?>
		  <?php
		  	if($hasModeratorAccess == 1 || $hasModeratorAccess == 2 || $hasModeratorAccess == 3):
		  ?>
		  <div class="form-group">
		    <label for="tag_search" class="col-md-5 control-label">Content Flag</label>
		    <div class="col-md-6">
		      <select class="form-control" name="contentFlag" id="contentFlag">
		      	<option value="">All</option>
		      	<option value="Auto-moderated" <?=$_POST['contentFlag']=='Auto-moderated'?'selected="selected"':''?>>Auto-moderated</option>
		      	<option value="Flagged content" <?=$_POST['contentFlag']=='Flagged content'?'selected="selected"':''?>>Flagged content</option>
		      	<option value="Request Sent" <?=$_POST['contentFlag']=='Request Sent'?'selected="selected"':''?>>Request Sent</option>
		      	<option value="Edit Done" <?=$_POST['contentFlag']=='Edit Done'?'selected="selected"':''?>>Edit Done</option>
		      </select>
		    </div>
		  </div>
		  <?php endif ;?>

		  <div class="form-group">
		    <div class="col-sm-offset-5 col-sm-2">
		      <button type="button" onclick="getData();" class="btn btn-default">Get Data</button>
		    </div>
		    <div class="col-sm-5">
		      <button type="button" onclick="resetForm();" class="btn btn-default">Reset</button>
		    </div>
		  </div>
		</form>
	</div>
</div>
<hr/>
