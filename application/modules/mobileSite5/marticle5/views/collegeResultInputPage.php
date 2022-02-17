
    <div class="rslt-bx">
        <div class="rslt-info">
            <img class="vit-img" src="<?=$logoUrl;?>" alt="<?=$altTxt;?>"  />
            <p style="margin-bottom:5px">
                Shiksha is hosting the official <?=$examType;?> 2017 Result for your convenience. The scores you can see are directly sourced from the <?=$examName;?> website.
            </p>
            <?php if($examName != 'SRM'){?>
            <p>
                <a href="<?=$examUrl;?>" ga-attr="<?=$gaAttrExamLink;?>">Click here</a> to get redirected to the actual result page.
            </p>
            <?php } ?>
            <?php if($showCounter){?>
            <div class="clck-algn">
                <div id="clockdiv">
                        <div>
                            <span class="days"></span>
                            <div class="smalltext">Days</div>
                        </div>
                        <div>
                            <span class="hours"></span>
                            <div class="smalltext">Hours</div>
                        </div>
                        <div>
                            <span class="minutes"></span>
                            <div class="smalltext">Minutes</div>
                        </div>
                       <!--  <div>
                            <span class="seconds"></span>
                            <div class="smalltext">Seconds</div>
                        </div> -->
                    </div>
            </div>
            <?php } ?>

            <?php if(!$showCounter){?>
                <div>
             <?php }else{?>
                <div id="collegeExamInputDiv" style="display:none;">
            <?php } ?>
            <form  id="collegeExamResultForm" action=""  accept-charset="utf-8" method="post"  novalidate="novalidate" name="collegeResultForm" >
            <div class="ex-fm" >
                <ul>
                    <li>
                        <label>Application Number</label>
                        <input type="text" class="app-txt" name="application_no_input" id= "application_no_input" class="prf-inpt" value="" minlength="10" maxlength="10" caption="application number" required="true" validate="validateInteger"/>
                        <div style="display:none;"><p class="err0r-msg" id="application_no_input_error"></p></div>
                    </li>
                    <li>
                        <label>Date of Birth</label>
                        <div class="reltv">
                            <input type="text"  id="date_of_birth_input" name="date_of_birth_input" placeholder="DD/MM/YYYY" caption="date of birth" required="true" validate = "validateStr" minlength = "0" maxlength="30" class="app-txt">
                            <a href="javascript:void(0);" onclick="$('#date_of_birth_input').focus();" >
                                <i class="icons1 ic_cal"></i>
                            </a>
                        </div>
                            <div style="display:none;" id="error_div_result"><p class="err0r-msg" id="date_of_birth_input_error"></p></div>

                    </li>
                    <li>
                        <a href="javascript:void(0)" class="app-sbmt" id="showResultButton" ga-attr="SHOW_RESULT">Show Result</a>
                    </li>
                </ul>
                <input type="hidden" value='<?=$trackingKeyId;?>' id="trackingPageKeyField">
                <input type="hidden" value='<?=$examType;?>' id="exam_name_input">
                <input type="hidden" value='<?=$resultUrl;?>' id="resultUrl">
            </div>
            </form>
        </div>
        </div>
    </div>

<script type="text/javascript">
        var GA_currentPage = "VIT INPUT PAGE";
        var ga_user_level = "<?=$ga_user_level;?>";
        var ga_commonCTA_name = '_VIT_INPUT_PAGE_MOB';
    </script>
