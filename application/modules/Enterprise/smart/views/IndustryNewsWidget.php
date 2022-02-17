<?php
if(isset($latestUpdates)):
$latestUpdates = json_decode($latestUpdates,false);
$latestUpdates = $latestUpdates->articles;
$count_latest_updates = count($latestUpdates);
if($count_latest_updates%4 == 0) {
	$count_latest_updates = $count_latest_updates/4;
} else {
	$count_latest_updates = intval($count_latest_updates/4)+1;
}
$count_flavours = count($flavoredArticle);
if($count_flavours>=$count_latest_updates) {
	$count_flavours = $count_latest_updates;
} else {
	$count_latest_updates = $count_flavours; 
}
?>
<ul class="latest-up-lists" style="width:<?php echo $count_latest_updates*342*4;?>px;" id="latest_update_ul_smart">
<?php $index_update = 0;while($index_update<$count_latest_updates*4):
$index_update1 = $index_update+1;
$index_update2 = $index_update+2;
$index_update3 = $index_update+3;
$txt_1 = trim(strip_tags($latestUpdates[$index_update]->blogTitle));
$txt_2 = trim(strip_tags($latestUpdates[$index_update1]->blogTitle));
$txt_3 = trim(strip_tags($latestUpdates[$index_update2]->blogTitle));
$txt_4 = trim(strip_tags($latestUpdates[$index_update3]->blogTitle));
//$text1 = $txt_1;
//$text2 = $txt_2;
//$text3 = $txt_3;
//$text30 = $txt_4;
if(strlen($txt_1)>40) {$text1 = wordLimiter(substr($txt_1,0,40),40)."...";} else {$text1 = $txt_1;}
if(strlen($txt_2)>40) {$text2 = wordLimiter(substr($txt_2,0,40),40)."...";} else {$text2 = $txt_2;}
if(strlen($txt_3)>40) {$text4 = wordLimiter(substr($txt_3,0,40),40);$text3=$text4."...";} else {$text3 = $txt_3;}
if(strlen($txt_4)>40) {$text40 = wordLimiter(substr($txt_4,0,40),40);$text30=$text40."...";} else {$text30 = $txt_4;}
$var_text = '<ul><li><a onclick="trackEventByGA(\'homepagelatestupdateclick\',this.innerHTML);" title="'.$latestUpdates[$index_update]->blogTitle.'" href="'.$latestUpdates[$index_update]->url.'">'.$text1.'</a></li>'.
'<li><a onclick="trackEventByGA(\'homepagelatestupdateclick\',this.innerHTML);" title="'.$latestUpdates[$index_update1]->blogTitle.'" href="'.$latestUpdates[$index_update1]->url.'">'.$text2.'</a><br/></li>'.
'<li><a  onclick="trackEventByGA(\'homepagelatestupdateclick\',this.innerHTML);" title="'.$latestUpdates[$index_update2]->blogTitle.'" href="'.$latestUpdates[$index_update2]->url.'">'.$text3.'</a></li>'.
'<li><a  onclick="trackEventByGA(\'homepagelatestupdateclick\',this.innerHTML);" title="'.$latestUpdates[$index_update3]->blogTitle.'" href="'.$latestUpdates[$index_update3]->url.'">'.$text30.'</a></li></ul>';
?>
<li style="float:left;display:inline;width:406px;margin-left:2px;margin-right:2px;"><?php echo $var_text;?>
<p class="tar"><a onclick="trackEventByGA('homepagelatestupdateclick',this.innerHTML);" href="<?php echo SHIKSHA_HOME.'/blogs/shikshaBlog/getAllLatestArticles';?>">View all News</a>&nbsp;</p>
</li>
<?php $index_update = ++$index_update3;endwhile;?>
</ul>
<?php endif;?>
<div class="clearFix spacer20"></div>
<!--<div class="profile-view">
<a href="<?php echo SHIKSHA_HOME.'/blogs/shikshaBlog/showArticlesList?type=kumkum';?>">View all articles from Mrs. Tandon's books here</a>
</div>-->
