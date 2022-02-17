<?php
if(isset($newsArticles)):
$newsArticles = json_decode($newsArticles,false);
$newsArticles = $newsArticles->articles;
$count_latest_updates = count($newsArticles);

if($count_latest_updates%4 == 0) {
	$count_latest_updates = $count_latest_updates/4;
} else {
	$count_latest_updates = intval($count_latest_updates/4)+1;
}
$count_flavours = count($allArticles);
if($count_flavours>=$count_latest_updates) {
	$count_flavours = $count_latest_updates;
} else {
	$count_latest_updates = $count_flavours; 
}
?>
<ul class="latest-up-lists" style="width:<?php echo $count_latest_updates*342*4;?>px;" id="latest_update_ul">
<?php $index_update = 0;while($index_update<$count_latest_updates*4):
$index_update1 = $index_update+1;
$index_update2 = $index_update+2;
$index_update3 = $index_update+3;
$txt_1 = trim(strip_tags($newsArticles[$index_update]->blogTitle));
$txt_2 = trim(strip_tags($newsArticles[$index_update1]->blogTitle));
$txt_3 = trim(strip_tags($newsArticles[$index_update2]->blogTitle));
$txt_4 = trim(strip_tags($newsArticles[$index_update3]->blogTitle));
if(strlen($txt_1)>40) {$text1 = wordLimiter(substr($txt_1,0,40),40)."...";} else {$text1 = $txt_1;}
if(strlen($txt_2)>40) {$text2 = wordLimiter(substr($txt_2,0,40),40)."...";} else {$text2 = $txt_2;}
if(strlen($txt_3)>40) {$text4 = wordLimiter(substr($txt_3,0,40),40);$text3=$text4."...";} else {$text3 = $txt_3;}
if(strlen($txt_4)>40) {$text40 = wordLimiter(substr($txt_4,0,40),40);$text30=$text40."...";} else {$text30 = $txt_4;}
$var_text = '<ul><li><a onclick="trackEventByGA(\'homepageNewsArtcileclick\',this.innerHTML);" title="'.$newsArticles[$index_update]->blogTitle.'" href="'.$newsArticles[$index_update]->url.'">'.$text1.'</a></li>';
if(!empty($text2))
$var_text .= '<li><a onclick="trackEventByGA(\'homepageNewsArtcileclick\',this.innerHTML);" title="'.$newsArticles[$index_update1]->blogTitle.'" href="'.$newsArticles[$index_update1]->url.'">'.$text2.'</a><br/></li>';
if(!empty($text3))
$var_text .= '<li><a  onclick="trackEventByGA(\'homepageNewsArtcileclick\',this.innerHTML);" title="'.$newsArticles[$index_update2]->blogTitle.'" href="'.$newsArticles[$index_update2]->url.'">'.$text3.'</a></li>';
if(!empty($text30))
$var_text .= '<li><a  onclick="trackEventByGA(\'homepageNewsArtcileclick\',this.innerHTML);" title="'.$newsArticles[$index_update3]->blogTitle.'" href="'.$newsArticles[$index_update3]->url.'">'.$text30.'</a></li></ul>';
?>
<li style="float:left;display:inline;width:338px;margin-left:2px;margin-right:2px;"><?php echo $var_text;?>
<div class="clearFix spacer5"></div>
<p style="margin: 0 3px 0 23px">
<a class="flRt" onclick="trackEventByGA('homepageNewsArtcileclick',this.innerHTML);" href="<?php echo SHIKSHA_HOME.'/news';?>">View all News</a></p>
</li>
<?php $index_update = ++$index_update3;endwhile;?>
</ul>
<?php endif;?>
<div class="clearFix spacer20"></div>
<!--<div class="profile-view">
<a href="<?php echo SHIKSHA_HOME.'/blogs/shikshaBlog/showArticlesList?type=kumkum';?>">View all articles from Mrs. Tandon's books here</a>
</div>-->
