<?php foreach ($replyArr as $key => $row){
$msgId = $row['msgId'];
$userProfileImage = ($row['userImage']) ? $row['userImage'] : $row['userProfileImage'];
$userProfileImage = ($userProfileImage) ? $userProfileImage : '/public/images/photoNotAvailable_v1.gif';
$displayName = ($row['displayName']) ? $row['displayName'] : $row['displayname'];
$text = $row['msgTxt'];
if(isset($showTimeTextHide) && $showTimeTextHide){
	$creationTime = makeRelativeTime($row['creationDate']);
}
?>
<div id="completeMsgContent<?php echo $msgId;?>" class="fbkBx">
  <div>
	  <div class="flLt wdh100">
		  <?php if($status!='abused'){ ?>
		  <div class="mobile-flex">
		  		<div class="imgBx">
				   <img id="userProfileImageForComment<?php echo $msgId.rand(0, 10000);?>" src="<?php echo getTinyImage($userProfileImage);?>" style="cursor:pointer;" onClick="window.location=('<?php echo site_url('getUserProfile').'/'.$displayName; ?>');" />
			  </div>
			  <div class="cntBx">
				  <div class="wdh100 flLt">
					  <div class="">
						<span>
							<span><strong><a href="<?php echo site_url('getUserProfile').'/'.$displayName; ?>"><?php echo $displayName; ?></a></strong></span>
						</span>
					  </div>
				  </div>
			  </div>
			  </div>

			  <div class="clearFix"></div>
		  <?php }?>

		  <!-- user replies on comments -->
		  <div class="topic-discusn wdh100 flLt"> 
	  	 	<?php $text = html_entity_decode(html_entity_decode($text,ENT_NOQUOTES,'UTF-8'));
				  $text = formatQNAforQuestionDetailPage($text,500);
			?>
		  	<?php if(strlen(strip_tags($text))>300){?>
			<p id="shortCnt<?php echo $msgId;?>">
				<?php echo cutString($text, 300);?> <a data-cntId="<?php echo $msgId;?>" class="_readMore">Read more</a>
            </p>
           <?php }?>
			<p id="fullCntnt<?php echo $msgId;?>" <?php if(strlen(strip_tags($text))>300){?> class="hide" <?php }?>>
				<?php echo $text;?>
            </p>
		  </div>
		  <div class="topic-flex wdh100 flLt"><span class="fcdGya"><?php if(isset($showTimeTextHide) && $showTimeTextHide){ echo $creationTime; }else{?>a few secs ago<?php }?></span></div>
	  </div>
  </div>
</div>
<?php }?>