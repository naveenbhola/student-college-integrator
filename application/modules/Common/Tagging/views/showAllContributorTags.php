<?php
ob_start('compressHTML');
$preMetaString = $startOffSet == 1 ? '' :'Page '.$startOffSet." - ";
$tempJsArray = array('common');
$tempCssArray = array('showAllContributorTags','ana');
$bannerProperties = array('pageId'=>'TAGS', 'pageZone'=>'HEADER');
$headerComponents = array(
                'css'   =>  array(),
                'js' => array(),
                'jsFooter'=>    $tempJsArray,
                'cssFooter' => $tempCssArray,
                'showBottomMargin' => false,
                'product' => 'anaDesktopV2',
                'title' =>      $preMetaString."Popular Tags - Shiksha.com",
                'metaDescription' => $preMetaString."Browse list of education topics to find questions, answers, and discussions on colleges, courses, exams, careers, admission, and more.",
                'canonicalURL' =>$canonicalURL,
		'bannerProperties' => $bannerProperties,
    'lazyLoadJsFiles' => true
);
$this->load->view('common/header', $headerComponents);
?>
  <body>



    <div id="main-wrapper">
       <div id="content-wrapper">
         <div id="main-wrapper" class="wrapperFxd">

           <!--pick html from here-->
            <div id="profile_builder" class="wrapper">
               <div class="tagDtls">
                 <h2 class="tagTitl">Follow Topics</h2>
                 <p>You may now add more topics that you want to follow, and answer questions on to share your knowledge.</p>
                 <a href="<?=SHIKSHA_HOME?>/userprofile/edit#Activity#TagsFollowed" class="viewAll">View all followed tags</a>
               </div>
               <div class="tagSpace">
                 <div class="tagBlock">
                   <div class="tagInput">
                     <input type="text" name="" id="tagSearch" placeholder="Type and select topics of your interest" />
		    <div class="search-college-layer" id="tagSearch_container" style="display:none" >
		</div>

                     <label for="place_ico"><span class="search icon"></span></label>
                   </div>
                   <div class="addOnTags">
                   </div>
                 </div>
                 <div class="tagsList">
                     <h2 class="tagHeading">Most Popular Topics</h2>
                     <div class="new_Label">
			<?php
			foreach ($tags as $key => $value)
			{?>
                         <p><span class="tagLabel <?php if($followedTagsOnPage[$value[tag_id]]==="true"){?> active <?php }?>" id="popularTag<?=$value[tag_id];?>"><a href="<?=$value['url'];?>" class="spanText"><?=$value['tag_name'];?></a> <span class="ico_plus" onclick="toggleContributorTag('<?=$value[tag_id];?>','<?=$value[tag_name];?>','<?=$value['url'];?>','popularTags');"></span></span></p>

			<?php 
			}?>

                     </div>
                     <div class="align_cntr">
                       <div class="n-pagination">
                          <ul>
                                         <?php echo $paginationHTMLForGoogle;?>
                          </ul>
                        <p class="clr"></p>
                        </div>
                     </div>
                     <div class="align_cntr">
                         <a href="<?=SHIKSHA_ASK_HOME?>/unanswers" class="popUp_btn">Submit</a>
                     </div>
                 </div>
               </div>
            </div>
           <!--end of html-->

         </div>
       </div>
    </div>

  </body>
<script>
var GA_userLevel_AllTags = '<?php echo $GA_userLevel;?>'; 
  function LazyLoadAnADesktopCallback(){
        $LAB
      .script('//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api");?>',
                    '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ana_desktop");?>',
                    '//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("quesDiscPosting");?>'
                  )
      .wait(function(){
        initializeTagAutoSuggestor("showAllContributorTags");
      });
    }
</script>
<?php
    $this->load->view('common/footer');
    echo includeJSFiles('shikshaDesktopWebsiteTour');

ob_end_flush();
?>
