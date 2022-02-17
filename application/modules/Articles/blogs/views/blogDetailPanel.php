<input type="hidden" id="blogid_unified" value="<?php echo $blogId; ?>"/>
<input type="hidden" id="blogurl_unified" value="<?php echo $blogInfo[0]['url']; ?>"/>
<input type="hidden" id="article_category_id_unified" value="<?php echo $unifiedCategoryId; ?>"/>
<?php
	$ArticleReference  = '';
	$bookName  = '';
	$chapterNumber = '';
	$chapterName = '';
	$userGroup = isset($validateuser[0]['usergroup'])?$validateuser[0]['usergroup']:'normal';

	if($blogInfo[0]['chapterNumber'] != '') {
		$chapterNumber = 'No. <b>'. $blogInfo[0]['chapterNumber'] .'</b>';
	}
	if($blogInfo[0]['chapterName'] != '') {
		$chapterName = 'titled <b>'. $blogInfo[0]['chapterName'] .'</b>';
	}
	if($blogInfo[0]['bookName'] != '') {
		$bookName = '<b>'. $blogInfo[0]['bookName'] .'</b>';
	}
	if($blogInfo[0]['bookName'] != '') {
		$ArticleReference = 'This Article is taken from <b>Mrs. Kum Kum Tandon\'s</b> book '.$bookName.'  chapter '.$chapterNumber .' '. $chapterName;
	}
    $userName = isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:'';
    $param = base64_encode(serialize(array('userName' => $userName ,'blogName' => $blogInfo[0]['blogTitle'])));
    
    $this->load->view("search/searchOverlay",array('subject' => 'Shiksha Article - '.$blogInfo[0]['blogTitle'],'extraParams' => $param));
    
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

	<?php
		$reportChangeParams = array('listing_type' => 'blogs' ,  'type_id' => $blogInfo[0]['blogId']);
		$this->load->view('listing/reportChange',$reportChangeParams);
	?>
			<!--BlogPanel-->
			<div>
                <div class="article-header">
                    <div class="float_R">
                        <div id="articleDeleteMsg" class="bld fontSize_16p" style="display:none"></div>
                        <div id="articleOperations">
                            <?php if(is_array($validateuser[0]) && ($blogInfo[0]['userId'] == $validateuser[0]['userid'] || $validateuser[0]['usergroup'] == 'cms')){ ?>
                            <button  class="btn-submit13 w3" type="Submit" onclick="location.replace('/enterprise/Enterprise/addBlogCMS/<?php echo $blogInfo[0]['blogId'].'/'.$blogInfo[0]['status']; ?>');">
                                <div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Edit Article</p></div>
                            </button>
                            &nbsp;
                            <button  class="btn-submit5 w3" type="button" onclick="deleteArticle('<?php  echo $blogInfo[0]['blogId']; ?>');">
                                <div class="btn-submit5"><p class="btn-submit6">Delete Article</p></div>
                            </button>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <div>
                        <h1 itemprop="name"><?php if(isset($blogInfo[0]['blogTitle'])):echo $blogInfo[0]['blogTitle']; endif; ?></h1>
			<!--<meta itemprop="articleBody" content="<?php //$this->load->view('blogs/schemaContent'); ?>">-->

                        <p class="post-details">
                            <?php
                                $commentCountForTopic = isset($commentCountForTopic)?$commentCountForTopic: 0;
                                switch($commentCountForTopic) {
                                    case 0: $commentsCaption = '<span><span id="commentCountHolder">Ask or</span> Comment</span>'; break;
                                    case 1: $commentsCaption = '<span><span id="commentCountHolder">1</span> Comment</span>'; break;
                                    default: $commentsCaption = '<span><span id="commentCountHolder">'.$commentCountForTopic.'</span> Comments</span>'; break;
                                }
                            ?>
                        </p>
			
                    </div>


                    <?php if(isset($blogInfo[0]['blogText'])) { 
				$lastModifiedDate = $blogInfo[0]['lastModifiedDate'];
                                $differenceBetweenDates = strtotime($lastModifiedDate)- strtotime($blogInfo[0]['creationDate']);
			?>
                        <div class="blog-cont">
			<?php if($differenceBetweenDates<=0) { ?>
                                 <span itemprop="datePublished" content="<?php echo date('Y-m-d',strtotime($blogInfo[0]['creationDate']));?>T<?php echo date('h:i A',strtotime($blogInfo[0]['creationDate'])); ?>">
                            <?php
                                echo date ("M j, Y, h.iA", strtotime($blogInfo[0]['creationDate'])) . ' IST ';
                                echo "</span>";
                            }else{ ?>

                                <span itemprop="datePublished" content="<?php echo date('Y-m-d',strtotime($blogInfo[0]['lastModifiedDate']));?>T<?php echo date('h:i A',strtotime($blogInfo[0]['lastModifiedDate'])); ?>">
                            <?php
                                echo "Updated on: ".date ("M j, Y, h.iA", strtotime($blogInfo[0]['lastModifiedDate'])) . ' IST ';
                                echo "</span>";
                                }

                                if($blogInfo[0]['blogType'] == 'kumkum') {
                            ?>
                                by <a rel="author" target="_blank" href="<?=SHIKSHA_HOME?>/shikshaHelp/ShikshaHelp/kumkum" title="Kumkum Tandon, Renowned Career Counsellor"><span itemprop="author" itemscope itemtype="https://schema.org/Person"> <span itemprop="name">Kumkum Tandon</span></span></a>, Renowned Career Counsellor
                            <?php } else if($blogInfo[0]['blogType'] == 'ht') {
                                echo 'Powered by HT Horizons';
                                } else {
                                    if($authorUrl!=''){ ?>
                                        by <a rel="author" target="_blank" href="<?php echo $authorUrl;?>/"><span itemprop="author" itemscope itemtype="https://schema.org/Person"> <span itemprop="name"><?php echo $authoruserName;?></span></span></a>
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
                    <?php }
                    ?>

                    <div class="social-cont">
                        <div class="socila-icons">
                        <table cellpadding="0" cellspacing="0" border="0">
        <tr>
        <td>

<?php
    $url_parts = parse_url("http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
    $constructed_url = $url_parts['scheme'] . '://' . $url_parts['host'] . (isset($url_parts['path'])?$url_parts['path']:'');
    $urlArticle = urlencode($constructed_url);
?>

                                        <div class="flLt">
                                            <iframe src="https://www.facebook.com/plugins/like.php?href=<?php echo $urlArticle; ?>&amp;layout=button_count&amp;show_faces=false&amp;width=450&amp;action=like&amp;font=tahoma&amp;stream=true&amp;header=true&amp;appId=<?php echo FACEBOOK_API_ID; ?>" colorscheme=light" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:75px; height:25px"></iframe>
                                        </div>
                                        <div class="flLt">
                                            <iframe id="twitterFrame" allowtransparency="true" frameborder="0" scrolling="no"  src="about:blank" style="width:82px; height:20px;"></iframe>
                                        </div>
                                        <div class="flLt">
                                            <g:plusone size="medium"></g:plusone>
                                            <script type="text/javascript">
                                            (function() {
                                            var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                                            po.src = 'https://apis.google.com/js/plusone.js';
                                            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                                            })();
                                            </script>
                                        </div>
<div class="flLt">


            <?php $source = 'BLOG_BLOGDETAIL'; if(!is_array($validateuser[0])){ ?>
            <a href="javascript:void(0);" class="mailArw" onClick="if(checkForUnifiedObjectAndMethod()){registerNowOnBlog('emailthiswidget','false','209');} return false;"  title="Email this"></a>
            <?php } else { ?>
            <a href="javascript:void(0);" class="mailArw" onClick="showSearchMailOverlay('blogEmailThis','<?php echo $blogId; ?>','<?php echo $blogInfo[0]['url']; ?>');return false;"  title="Email this"></a>
            <?php } ?>

            </div>
        
			    
                            <?php /*if(!isset($validateuser[0]['userid'])){?>
                            <div class="flLt">
                                    <a href="javascript:void(0);" onClick="eventTriggered = 'followArticle'; showOverlayForFConnect();"><img src = "/public/images/fb_followBtn.gif" border="0"></a>
                            </div>
                            <?php }else{?>
                            <div class="flLt" id = "article_FButton" style="display:none">
                                    <a href="javascript:void(0);" onClick="eventTriggered = 'followArticle'; showOverlayForFConnect();"><img src = "/public/images/fb_followBtn.gif" border="0"></a>
                            </div>
                            <div class="flLt" id = "article_Shiskha_FButton" style="display:none">
                                    <a href="javascript:void(0);" onClick="checkFshareOverlay('loggedInState');"><img src = "/public/images/fb_followBtn.gif"></a>
                            </div>
                            <?php } */?>
                            <script>
                            var blogId_f = <?php echo $blogInfo[0]['blogId'];?>;
                            var boardId_f = <?php echo $blogInfo[0]['boardId'];?>;
                            var action = 'followArticle';
                            paraString_facebook = 'action='+action+'&blogId='+blogId_f+'&boardId='+boardId_f;
                            eventTriggered = action;
                            </script>
                            </td>
                            </tr>
                        </table>
			   
                    </div>
                 
                    <div class="comment-section">
                        <a href="<?php if($tab_required_course_page){ ?>javascript:void(0);<?php } else {?>#blogCommentSection<?php } ?>" <?php if($tab_required_course_page){ ?>onclick="blogCommentSectionWithCPGSHeader();"<?php } ?> class="art-comnts"><?php echo $commentsCaption; ?></a>
                    </div>
		 
                    </div>
		 
                </div>
                       
                <div>
		    
                    <?php if($ArticleReference!=''){ ?>
                    <div class="spacer8 clearFix"></div>
                    <div><?php echo $ArticleReference ; ?></div>
                    <?php } ?>
			
                </div>

                <div id="articleP txt_align_j" class="blog-cont">
                    <?php
                        if(isset($blogInfo[0]['blogText'])) {
                            $blogDescription = json_decode($blogInfo[0]['blogText'], true);
							switch($blogInfo[0]['blogLayout']){
								case 'qna':$this->load->view('blogs/blogDetailsQnA');
											break;
								case 'slideshow':$this->load->view('blogs/blogDetailsSlideShow');
											break;
								default: echo '<p style="margin: 0px; padding: 0px; height: 0px;">&nbsp;</p>'.addAltText($blogInfo[0]['blogTitle'],$blogDescription[0]['description']);
							}
              
                     ?>
		     
                </div>
                <div class="spacer10 clearFix"></div>
                    <div>
                        <?php
                            if($blogInfo[0]['blogType'] == 'ht') {
                                echo '<a href="/blogs/shikshaBlog/showArticlesList?type=ht&c='. rand() .'">View all HT Horizons\' articles</a>';
                            }
                        ?>
                        <div class="spacer10 clearFic"></div>
                        <?php
							if($blogInfo[0]['blogLayout']!="qna" && $blogInfo[0]['blogLayout']!="slideshow"){
								$lastBlogPageTag = $nextBlogPageTag = $lastPageTag = '';
								$nextBlogPage  = $lastBlogPage = $currentPage = 0;
								$pageNumber = 0;
								foreach($blogPagesIndex as $blogPageNum => $blogPageTag) {
								++$pageNumber;
								if($currentPage > 0) {
									$nextBlogPage = $pageNumber;
									$nextBlogPageTag = $blogPageTag == '' ? 'Next Page >>' : 'Next Page - '. $blogPageTag .' >>';
								}
								if ($blogPageNum == $blogDescription[0]['descriptionId']) {
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

if ($lastBlogPage == 0) { $lastBlogPage = 1;}
$next = $lastBlogPage + 1;
$prev = $lastBlogPage - 1;
if ($prev == 0) {
$prev = 1;
}

//$next = $lastBlogPage+2;
//$prev = $lastBlogPage;
$BASEURL = substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],"".$blogId)+strlen($blogId))."-";
?>
			<?php if($nextBlogPageTag!='')	{ ?>
                            <div class="flRt"><a href="<?php echo $BASEURL . $next; ?>" class="bld"><?php echo $nextBlogPageTag ;?></a></div>
			<?php }
			      if($lastBlogPageTag!=''){ ?>
                            <div class="flLt"><a href="<?php echo $BASEURL . $prev; ?>" class="bld"><?php echo  $lastBlogPageTag ; ?></a></div>
			<?php } ?>

<?php
} else {
?>
			<?php if($nextBlogPageTag!='')  { ?>
                            <div class="flRt"><a href="<?php echo substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'],'?')) .'?token=aa&page='. $nextBlogPage; ?>" class="bld"><?php echo $nextBlogPageTag ;?></a></div>
			<?php }
			       if($lastBlogPageTag!=''){ ?>
                          <?php   if($lastBlogPage!=0): ?><div class="flLt"><a href="<?php echo substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'],'?')) .'?token=aa&page='. ($lastBlogPage-1); ?>" class="bld"><?php echo  $lastBlogPageTag ; ?></a></div><?php endif; ?>
			<?php } ?>
<?php
}
?>
					 
                        <div class="clearFix"></div>
	
                        </div>
                        <div class="spacer10 clearFix"></div>
                        <?php  if($blogInfo[0]['tags'] != '' && false) { ?>
                            <div><span class="bld">Keywords</span> : <?php echo $blogInfo[0]['tags']; ?></div>
                            <div class="spacer10 clearFix"></div>
                        <?php } ?>
                    </div>
 
						<?php $polls = Modules::run('blogs/shikshaBlog/showPoll',$blogId);echo $polls; ?>
                        <?php } ?>
                        <div class="fb-recommend">
			    <iframe src="//www.facebook.com/plugins/share_button.php?href=<?php echo $urlArticle; ?>&amp;layout=button_count&amp;show_faces=true&amp;width=450&amp;action=share&amp;font=tahoma&amp;stream=true&amp;header=true&amp;appId=<?php echo FACEBOOK_API_ID; ?>" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:60px;" allowTransparency="true"></iframe>
                        </div>
                        <?php if($subcat_id_course_page == 23){
                            ?>
                                <div class="clearFix"></div>
                                <?php $this->load->view('RecentActivities/InlineWidget'); ?>
                                <div class="clearFix"></div>
                            <?php     
                        }
                        ?>
                       

                        <?php echo Modules::run('blogs/shikshaBlog/showArticleRecommendation',$blogId,$blogInfo[0]['boardId'],$blogInfo[0]['countryId'],$relatedBlogs,$blogInfo[0]['blogLayout'],$polls); ?>


			<div class="lineSpace_20">&nbsp;</div>
                    
                    <div class="discussion-section">
                    <div><a name="blogCommentSection" id="blogCommentSection">&nbsp;</a></div>
                    <h2 class="discussion-title">Discussion Board</h2>
                    
                    <!-- comment box start here -->
                    <div id="topicContainer">
                        <div class="spacer3 clearFix"></div>
                        <?php
                         $isCmsUser =0;
                         if((is_array($validateuser))&&(strcmp($validateuser[0]['usergroup'],'cms') == 0))
                                 $isCmsUser = 1;
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
                        $commentData['articleId'] = $blogInfo[0]['blogId'];
                        $commentData['entityTypeShown'] = "Blog Comment";
                        //below lone is used for conversion tracking purpose
                        $commentData['trackingPageKeyId']=207;
                        $commentData['rtrackingPageKeyId']=$rtrackingPageKeyId;
                        $commentData['ratrackingPageKeyId']=$ratrackingPageKeyId;
                         ?>
                        <div><?php $this->load->view('messageBoard/showEntityComments',$commentData); ?></div>
                    </div>

                    <!--       LATEST NEWS WIDGET      -->
                    <?php if(!empty($latestNews)) echo $this->load->view("blogs/latestNews",array('articleWidgetsData' => $latestNews)); ?>

                    <!--      QUICK LINKS WIDGET      -->
                    <?php if(!empty($quickLinks)){ echo "<div class='lineSpace_15'>&nbsp;</div>";
			echo $this->load->view("messageBoard/quickLinks",array('articleWidgetsData' => $quickLinks, 'moduleName' => 'Articles' ));} ?>

                        <!--Start_google_Banner-->
                        <div style="margin-top:40px;margin-bottom:20px;">
                                                        <?php
                                                        $bannerProperties = array('pageId'=>'ARTICLE_DETAILS', 'pageZone'=>'BOTTOMBANNER');
                                                        $this->load->view('common/banner', $bannerProperties);
                                                        ?>
                                                </div>
                        <!--End_google_Banner-->

		</div>
            </div>
				
			

			<!--BlogPanel-->
			<?php
				echo "<script language=\"javascript\"> ";
				if($tabselected == 1)
				echo "selectTab(document.getElementById('tab1'));";
				elseif($tabselected == 2)
				echo "selectTab(document.getElementById('tab2'));";
				echo "</script>";
			?>

<script>
arr_unified[0] = '2';
arr_unified[1] = <?php echo $unifiedCategoryId ?>;
var show_unified_regis = true;
// if(arr_unified[1] && (arr_unified[1] == '4' || arr_unified[1] == '9' || arr_unified[1] == '11')) {
//         arr_unified[1] = '';
// 	//show_unified_regis = false;
// }

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

<?php if(isset($validateuser[0]['userid'])){?>			
<script>
checkForLinkDLinkOption('<?php echo $blogInfo[0]['boardId'];?>','Article');
</script>
<?php }?>

