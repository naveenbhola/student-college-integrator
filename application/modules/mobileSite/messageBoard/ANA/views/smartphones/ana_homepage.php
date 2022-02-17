<?php $this->load->view('/mcommon/header'); ?>
<div class="search-btn-back">
	<a href="/search/showSearchHome" class="gray-button2">Search Institutes & Courses</a>
</div>
<div id="head-sep"></div>
<?php

$categoryId = $pcategoryId;
$flag_UnansweredTopics = $pflag_UnansweredTopics;
$start = $pstart;
$rows = $prows; 
$countryId = $pcountryId;
$myqnaTab='answer';
$actionDone='default';

$displaycategory    = $categoryCountry[0]['category'];
$displaycategoryId  = $categoryCountry[0]['categoryId'];
$displaycountry     = $categoryCountry[0]['country'];
$displaycountryId   = $categoryCountry[0]['countryId'];

$pageurl = SHIKSHA_ASK_HOME . "/messageBoard/MsgBoard/discussionHome/" . $categoryId . "/" . $flag_UnansweredTopics . "/" . $countryId . "/" . $myqnaTab . '/' .  $actionDone .  "/" . $start . "/" . $rows;

if($flag_UnansweredTopics == '1')
{
  $pageurl  = SHIKSHA_ASK_HOME . "/messageBoard/MsgBoard/discussionHome/" . $categoryId . "/" . "3" . "/" . $countryId . "/" . $myqnaTab . '/' .  $actionDone .  "/" . "0" . "/" . "10"; 
  $sorthtml = "Sort by: <strong>All</strong> <span>|</span> <a href='".$pageurl."'>Unanswered</a>";
}

if($flag_UnansweredTopics == '3')
{
  $pageurl  = SHIKSHA_ASK_HOME . "/messageBoard/MsgBoard/discussionHome/" . $categoryId . "/" . "1" . "/" . $countryId . "/" . $myqnaTab . '/' .  $actionDone .  "/" . "0" . "/" . "10";
  $sorthtml = "Sort by: <a href='".$pageurl."'>All</a><span>|</span> <strong>Unanswered</strong>";
}
?> 
<script>
function update_category(url)
{
    window.location = url;
}
</script>

<div id="head-title">
	<h4 class="flLt"><?php echo $displaycategory; ?></h4>&nbsp;
    <div align="right" class="location">
        <?php 
        $categories = $categoryRepository->getSubCategories(1,'');
        ?>
        <select  onchange="update_category(value)" style="width:200px" >
          <?php
           foreach($categories as $category) {
            $catId = $category->getId();
            if($catId == $categoryId )
             {
                $selected = "selected";
            }
            else
            {
                $selected = "";
            }

            $pageurl = SHIKSHA_ASK_HOME . "/messageBoard/MsgBoard/discussionHome/" .  $catId . "/" . $flag_UnansweredTopics . "/" . $countryId . "/" . $myqnaTab . '/' .  $actionDone .  "/" . $start . "/" . $rows;
           ?>
          <option  <?php echo   $selected ; ?> value="<?php  echo $pageurl ; ?>" > <?php  echo $category->getName() ; ?></option>
          <?php } ?> 
      </select>
    </div>
    <span>&nbsp;</span>
    <div class="clearFix spacer5"></div>
</div>

<div id="content-wrap">
	<div class="clearFix spacer5"></div>
    <div class="sorting"><?php echo $sorthtml; ?></div>

	<div id="contents">
    	<ul>
            <?php
            if (count($results) <= 0 )
            {
                ?>
                <h1 class="no-result">Sorry, no results were found matching your selection.</h1>
                <?php
            }
            foreach($results as $result)
            {
                
                $descriptionD        = empty($result['descriptionD'])?"":$result['descriptionD'];
                $displayname        = empty($result['displayname'])?"":$result['displayname'];
                $creationDate        = empty($result['creationDate'])?"":$result['creationDate'];
                $listingTitle            = empty($result['listingTitle'])?"":$result['listingTitle'];
                $instituteurl           = empty($result['instituteurl'])?"":$result['instituteurl'];
                $url                         = empty($result['url'])?"":$result['url'];
                
                if($result['viewCount'] == '1')
                {
                    $viewCount          = $result['viewCount'] . " View";
                }
                else
                {
                    $viewCount          = ($result['viewCount'] == "0")?"No Views":$result['viewCount'] . " Views";
                }

                if($result['answerCount'] == '1')
                {
                    $answerCount        = $result['answerCount'] . " Answer";
                }
                else
                {
                    $answerCount        = ($result['answerCount'] == "0")?"No Answers":$result['answerCount'] . " Answers";
                }
                
                if($result['likes'] == '1')
                {
                    $likes              = $result['commentCount'] . " Comment";
                }
                else
                {
                    $likes              = empty($result['commentCount'])?"No Comments":$result['commentCount'] . " Comments";
                }
                
                $userphoto              = empty($arrayOfUsers[$result['userId']]['userImage'])?"/public/mobile/images/photoNotAvailable_mobile.gif":$arrayOfUsers[$result['userId']]['userImage'];
                
                $descHTML               = empty($descriptionD)?"":"<p>".displaySubStringWithStrip($descriptionD, 200)."</p>";
                
            ?>
        	<li onclick="window.location='<?php echo $url; ?>';" style="cursor: pointer;">
            	<div class="figure3">
                    <img class="lazy" src="/public/mobile/images/photoNotAvailable_mobile.gif" data-original="<?php echo $userphoto; ?>"  width="46" />
                </div>    
                <div class="details3">
                	<h2><a href="<?php echo $url; ?>"><?php echo displaySubStringWithStrip($result['msgTxt'],30); ?></a></h2>
                    <?php echo $descHTML; ?>
                    <p>
                    <strong><a><?php echo displaySubStringWithStrip($displayname,20); ?></a>
                    <?php
                        if($listingTitle)
                        {
                            echo " asked  about ";
                        }
                        else
                        {
                            echo " asked";
                        }
                    ?>
                    <a href="<?php echo $instituteurl;?>"><?php echo $listingTitle; ?></a> <span><?php echo $creationDate;?></span></strong>
                    </p>
                    <p class="liked-post"><?php echo "(". /* $likes . "," . */ $viewCount . "," .$answerCount . ")"; ?></p>
                </div>
            </li>
            <?php
            }
            ?>
        </ul>
        <?php
        $mobile_website_pagination_count = $this->config->item('mobile_website_pagination_count');
	//$totalCount = 30;
       $current_page   =  ceil(($totalCount - $rows)/$mobile_website_pagination_count);
        $lastresult        =   $totalCount%$mobile_website_pagination_count;
        //echo $lastresult . "###" . $current_page . "###" . $totalCount ;
        if($current_page > 1) {
                      $newrows   =  ($rows  + $mobile_website_pagination_count);
                      $lastPage   =  $mobile_website_pagination_count;  
        }
        if($current_page  <= 1) { 
		if($lastresult == 0) {
		 $lastPage   =  $mobile_website_pagination_count;
		} else {
                $lastPage =   $lastresult;
		}
                $newrows =  $totalCount;
        }

        if($current_page != 0 AND $totalCount != 0 )
        {
        ?>
        <div id="see-more">
        <?php
        $pageurl = SHIKSHA_ASK_HOME . "/messageBoard/MsgBoard/discussionHome/" . $categoryId . "/" . $pflag_UnansweredTopics . "/" . $countryId . "/" . $myqnaTab . '/' .  $actionDone .  "/" . $start . "/" . $newrows;
        echo '<a href="'.$pageurl.'">See '.$lastPage. ' more</a></li>';
        ?>  
        </div>
        <?php
        }
        ?>
    </div>
<?php 
global $ci_mobile_capbilities; if(!isset($ci_mobile_capbilities)) { $ci_mobile_capbilities = $_COOKIE['ci_mobile_capbilities']; $wurfl_data = json_decode($ci_mobile_capbilities,true);} else { $wurfl_data = $ci_mobile_capbilities; } if($wurfl_data['ajax_support_javascript'] == "true") { $this->load->view('ANA/smartphones/ask_question_widget');  } ?>
    <script>
    $("img.lazy").show().lazyload({ 
        effect : "fadeIn",
        failure_limit : 5
    });
    </script>
<?php $this->load->view('/mcommon/footer'); ?>
