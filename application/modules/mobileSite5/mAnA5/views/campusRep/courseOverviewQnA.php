  <div class="qna-dtls">
     <input id="institute_id" type="hidden" value="<?php echo $instituteId;?>">
      <input id="course_id" type="hidden" value="<?php echo $courseId;?>">
      <input id="js_enabled" type="hidden" value="<?php echo $js_enabled;?>">
      <input id="study_india" type="hidden" value="<?php echo $studyIndia;?>">
      
    <!--qna head--> 
       <div class="qna-head">
        <?php $unansweredText = ($questionType == 'All')? 'Answered':'Unanswered';
        if($questionType == 'All')
        {
          $GA_TapOnLoadMore = 'LOADMORE_COURSELISTING_WEBAnA';
          $GA_TapOnViewList = 'UNANSWERED_SORT_COURSELISTING_WEBAnA';
        }
        else
        {
          $GA_TapOnLoadMore = 'LOADMORE_COURSELISTING_WEBAnA';
          $GA_TapOnViewList = 'ANSWERED_SORT_COURSELISTING_WEBAnA';
        }
        ?>
         <p><?php echo $total;?> <?php echo ($total > 1)?$unansweredText.' Questions':$unansweredText.' Question'?></p>
              <span class="right-span">View : 
                       <div class="qa-dropdown">
                          <a href="javascript:void(0);" onclick="$('#sortOptionTab').toggle();" id="sortingDiv">
                             <div class="arrow"><i class="caret"></i></div>
                             <span class="display-area" id="selectedSortOrder"><?php echo $questionType == 'All' ? 'Answered Questions':'Unanswered Questions';?></span>
                         </a>
                          <div class="drop-layer" id="sortOptionTab" style="display:none;">
                            <ul>
                              <?php if($questionType == 'All') {?>
                                  <li><a href="javascript:void(0);" id="sort_AnsweredQnA" onclick="$('#sortOptionTab').hide();">Answered Questions</a></li>
                                <li><a href="javascript:void(0);" id="sort_latest" onclick="gaTrackEventCustom('COURSELISTING_WEBAnA','<?php echo $GA_TapOnViewList;?>','<?php echo $GA_userLevel;?>');filterQuestions('Unanswered','<?php echo $_GET['link_id']?>');">Unanswered Questions</a></li>
                              <?php }else{?>
                                  <li><a href="javascript:void(0);" id="sort_AnsweredQnA" onclick="gaTrackEventCustom('COURSELISTING_WEBAnA','<?php echo $GA_TapOnViewList;?>','<?php echo $GA_userLevel;?>');filterQuestions('All','<?php echo $_GET['link_id']?>')">Answered Questions</a></li>
                                <li><a href="javascript:void(0);" id="sort_latest" onclick="$('#sortOptionTab').hide();">Unanswered Questions</a></li>
                              <?php } ?>
                            </ul>
                          </div>
                      </div>

                 </span>
       </div>
       <!--qna head close-->
       <div id="qna_div">
          <?php $this->load->view('mAnA5/campusRep/campusConnectInCourseDetailPage')?>
      </div>
   <!--loadmore btns--> 
       
          <script>var totalQuestionCount = <?=$total?>;</script>
      <?php if($total > 3){ ?>
        <div class="qna-cmp-dtls">
          <a href="javascript:void(0)" onclick="gaTrackEventCustom('COURSELISTING_WEBAnA','<?php echo $GA_TapOnLoadMore;?>','<?php echo $GA_userLevel;?>');loadMoreQnAOverview('','','<?php echo $atrackingPageKeyId;?>','<?php echo $tupaTrackingPageKeyId;?>','<?php echo $tdownTrackingPageKeyId;?>','<?php echo $fqTrackingPageKeyId;?>')" class="u-btns l-more" id="load_more">Load More Questions...</a>     
            <span id="load_more_span" style="display:none;margin-bottom: 10px;padding: 0.5em;text-align: center;">No more questions to show</span>
        </div>   
        <?php } ?> 
          <!-- <a href="#" class="u-btns p-btn">Ask your Questions</a> -->
       
  <!--card end-->
 </div>
