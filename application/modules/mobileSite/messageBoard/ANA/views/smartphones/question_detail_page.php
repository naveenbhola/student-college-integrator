<?php $this->load->view('/mcommon/header'); ?>
<div class="search-btn-back">
	<a href="/search/showSearchHome" class="gray-button2">Search Institutes & Courses</a>
</div>
<div id="head-sep"></div>
<?php
$questionDesc   = isset($questionDescription)?$questionDescription:"";
$questionText   = $main_message['msgTxt'];
$questionId     = $topicId;
$userAvtarImg   = isset($main_message['userImage'])? $main_message['userImage']:"/public/mobile/images/photoNotAvailable_mobile.gif";
$userName       = displaySubStringWithStrip($main_message['displayname'],20);
$userId         = $main_message['userId'];
$ansCount       = isset($totalComments)?$totalComments:"0";
$mobile_website_pagination_count = $this->config->item('mobile_website_pagination_count');
global $ci_mobile_capbilities; if(!isset($ci_mobile_capbilities)) { $ci_mobile_capbilities = $_COOKIE['ci_mobile_capbilities']; $wurfl_data = json_decode($ci_mobile_capbilities,true);} else { $wurfl_data = $ci_mobile_capbilities; }
/*
echo $ansCount ;
echo "<br>";
echo $paginationURL ;
echo "<br>";
echo $filterURL;
echo "<br>";
echo $canonicalURL;
echo "<br>";
echo $start;
echo "<br>";
echo $count;
echo "<br>";
*/
if ($ansCount > 0 )
{
    if( ((int)$ansCount - ((int)$mobile_website_pagination_count  + (int)$start))  <= (int)$mobile_website_pagination_count)
    {
        $ans_pagination = ((int)$ansCount - ((int)$mobile_website_pagination_count  + (int)$start));
    }
    else
    {
        $ans_pagination = (int)($mobile_website_pagination_count);
    }
     //echo $ans_pagination;
     //echo "<br>";
    //if($start  == 0)
    //{
        $start = $start + 10;
        $paginationPath = str_replace('@start@',$start,$paginationURL);
        $paginationPath = str_replace('@count@',$count,$paginationPath);
        $anspaginationurl = SHIKSHA_ASK_HOME . $paginationPath;
       // echo $url;
    //}
}
?> 
<?php
if($canonicalURL) {
?>
<link rel="canonical" href="<?php echo $canonicalURL; ?>" />
<?php
}
?>
<div id="listing-wrap">
	<div class="inst-box">
    	<div class="qna-header">
        	<h5>QUESTION</h5>
            <h6><?php echo MakeLinksFrmString(nl2br($questionText));?></h6>
            <p><?php echo MakeLinksFrmString(nl2br($questionDesc)); ?></p>
            <div class="ana-user-details">
                <div class="user-pic"><img width="46" src="<?php echo $userAvtarImg; ?>" /></div>
                <p style=""><a >By <?php echo $userName;?></a></p>
            </div>
            
        </div>
        <div class="clearFix"></div>
        <span class="cloud-arr">&nbsp;</span>
	</div>
	<?php 
        if (count($topic_messages) > 0)
        {
    ?>
    <div id="answer_box" style="display:block;" class="round-box">
    	<h5 class="orange-title">Answers <span>(<?php echo $totalComments; ?>)</span></h5>
        <div class="clearFix spacer5"></div>
        <?php 
            $i = 0;
            foreach($topic_messages as $key=>$value)
            {

                $ans_user_image =  isset($value[0]['userImage'])? $value[0]['userImage']:"/public/mobile/images/photoNotAvailable_mobile.gif";
        ?>
        <div class="ques-details" id="answer_section_container_<?php echo $i; ?>">
            <div class="ana-user-details2">
                    <div class="user-pic"><img width="46" src="<?php echo $ans_user_image; ?>" /></div>
                    <p>
                        <a ><?php echo displaySubStringWithStrip($value[0]['displayname'],20); ?></a>
                        <?php
                            if(isset($expertArray[$value[0]['userId']]['expertStatus']) && ($expertArray[$value[0]['userId']]['expertStatus'] != '0'))
                            {
                        ?>
                        <span class="expert-box">Shiksha Expert</span><?php echo display_shiksha_expert_star($value[0]['userId'],$levelVCard); ?>
                        <?php 
                            }
                        ?>
                        <span class="gray-font"><?php echo $value[0]['creationDate']; ?></span>
                        <?php if ($value[0]['bestAnsFlag'] == "1") { ?> <span style="margin-top:5px; display:block"> Best answer</span> <?php } ?>
                    </p>
                </div>
            <p> <?php echo MakeLinksFrmString(nl2br($value[0]['msgTxt'])); ?></p>
            <div class="thumbs-cont">
                <span class="thumbs-up"><?php echo $value[0]['digUp'];?></span>
                <span class="thumbs-dwn"><?php echo $value[0]['digDown']; ?></span>
            </div>
            <div class="clearFix"></div>
        </div>
        <?php
            $i++;
            }
         ?>
        <div id="ans_ajax_show" style="background-Color:yellow;"></div>

<?php if ($ans_pagination > 0 ) { ?>
        <div id="see-more">
			<a href="<?php echo $anspaginationurl; ?>"><?php echo "See " . $ans_pagination . " more"; ?></a>
        </div>
        <?php } ?>
        <div class="clearFix spacer8"></div>
    </div>
    <?php 
        }
        else
        {
            ?>
            <div id="answer_box" style="display:none;" class="round-box">
                <h5 class="orange-title">Answers <span>(1)</span></h5>
                <div class="clearFix spacer5"></div>
                <div id="ans_ajax_show" style="background-Color:yellow;"></div>
                <div class="clearFix spacer8"></div>
            </div> 
             <input type="hidden" name="logged_in_user_already_answered" id="logged_in_user_already_answered" value="false" >
            <?php
        }
        foreach($topic_messages as $key=>$value)
        {
            if ($value[0]['userId'] == $logged_in_user['userid'])
            {
                ?>
                <input type="hidden" name="logged_in_user_already_answered" id="logged_in_user_already_answered" value="true" >
                <?php
                break;
            }
            else
            {
                ?>
                <input type="hidden" name="logged_in_user_already_answered" id="logged_in_user_already_answered" value="false" >
                <?php
            }
        }
    ?>
    <!-- Answer Form shud come here -->
    <div id="post_answer_container" style="display:none;">
        <div class="round-box">
            <h4 class="ans-title">Your Answer</h4>
            <form name="post_answer_form" id="form_post_form" accept-charset="utf-8" method="post" autocomplete="off">
                 <?php $secret_key =  $this->config->item('secret_key'); ?>
                <input type="hidden" id="hashtxt" name="hashtxt" value="<?php echo md5($secret_key.time()).','.time(); ?>" />
                <input type="hidden" id="ans_char_count_limit" name="ans_char_count_limit" value="<?php echo $this->config->item('ans_char_count_limit');?>" />
                <input type="hidden" id="question_id" name="question_id" value="<?php echo $questionId;?>" />
                <?php if(!empty($logged_in_user['userid']))  { ?>
                <input type="hidden" id="logged_id_userid" name="logged_id_userid" value="<?php echo $logged_in_user['userid'];?>" />
                <input type="hidden" id="username" name="name" value="<?php echo $logged_in_user['displayname'];?>" />
                <input type="hidden" id="lusername" name="lname" value="<?php if($logged_in_user['lastname']) { echo $logged_in_user['lastname']; } else { echo ""; } ?>" />
                <input type="hidden" id="emailid" name="email" value="<?php $value = explode("|",$logged_in_user['cookiestr']); echo $value[0];?>" />
                <input type="hidden" id="mobileno" name="mobile" value="<?php echo $logged_in_user['mobile'];?>" />
                <?php
                         if (isset($logged_in_user_expert_detail[0]['msgArray'][0]) AND count($logged_in_user_expert_detail[0]['msgArray'][0]) > 0 )
                        {
                            $level = $logged_in_user_expert_detail[0]['msgArray'][0]['level'];
                            if($level == "Advisor")
                            {
                                $img = 'public/mobile/images/str_1s.gif' ;
                            }elseif ($level == "Senior Advisor") {
                              # code...
                              $img ='public/mobile/images/str_2s.gif';
                            }elseif ($level == "Lead Advisor") {
                              # code...
                              $img ='public/mobile/images/str_3s.gif';
                            }elseif ($level == "Principal Advisor") {
                              # code...
                              $img ='public/mobile/images/str_4s.gif';
                            }elseif ($level == "Chief Advisor") {
                              # code...
                              $img ='public/mobile/images/str_5s.gif';
                            }
                        ?>
                        <input type="hidden" id="logged_in_foto_url" name="logged_in_foto_url" value="<?php echo $logged_in_user_expert_detail[0]['msgArray'][0]['avtarimageurl'] ; ?>" />
                        <input type="hidden" id="logged_in_expert_star_url" name="logged_in_expert_star_url" value="<?php echo $img;?>" />
                        <input type="hidden" id="logged_in_expert_name" name="logged_in_expert_name" value="<?php echo $logged_in_user_expert_detail[0]['msgArray'][0]['displayname'];?>" />
                        <?php
                        }
                }
                  ?>
            <div class="form-cont">
            <ul>
                <li>
                    <textarea required=""  style="color:#9a9a9a;" maxlength="2500" id="ans_text" required type="text" name="answer_text" rows="3" cols="3" class="text-area"></textarea>  
                </li>
                <?php if (empty($logged_in_user['userid']) && count($logged_in_user) <= 0) { ?>
                <li>
                    <label>First name</label>
                    <div class="field-cont">
                    <input type="text" name="name" required="" id="username" minlength="1" maxlength="50" class="login-field">
                    </div>
                    <div id="username_error" style="color:red;font-size:13px;"></div>
                </li>
                <li>
                    <label>Last name</label>
                    <div class="field-cont">
                    <input type="text" name="lname" required="" id="lusername" minlength="1" maxlength="50" class="login-field">
                    </div>
                    <div id="lusername_error" style="color:red;font-size:13px;"></div>
                </li>
                <li>
                    <label>Email</label>
                    <div class="field-cont">
                    <input type="email" name="email" required="" pattern="^(?:[-+âˆ¼=!#$%&amp;'*/?\^`{|}\w]+)(?:\.[-+âˆ¼=!#$%&amp;'*/?\^`{|}\w]+)*@(?:[a-zA-Z0-9][-a-zA-Z0-9]*[a-zA-Z0-9]\.)+[a-zA-Z]{2,6}$" maxlength="125" id="emailid" class="login-field">
                    </div>
                    <div id="emailid_error" style="color:red;font-size:13px;"></div>
                </li>
                
                <li>
                    <label>Mobile</label>
                    <div class="field-cont">
                    <input type="tel" pattern="\d{10}" name="mobile" required="" maxlength="10" id="mobileno" class="login-field">
                    </div>
                    <div id="mobileno_error" style="color:red;font-size:13px;"></div>
                </li>
                <?php } ?>
                <li>
                    <div id="main_error" style="color:red;font-size:13px;"></div>
                </li>
                <li style="margin-top:8px">
                    <input type="submit" id="submit_ans_post_form" class="orange-button" value="Submit" /> &nbsp; <a href="javascript:void(0)" onclick="return hideAnsForm();">Cancel</a>
                </li>
             </ul>
            </div>
            </form>
            <div class="clearFix"></div>
        </div>
    </div>
    <?php if(($main_message['status']  != 'closed') &&  ( $wurfl_data['ajax_support_javascript'] == "true")) { ?>
     <div id="post_answer_box" class="round-box">
        <p class="knw-better-ans"><a class="orange-button" href="javascript:void(0)" onclick="return showAnsForm('<?php echo $userId;?>');"><?php  echo "Click here to post your answer"; ?></a></p>
    </div>
    <?php } ?>

    <?php  if(($main_message['status']  == 'closed')) { ?>
     <div id="post_answer_box" class="round-box">
        <p class="knw-better-ans">This question is closed for Answering.</p>
    </div>
    <?php } ?>
    <?php  if($wurfl_data['ajax_support_javascript'] == "true" AND  $ansCount  > 0 ) {  ?>
    <div id="post_question_box" class="round-box">
        <form name="post_answer_form" id="form_post_form" accept-charset="utf-8" method="post" action="ANA/mobile_messageboard/render_ask_question_page">
        	<h3 class="widget-titles">Need expert guidance on career? </h3>
            <textarea rows="3" maxlength="140" required=""  onkeypress="if(event.keyCode == 13){ return false;}" onkeyup="if(event.keyCode == 13){ return false;}" name="question_text_for_post" id="question_text_for_post" cols="3" class="text-area" style="color:#9a9a9a;" placeholder="Need expert guidance on education or career? Ask our experts."></textarea>
            <div class="spacer8 clearFix"></div>
            <input type="submit" <?php  if ($reputationPoints <= 0) {  echo "onclick='return showalert();'"; } ?> value="Ask your question" class="orange-button" />
        </form>
    </div>
    <?php } ?>
    <?php  
    if (((isset($googleRes['link'])) && (count($googleRes['link']) > 1)) || (count($linkQuestionViewCount->link)>0) )
    {
    ?>
    <div id="related_question_box" class="round-box">
    	<h3 class="widget-titles">Related Questions</h3>
        <div>
	
        	<ul class="bullet-item">
                <?php 
                if(count($linkQuestionViewCount->link)>0)
                {
                     $j = 0;
                     foreach($linkQuestionViewCount->link as $linkedQuestion) { 
                        if(!empty( $linkQuestionViewCount->title[$j]))
                        {
                        ?>
                        <li><a href="<?php echo $linkedQuestion; ?>" title="<?php echo  $linkQuestionViewCount->title[$j]; ?>" ><?php echo  $linkQuestionViewCount->title[$j]; ?></a></li>
                        <?php
                        } $j++;
                     }
                }
               
                ?>
                <?php  if(!(count($linkQuestionViewCount->link)>0))
                    $i = 0;
                     foreach($googleRes['link'] as $link) { 
                        if (!empty($googleRes['title'][$i]) && !empty($link) && (filter_item_from_array($link,$site_current_url) == false))  {
                   ?>
            	           <li><a href="<?php echo $link; ?>" title="<?php echo $googleRes['title'][$i] ; ?>" ><?php echo $googleRes['title'][$i]; ?></a></li>
                <?php
                     }
                 $i++;
                 } 
                 ?>
            </ul>
        </div>
    </div>
    <?php
    }
    ?>
    <?php 
    if($ansCount  <= 0 AND $wurfl_data['ajax_support_javascript'] == "true") {
    ?>
     <div id="post_question_box" class="round-box">
        <form name="post_answer_form" id="form_post_form" accept-charset="utf-8" method="post" action="ANA/mobile_messageboard/render_ask_question_page">
            <h3 class="widget-titles">Need expert guidance on career? </h3>
            <input type="submit" <?php  if ($reputationPoints <= 0) {  echo "onclick='return showalert();'"; } ?> value="Ask your question" class="orange-button" />
        </form>
    </div>
    <?php 
    }
    ?>
     <div id="post_answer_box1" class="round-box" onclick="window.location='<?php echo $quickLinkURL; ?>';" style="cursor: pointer;">
        <a  href="<?php echo $quickLinkURL; ?>" >View Institutes offering  <?php echo $catCountArray[0]['category']; ?></a>
    </div>

    <p style="padding:5px 10px;font-size:80%"><strong>Disclaimer:</strong> <a href="<?php echo SHIKSHA_HOME; ?>/mcommon/MobileSiteStatic/privacy" style="color:gray;font-style:italic">Views expressed by the users above are their own, Info Edge (India) Limited does not endorse the same.</a></p>

<img id = 'beacon_img' width=1 height=1 >
<script>
   var reputationPoints = "<?php echo $reputationPoints; ?>";
   var img = document.getElementById('beacon_img');
   var randNumForBeacon = Math.floor(Math.random()*Math.pow(10,16));
   img.src = '<?php echo BEACON_URL; ?>/'+randNumForBeacon+'/0003003/<?php echo $topicId; ?>+<?php echo $userId; ?>';
   currentPageName = 'QUES DETAIL PAGE';
</script>
     <script>
        $(document).ready(function()
        {
            // textarea char count
            $("#ans_text").charCount({
              allowed: 2500,    
              warning: 50,
              counterText: 'Characters left: ',
              css: 'char-limit counter',
              counterElement: 'p',
              cssWarning: 'warning',
              cssExceeded: 'exceeded'
            });

            $("#question_text_for_post").charCount({
              allowed: <?php echo $this->config->item('ans_char_count_limit');?>,    
              warning: 50,
              counterText: 'Characters left: ',
              css: 'counter-char-limit counter',
              counterElement: 'p',
              cssWarning: 'warning',
              cssExceeded: 'exceeded'
            });
            // images lazy loading
            $("img.lazy").lazyload();

            $('#question_text_for_post').bind('paste', function(e){ 
            var elem = $(this);
            setTimeout(function() {
                    var text = elem.val(); 
                    while (text.indexOf("\n") > -1)
                    text = text.replace("\n"," ");
                    elem.val(text);
                }, 50);
            });
            
        });
    </script>
<?php $this->load->view('/mcommon/footer'); ?>
