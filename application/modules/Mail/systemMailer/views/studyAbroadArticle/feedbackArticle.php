<?php $this->load->view('systemMailer/studyAbroadArticle/headerSAArticleMail');?>

<div width="600">Dear <?=$name;?><br>
<?php if($rating == 1) $suffixStr = 'an upvote'; else $suffixStr = 'a downvote'; ?>
<p>Your article received <?=$suffixStr;?>!</p>
<?php if(strlen($articleTitle) > 160) {
	$articleTitle = substr($articleTitle,0,160).'...';
}else {
	$articleTitle = substr($articleTitle, 0, 160);
} ?>
<p><b>Article Title:</b> <?=$articleTitle;?></p>
<p><b>Article Link: </b><a href="<?=$articleUrl;?>">Click here to visit the article</a></p>
We love your work, keep writing :-)<br>

<?php $this->load->view('systemMailer/studyAbroadArticle/footerSAArticleMailer');?>
