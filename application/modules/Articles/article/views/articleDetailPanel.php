<input type="hidden" id="blogid_unified" value="<?php echo $blogId; ?>"/>
<input type="hidden" id="blogurl_unified" value="<?php echo $blogObj->getUrlNew(); ?>"/>
<?php
	$ArticleReference  = '';
	$bookName  = '';
	$chapterNumber = '';
	$chapterName = '';
	$userGroup = isset($validateuser[0]['usergroup'])?$validateuser[0]['usergroup']:'normal';

	if($blogObj->getBookChapterNumber() != '') {
		$chapterNumber = 'No. <b>'. $blogObj->getBookChapterNumber() .'</b>';
	}
	if($blogObj->getBookChapterName() != '') {
		$chapterName = 'titled <b>'. $blogObj->getBookChapterName() .'</b>';
	}
	if($blogObj->getBookName() != '') {
		$bookName = '<b>'. $blogObj->getBookName() .'</b>';
	}
	if($blogObj->getBookName() != '') {
		$ArticleReference = 'This Article is taken from <b>Mrs. Kum Kum Tandon\'s</b> book '.$bookName.'  chapter '.$chapterNumber .' '. $chapterName;
	}
    $userName = isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:'';
    $param = base64_encode(serialize(array('userName' => $userName ,'blogName' => $blogObj->getTitle())));
    
    // $this->load->view("search/searchOverlay",array('subject' => 'Shiksha Article - '.$blogObj->getTitle(),'extraParams' => $param));
    $blogDescriptionObj = $blogObj->getDescription();    
?>
<script>
//Added by Ankur to add VCard on all AnA pages: Start
var userVCardObject = new Array();
</script>
<div id="userNameImageDiv" style="display:none"></div>
<div style="display:none;" id="abuseFormText"></div>
<!-- Start of Report abuse confirmation overlay -->
<div style="display:none;" id="reportAbuseConfirmationDiv">
	<div>
		<div style="padding:10px">
			<div class="lineSpace_5p">&nbsp;</div>
			<div align="center"><span id="reportAbuseConfirmation" style="font-size:14px;"></span></div>
			<div class="lineSpace_5p">&nbsp;</div>
			<div align="center"><input type="button" value="OK" class="spirit_header RegisterBtn" onClick="javascript:hideOverlay();" /></div>
			<div class="lineSpace_5p">&nbsp;</div>
		</div>
	</div>
</div>
<!-- End of Report abuse confirmation overlay -->
			<!--BlogPanel-->
<div>
    <?php 
        if($blogId != '13726' || 1){
            ?>
            <div class="article-header">
                <div class="float_R">
                    <div id="articleDeleteMsg" class="bld fontSize_16p" style="display:none"></div>
                    <div id="articleOperations">
                        <?php if(is_array($validateuser[0]) && ($blogObj->getCreatorId() == $validateuser[0]['userid'] || $validateuser[0]['usergroup'] == 'cms')){ ?>
                        <button  class="btn-submit13 w3" type="Submit" onclick="location.replace('/enterprise/Enterprise/addBlogCMS/<?php echo $blogId.'/'.$blogObj->getStatus(); ?>');">
                            <div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Edit Article</p></div>
                        </button>
                        &nbsp;
                        <button  class="btn-submit5 w3" type="button" onclick="deleteArticle('<?php  echo $blogId; ?>');">
                            <div class="btn-submit5"><p class="btn-submit6">Delete Article</p></div>
                        </button>
                        <?php } ?>
                    </div>
                </div>
                
                <div>
                    <h1><?php if($blogObj->getTitle()!=''):echo $blogObj->getTitle(); endif; ?></h1>
                    <!--<meta content="<?php //$this->load->view('blogs/schemaContent'); ?>">-->

                    <p class="post-details">
                        <?php
                            $commentReplyCountForTopic = isset($commentReplyCountForTopic)?$commentReplyCountForTopic: 0;
                            switch($commentReplyCountForTopic) {
                                case 0: $commentsCaption = '<span><span id="commentCountHolder">Ask or</span> Comment</span>'; break;
                                case 1: $commentsCaption = '<span><span id="commentCountHolder">1</span> Comment</span>'; break;
                                default: $commentsCaption = '<span><span id="commentCountHolder">'.$commentReplyCountForTopic.'</span> Comments</span>'; break;
                            }
                        ?>
                    </p>
		
                </div>


                <?php 
                if(!empty($blogDescriptionObj)) { 
			        $lastModifiedDate = $blogObj->getLastModifiedDate();
                    $differenceBetweenDates = strtotime($lastModifiedDate)- strtotime($blogObj->getCreationDate());
		             ?>
                    <div class="blog-cont">
                        <?php 
                        if($differenceBetweenDates<=0) { 
                            ?>
                            <span content="<?php echo date('Y-m-d',strtotime($blogObj->getCreationDate()));?>T<?php echo date('h:i A',strtotime($blogObj->getCreationDate())); ?>">
                                <?php echo date ("M j, Y, h.iA", strtotime($blogObj->getCreationDate())) . ' IST '; ?>
                            </span>
                            <?php
                        }
                        else{ 
                            ?>
                            <span content="<?php echo date('Y-m-d',strtotime($blogObj->getLastModifiedDate()));?>T<?php echo date('h:i A',strtotime($blogObj->getLastModifiedDate())); ?>">
                                <?php echo "Updated on: ".date ("M j, Y, h.iA", strtotime($blogObj->getLastModifiedDate())) . ' IST '; ?>
                            </span>
                            <?php
                        }

                        if($blogObj->getType() == 'kumkum') {
                            ?>
                            by 
                            <a rel="author" target="_blank" href="<?php echo SHIKSHA_HOME;?>/shikshaHelp/ShikshaHelp/kumkum" title="Kumkum Tandon, Renowned Career Counsellor">
                                <span>
                                    <span>Kumkum Tandon</span>
                                </span>
                            </a>, Renowned Career Counsellor
                            <?php 
                        } 
                        else if($blogObj->getType() == 'ht') {
                            echo 'Powered by HT Horizons';
                        } 
                        else {
                            if($authorUrl!=''){ ?>
                                by <a rel="author" target="_blank" href="<?php echo $authorUrl;?>/"><span> <span><?php echo $authoruserName;?></span></span></a>
                            <?php }
                            else if ($externalUser=='true'){
                                ?>by <?php echo $authoruserName;
                            }
                            else{
                                ?>by Shiksha <?php
                            }
                        }
                        ?>
                    </div>                                    
                    <?php 
                }
                ?>

                <div class="social-cont">
                    <div class="socialShareADPDeskTop">
                        <?php $this->load->view("mcommon5/socialSharingBand", array('widgetPosition' => 'ADP_Top','fromWhere'=>'desktop')); ?>
                    </div>
                    <div class="comment-section">
                        <a href="<?php if($tab_required_course_page){ ?>javascript:void(0);<?php } else {?>#blogCommentSection<?php } ?>" class="art-comnts"><?php echo $commentsCaption; ?></a>
                    </div>
                </div>
	 
            </div>

            <!-- schema -->
            <?php 
            $this->load->view('article/articleSchemaMarkup');
        } 
    ?>
           
    <div>
        <?php 
            if($ArticleReference!=''){ 
                ?>
                <div class="spacer8 clearFix"></div>
                <div><?php echo $ArticleReference ; ?></div>
                <?php 
            } 
        ?>
    </div>

    <?php
        if(!empty($blogDescriptionObj)) {
            ?>
            <div id="articleP txt_align_j" class="blog-cont">
                <?php 
                    switch($blogObj->getBlogLayout()){
						case 'qna':$this->load->view('article/blogQnaDetail');
									break;
						case 'slideshow':$this->load->view('article/blogSlideShowDetails');
									break;
						default: echo '<p style="margin: 0px; padding: 0px; height: 0px;">&nbsp;</p>'.addAltText($blogObj->getTitle(), $blogDescriptionObj[$pageNum]->getDescription(), 1);
					}?>

            

            </div>
            <div class="spacer10 clearFix"></div>

            <div class="socialShareADPDeskBtm">
                <?php $this->load->view("mcommon5/socialSharingBand", array('widgetPosition' => 'ADP_Bottom','fromWhere'=>'desktop')); ?>
            </div>
            <div class="spacer10 clearFix"></div>
            <?php $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerType' => 'content','bannerPlace' => 'C_C1'));?>

            <div>
                <?php
                    if($blogObj->getType() == 'ht') {
                        echo '<a href="/blogs/shikshaBlog/showArticlesList?type=ht&c='. rand() .'">View all HT Horizons\' articles</a>';
                    }
                ?>
                <div class="spacer10 clearFic"></div>
                <?php
					if($blogObj->getBlogLayout()!="qna" && $blogObj->getBlogLayout()!="slideshow"){
						$lastBlogPageTag = $nextBlogPageTag = $lastPageTag = '';
						$nextBlogPage  = $lastBlogPage = $currentPage = 0;
						$pageNumber = 0;
						foreach($blogPagesIndex as $blogPageNum => $blogPageTag) {
							++$pageNumber;
							if($currentPage > 0) {
								$nextBlogPage = $pageNumber;
								$nextBlogPageTag = $blogPageTag == '' ? 'Next Page >>' : 'Next Page - '. $blogPageTag .' >>';
							}
							if ($blogPageNum == $blogDescriptionObj[$pageNum]->getDescriptionId()) {
								if($pageNumber > 1) {
									$lastBlogPageTag = $lastPageTag == '' ? '<< Previous Page' : '<< Previous Page - '. $lastPageTag;
									$lastBlogPage = $pageNumber;
								}
								$currentPage = $pageNumber;
							}
							if($nextBlogPage > 0) { break; }
							$lastPageTag = $blogPageTag;
						}
					}
                ?>
	
                <div class="wdh100">
                    <?php
                        $urlseg = $this->uri->segment(1);
                        $url_segments = explode("-", $urlseg);
                        if ($url_segments[0] != 'getArticleDetail') {
                            if ($lastBlogPage == 0) { 
                                $lastBlogPage = 1;
                            }
                            $next = $lastBlogPage + 1;
                            $prev = $lastBlogPage - 1;
                            if ($prev == 0) {
                                $prev = 1;
                            }

                            //$next = $lastBlogPage+2;
                            //$prev = $lastBlogPage;
                            $BASEURL = substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],"".$blogId)+strlen($blogId))."-";
                            ?>
                            <?php 
                                if($nextBlogPageTag!=''){ 
                                    ?>
                                    <div class="flRt"><a href="<?php echo $BASEURL . $next; ?>" class="bld"><?php echo $nextBlogPageTag ;?></a></div>
		                             <?php 
                                }
                                if($lastBlogPageTag!=''){ 
                                    ?>
                                    <div class="flLt"><a href="<?php echo $BASEURL . $prev; ?>" class="bld"><?php echo  $lastBlogPageTag ; ?></a></div>
                        			<?php 
                                } 
                            ?>

                            <?php
                        } 
                        else {
                            if($nextBlogPageTag!=''){ 
                                ?>
                                <div class="flRt"><a href="<?php echo substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'],'?')) .'?token=aa&page='. $nextBlogPage; ?>" class="bld"><?php echo $nextBlogPageTag ;?></a></div>
                                <?php 
                            }
                            if($lastBlogPageTag!=''){ 
                                ?>
                                <?php 
                                if($lastBlogPage!=0){
                                    ?>
                                    <div class="flLt"><a href="<?php echo substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'],'?')) .'?token=aa&page='. ($lastBlogPage-1); ?>" class="bld"><?php echo  $lastBlogPageTag ; ?></a></div>
                                    <?php
                                }
                            }
                        }
                    ?>
				 
                    <div class="clearFix"></div>

                </div>
                <div class="spacer10 clearFix"></div>
            </div>

	       <?php //$polls = Modules::run('blogs/shikshaBlog/showPoll', $blogId);echo $polls; ?>
            <?php 
        } 
    ?>
        <?php 
            if($streamCheck == 'fullTimeMba'){
                ?>
                <div class="clearFix"></div>
                <?php $this->load->view('RecentActivities/InlineWidget'); ?>
                    <div class="clearFix"></div>
                <?php     
            }
        ?>
        


    <div class="lineSpace_20">&nbsp;</div>
    
    <div class="discussion-section">
        <div><a name="blogCommentSection" id="blogCommentSection">&nbsp;</a></div>
        <h2 class="discussion-title">Discussion Board</h2>
        
        <!-- comment box start here -->
        <div id="topicContainer">
            <div class="spacer3 clearFix"></div>
            <?php
                $isCmsUser =0;
                if((is_array($validateuser))&&(strcmp($validateuser[0]['usergroup'],'cms') == 0)){
                   $isCmsUser = 1;
                }
                $url = site_url("messageBoard/MsgBoard/replyMsg");
                $isCmsUser = 0;
                $topicUrl = site_url('messageBoard/MsgBoard/topicDetails').'/'.$topicId;
                $userProfile = site_url('getUserProfile').'/';
                $commentData['url'] = $url;
                $commentData['threadId'] = $topicId;
                $commentData['isCmsUser'] = $isCmsUser;
                $commentData['topicUrl'] = $topicUrl;
                $commentData['userProfile'] = $userProfile;
                $commentData['fromOthers'] = 'blog';
                $commentData['url'] = $url;
                //$commentData['threadId'] = $threadId;
                $commentData['maximumCommentAllowed'] = 4;
                $commentData['pageKeySuffixForDetail'] = 'BLOG_BLOGDETAIL_MIDDLEPANEL_';
                $commentData['functionToCall'] = 'incrementCommentCount()';
                $commentData['articleId'] = $blogId;
                $commentData['entityTypeShown'] = "Blog Comment";
                //below lone is used for conversion tracking purpose
                $commentData['trackingPageKeyId']=207;
                $commentData['rtrackingPageKeyId']=$rtrackingPageKeyId;
                $commentData['ratrackingPageKeyId']=$ratrackingPageKeyId;
             ?>
            <div><?php $this->load->view('messageBoard/showEntityComments',$commentData); ?></div>
        </div>
        <div id="topicContainer">
            <?php 
                $pageData = array();
                $pageData['pageId'] = $blogId;
                $pageData['pageType'] = 'ADP';
                $pageData['feedbackWidgetTypeClass'] = 'aligned';
                $this->load->view('mcommon5/feedbackWidget/feedback', $pageData);
            ?>
        </div>

        <!--       LATEST NEWS WIDGET      -->
        <?php /*if(!empty($latestNews)) echo $this->load->view("blogs/latestNews",array('articleWidgetsData' => $latestNews));*/ ?>

        <!--      QUICK LINKS WIDGET      -->
        <?php /*if(!empty($quickLinks)){ echo "<div class='lineSpace_15'>&nbsp;</div>";
            echo $this->load->view("messageBoard/quickLinks",array('articleWidgetsData' => $quickLinks, 'moduleName' => 'Articles' ));} */
        ?>

        <?php
            if($CategoryId == '56'){
                $frompage = 'engineeringArticles';
                echo Modules::run('CA/MentorController/getMentorInlineWidget', $frompage, $CategoryId);
            }
        ?>
        <?php /*
        <!--Start_google_Banner-->
        <div style="margin-top:40px;margin-bottom:20px;">
            <?php
            $bannerProperties = array('pageId'=>'ARTICLE_DETAILS', 'pageZone'=>'BOTTOMBANNER');
            $this->load->view('common/banner', $bannerProperties);
            ?>
        </div>
        <!--End_google_Banner-->
        */ 
        ?>
	</div>

    <?php $this->load->view('blogs/recentArticles'); ?>
    <?php echo Modules::run('blogs/shikshaBlog/showArticleRecommendation', $blogId, $relatedBlogs); ?>

</div>
	


<!--BlogPanel-->
<?php
	echo "<script language=\"javascript\"> ";
	if($tabselected == 1){
        echo "selectTab(document.getElementById('tab1'));";
    }
	elseif($tabselected == 2){
	    echo "selectTab(document.getElementById('tab2'));";
    }
	echo "</script>";
?>

<script>
function registerNowOnBlog(source,articlePageLogin,trackingPageKeyId){
    data = {};
    data['referer'] = window.location;
    data['source'] = source;
    data['hideLoginLink'] = articlePageLogin;
    data['trackingPageKeyId'] = trackingPageKeyId;
    data['callback'] = function(data) {
        if (data['status'] != 'LayerClosed') { 
            window.location.reload(true); 
        }
    };
    shikshaUserRegistration.showRegisterFreeLayer(data);              
}
</script>

<?php //if(isset($validateuser[0]['userid'])){?>			
<script>
//checkForLinkDLinkOption('<?php echo $blogInfo[0]['boardId'];?>','Article');
</script>
<?php //}?>

