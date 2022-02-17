<?php 
	$url_parts = parse_url("http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
	$constructed_url = $url_parts['scheme'] . '://' . $url_parts['host'] . (isset($url_parts['path'])?$url_parts['path']:'');
	$urlContent = urlencode($constructed_url);
?>

<div class="article-content dyanamic-content" itemprop="articleBody">
    <div class="article-widget clearwidth" >
		<?php echo addAltText($content['data']['strip_title'],$content['data']['summary']); ?>  
</div>
<?php foreach($content['data']['sections'] as $index => $data ):?>
 
<div class="article-widget clearwidth">
		<h2 id="section_<?=$index;?>"><?php $index = $index+1; $index = $index.'.';
		if($content['data']['type']!='article')echo $index;?> <?php echo strip_tags($data['heading']) ;?></h2>
		<?php echo addAltText($content['data']['strip_title'],$data['details']);?>
		<!-- Download Guide Widget STARTS-->
	        <?php if($index == 1) : 	
	        $this->load->view('saContent/downloadGuideInMiddle');
	        endif;?>
		<!-- Download Guide Widget ENDS-->  
</div>
<?php endforeach;?>

</div>
<?php $this->load->view("categoryList/abroad/widget/scholarshipInterlinkingCards");
if($browsewidget['finalArray']['widgetType']=='university')
{
    $this->load->view('studyAbroadArticleWidget/studyAbroadListingUnivesityWidget');
}
elseif($browsewidget['finalArray']['widgetType']=='course')
{
    $this->load->view('studyAbroadArticleWidget/studyAbroadListingCourseWidget');
}
//echo Modules::run('studyAbroadArticleWidget/articleAbroadWidgets/getListingWidgetsOnArticles', $content['data']['content_id']);?>
<?php $this->load->view('saContent/saContentFeedback');?>
<div class="clearwidth">
	<div class="flLt">
		<iframe src="https://www.facebook.com/plugins/like.php?href=<?php echo $urlContent; ?>&amp;layout=button_count&amp;show_faces=false&amp;width=450&amp;action=like&amp;font=tahoma&amp;stream=true&amp;header=true&amp;appId=<?php echo FACEBOOK_API_ID; ?>"
		colorscheme=light " scrolling="no" frameborder="0" allowTransparency="true"
		style="border: none; overflow: hidden; width: 80px; height: 20px"></iframe>
	</div>

                                                        <div class="flLt" style="width:80px;">
                                                                <a href="https://twitter.com/share" class="twitter-share-button" >Tweet</a>
                                                                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                                                        </div>


	
</div>

