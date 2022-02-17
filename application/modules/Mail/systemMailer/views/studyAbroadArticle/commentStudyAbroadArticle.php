<?php $this->load->view('systemMailer/studyAbroadArticle/headerSAArticleMail');?>
<?php 	if($articleType == 'examPage'){
		$type = 'exam page';
	}
	else if($articleType == 'guide'){
		$type = 'guide';
	}
	else if($articleType == 'applyContent'){
		$type = 'apply content page';
	}
	else{
		$type = 'article';
	}
?>

<div width="600">Dear <?=$name;?><br>
<p><?=$userName;?> has posted a comment on your <?=($type)?> on studyabroad.shiksha.com.</p>
<?php if(strlen($articleTitle) > 160) {
	$articleTitle = substr($articleTitle,0,160).'...';
    }else {
        $articleTitle = substr($articleTitle, 0, 160);
    } ?>

<p><b><?=(ucwords($type))?> Title:</b><?=$articleTitle;?></p>

<p><b><?=$userName;?>'s complete comment:</b> <br>
<?=formatQNAforQuestionDetailPage($commentText);?>
<br>
<a href="<?=$articleUrl;?>" target="_blank">Click here to read the comment on the website</a></p>
<p>If you are unable to click the above link, please copy and paste the following in the address bar of your web browser:<br>
</p>
<div style="width:540px; word-wrap:break-word"><?=$articleUrl;?></div>
<p></p>
<br>
<br>
We love your work, keep writing :-)<br>

<?php $this->load->view('systemMailer/studyAbroadArticle/footerSAArticleMailer');?>
