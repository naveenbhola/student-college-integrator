<?php $this->load->view('systemMailer/studyAbroadArticle/headerSAArticleMail');?>

<div width="600">Dear <?=$replyUserName;?><br>
<?php
  if ($articleType== 'examPage')
	$Type = 'Exam Page';
  else if($articleType== 'article')
	$Type = 'Article';
  else if($articleType== 'applyContent')
	$Type = 'Apply Content Page'; 	
  else
	$Type = 'Guide';
	
  if($type != 'author'){
   $str = 'replied on your comment';
   $clickText = 'Click here to read the reply on the website';
  }
  else{
    $str = 'posted a reply on your '.$articleType;
    $clickText = 'Click here to read the complete reply';
  }?>
<p><?=$userName;?> has <?=$str;?> on studyabroad.shiksha.com.</p>
<?php if(strlen($articleTitle) > 160) {
	$articleTitle = substr($articleTitle,0,160).'...';
}else {
	$articleTitle = substr($articleTitle, 0, 160);
} ?>
<p><b><?=$Type?> Title:</b> <?=$articleTitle;?></p>
<p><b><?=$userName;?>'s reply:</b> <br>
<?=formatQNAforQuestionDetailPage($commentText);?><br>
<a href="<?=$articleUrl;?>" target="_blank"><?=$clickText;?></a></p>
<p>If you are unable to click the above link, please copy and paste the following in the address bar of your web browser:<br>
</p>
<div style="width:540px; word-wrap:break-word">
<?=$articleUrl;?>
</div>
<p></p>
<br>
<br>
Good luck for your career :-)<br>

<?php $this->load->view('systemMailer/studyAbroadArticle/footerSAArticleMailer');?>
