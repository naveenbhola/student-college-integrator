<?php $this->load->view('/mcommon/header'); ?>
<div class="search-btn-back">
	<a href="/search/showSearchHome" class="gray-button2">Search Institutes & Courses</a>
</div>
<div id="head-sep"></div>
<?php
$appId = 1;
$categoryClient = new Category_list_client();
$categoryListIndia = $categoryClient->getCategoryTree($appId,'','national');
$categoryListAbroad = $categoryClient->getCategoryTree($appId,'','studyabroad');
$topicCountryId = '2';
$categoryList = $categoryListIndia;
?> 

<div id="head-title">
	<h4 style="padding:5px 0">Ask a question</h4>
    <span>&nbsp;</span>
</div>

<div id="content-wrap">
	<div style="padding:8px">
    <h4 class="ques-title">Your Question</h4>
	<form name="post_answer_form" id="form_post_question" accept-charset="utf-8" method="post" autocomplete="off" />
             <?php $secret_key =  $this->config->item('secret_key'); ?>
              <input type="hidden" id="hashtxt" name="hashtxt" value="<?php echo md5($secret_key.time()).','.time(); ?>" />
    <div class="form-cont">
    	<ul>
        	<li>
            	<textarea onkeypress="if(event.keyCode == 13){ return false;}" onkeyup="if(event.keyCode == 13){ return false;}" required="" wrap="hard" spellcheck="true" maxlength="140" placeholder="Need expert guidance on education or career? Ask our experts." rows="3" name="question_text_for_post" id="question_text_for_post" cols="3" class="text-area" style="color:#757575"><?php if(isset($question_text_for_post)) { echo $question_text_for_post; } ?></textarea>
            </li>
            <?php if (empty($logged_in_user['userid']) && count($logged_in_user) <= 0) { ?>
            <li>
            	<label>First name</label>
                <div class="field-cont">
                <input type="text" required="" name="qname" id="qusername" minlength="1" maxlength="50" class="login-field">
                </div>
            </li>

             <li>
                <label>Last name</label>
                <div class="field-cont">
                <input type="text" required="" name="qlname" id="qlusername" minlength="1" maxlength="50" class="login-field">
                </div>
            </li>

            <li>
            	<label>Email</label>
                <div class="field-cont">
                 <input type="email" pattern="^(?:[-+âˆ¼=!#$%&amp;'*/?\^`{|}\w]+)(?:\.[-+âˆ¼=!#$%&amp;'*/?\^`{|}\w]+)*@(?:[a-zA-Z0-9][-a-zA-Z0-9]*[a-zA-Z0-9]\.)+[a-zA-Z]{2,6}$" required="" name="qemail" maxlength="125" id="qemailid" class="login-field">
                </div>
            </li>
            
            <li>
            	<label>Mobile</label>
                <div class="field-cont">
                <input type="tel" pattern="\d{10}" required="" name="qmobile" maxlength="10" id="qmobileno" class="login-field">
                </div>
            </li>
            <?php } ?>
            <li>
            	<label>Question is related to</label>
                <div class="field-cont">
                    <?php
                    /* code to show only regions */ 
                       $countriesArray = getTempUserData("countriesArray");
                        if(strlen($countriesArray ) > 1)
                        {
                            $countriesArray  =  explode(",",  $countriesArray);
                            if($countriesArray[count($countriesArray)-1] == "")
                            {
                                unset($countriesArray[count($countriesArray)-1]);
                            }
                        }
                        else
                        {
                            $countriesArray  =  array("2");
                        }
                    ?>
                <select id="countrydd" required="" name="countrydd" class="select-field" onchange="showHideCountry();">
                    <?php 
                    foreach($countryList as $key=>$value)
                    {
                        $selected =  "";
                        if (in_array($key, $countriesArray ))
                        {
                         if ($key != '1')
                        {
                            if($key == '2')
                            {
                                $selected =  "selected";
                            }    
                    ?>
                    <option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo "Study in " . $value;?></option>
                    <?php
                        }
                        } 
                    }
                    ?>
                </select>
                </div>
            </li>
            <li>
            	<label>Select the appropriate category to post your question</label>
                <div class="field-cont">
                <select class="select-field" required="" id = "catselect" name="catselect" onChange = "changeSelection(this.value);" >
                    <option value="-1" > Select  category </option>
                    <?php
                    foreach($categoryList as $value) {

                        if($value['parentId'] == 1){
                    ?>
                    <option  value = "<?php echo $value['categoryID']; ?>"><?php echo $value['categoryName']; ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
                </div>
            </li>
            
            <li style="margin-top:8px">
            	<div class="field-cont">
                <select id = "subcatselect" required="" name="subcatselect" class="select-field" >
                    <option  value="-1" >Select sub category</option>
                </select>
                </div>
            </li>
            
            <li style="margin-top:8px">
            	<input type="submit" id="submit_qns_post_form" <?php  if ($reputationPoints <= 0) {  echo "onclick='return showalert();'"; } ?> class="orange-button" value="Post Question" /> &nbsp; <a href="<? echo $site_current_refferal; ?>">Cancel</a>
            </li>
         </ul>
         </div>
         <div id="main_error"></div>
     </form>
    </div>

<script>

categoryList = eval(<?php echo json_encode($categoryList); ?>);
categoryListIndia = eval(<?php echo json_encode($categoryListIndia); ?>);
categoryListAbroad = eval(<?php echo json_encode($categoryListAbroad); ?>);

function changeSelection(Id)
{
    document.getElementById('subcatselect').innerHTML = '';
    var optionElement = '';
    var optionElement = document.createElement('option');
    optionElement.value = '-1';
    optionElement.setAttribute('selected', 'selected');
    optionElement.innerHTML = 'Select sub category' ;
    document.getElementById('subcatselect').appendChild(optionElement);
    for(var categoryCount = 0; categoryCount < categoryList.length; categoryCount++) {
        if(Id == categoryList[categoryCount].parentId)
        {
            var optionElement = document.createElement('option');
            optionElement.value = categoryList[categoryCount].categoryID;
            optionElement.innerHTML = categoryList[categoryCount].categoryName ;
            document.getElementById('subcatselect').appendChild(optionElement);
        }
    }
}

function showHideCountry()
{
    var ddvalue = $('#countrydd').val();
    if (ddvalue != 2)
    {
        categoryList = categoryListAbroad;
    }
    else
    {
        categoryList = categoryListIndia;
    }
    var Id = $('#catselect').val();
    changeSelection(Id);
}

changeSelection('3');

$(document).ready(function()
{
    // textarea char count
    $("#question_text_for_post").charCount({
      allowed: <?php echo $this->config->item('ans_char_count_limit');?>,    
      warning: 50,
      counterText: 'Characters left: ',
      css: 'char-limit counter',
      counterElement: 'p',
      cssWarning: 'warning',
      cssExceeded: 'exceeded'
    });

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
