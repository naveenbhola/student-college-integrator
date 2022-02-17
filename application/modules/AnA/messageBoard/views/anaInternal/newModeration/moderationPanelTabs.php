<div class="row">
	<?php
	if($hasModeratorAccess == 3)
	{
	?>
	<ul class="nav nav-tabs nav-pills nav-justified">
	<li class="<?=$tabSelected==1?'active':''?> top-bottom-padding text-center moderatorTab" id="myPendingModeration">
		<a href="javascript:void(0);">Pending Moderation</a>
	</li>
	<li class="<?=$tabSelected==2?'active':''?> top-bottom-padding text-center moderatorTab" id="myModeratedEntity">
		<a href="javascript:void(0);">Moderated by me</a>
	</li>
	</ul>
	<?php
	}
	else if($hasModeratorAccess == 1 || $hasModeratorAccess == 2)
	{
	?>
	<ul class="nav nav-tabs nav-pills nav-justified">
		<li class="<?=$tabSelected==3?'active':''?> top-bottom-padding text-center moderatorTab">
			<a href="<?=$tabSelected!=3?'/messageBoard/MessageBoardInternal/newCafeModerationPanel':'javascript:void(0);'?>">Cafe Moderation Panel</a>
		</li>
		<li class="<?=$tabSelected==4?'active':''?> top-bottom-padding text-center moderatorTab">
			<a href="<?=$tabSelected!=4?'/messageBoard/MessageBoardInternal/reputationIndexPanel':'javascript:void(0);'?>">Reputation Index Panel</a>
		</li>
		<li class="<?=$tabSelected==5?'active':''?> top-bottom-padding text-center moderatorTab">
			<a href="<?=$tabSelected!=5?'/messageBoard/MessageBoardInternal/userPointHistory':'javascript:void(0);'?>">User Point History</a>
		</li>
		<li class="<?=$tabSelected==6?'active':''?> top-bottom-padding text-center moderatorTab">
			<a href="<?=$tabSelected!=6?'/messageBoard/MessageBoardInternal/moderatorLockedEntities':'javascript:void(0);'?>">Locked Entities</a>
		</li>
		<li class="<?=$tabSelected==7?'active':''?> top-bottom-padding text-center moderatorTab">
			<a href="<?=$tabSelected!=7?'/messageBoard/MessageBoardInternal/MISForListingANA':'javascript:void(0);'?>">Listing AnA MIS</a>
		</li>
	</ul>
	<?php
	}
	?>
</div>
<div class="clearfix"></div>