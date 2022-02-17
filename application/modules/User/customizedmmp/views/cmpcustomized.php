<title>Customize Form</title>
<?php
        $headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style', 'marketing','custom_cmp'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog','cmp'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  'Customized MMP',
        'taburl' => site_url('customizemmp/mmp/showCustomizedMMP/'),
        'metaKeywords'  =>''
        );

        $this->load->view('enterprise/headerCMS', $headerComponents);
        $this->load->view('enterprise/cmsTabs');
?>
<?php 
$abroadDesiredCourse = array();
foreach($fields as $field) {
	if($field['id'] == 'abroadDesiredCourse') {
		foreach($field['values'] as $des_key=>$des_val) {
			$abroadDesiredCourse[$des_val] = $des_key;
		}
	}
}
?>
<script type="text/javascript">
document.getElementById('navigationUL').children[0].setAttribute('id','selected');
var fields = new Array();

var numberOfCoursesOnForm = <?php echo $numberOfCoursesOnForm; ?>;

var labels = new Array(
<?php echo $fieldshtml['label']; ?>
);

var defaultValue = new Array(
<?php echo $fieldshtml['preselected']; ?>
);

var typeArr = new Array(
<?php echo $fieldshtml['type']; ?>
);

var visibleArr = new Array(
<?php echo $fieldshtml['visible']; ?>
);

var valuesArr = new Array(
<?php echo $fieldshtml['values']; ?>
);

var mandatoryArr = new Array(
<?php echo $fieldshtml['mandatory']; ?>
);

var isCustomArr = new Array(
<?php echo $fieldshtml['isCustom']; ?>
);

var ids = new Array(
<?php echo $fieldshtml['id']; ?>
);

var ruleSet = Array(<?php echo $rules;?>);
var toDoSet = Array(<?php echo $actions;?>);

var localP = '<?php echo $fieldshtml['prefLocal']; ?>';

var nationalP = '<?php echo $fieldshtml['prefNational']; ?>';

var numFields = <?php echo  $fieldshtml['count'] ?>;

var reach = '<?php  echo $reach; ?>';
var abroadDesiredCourse = '<?php echo json_encode($abroadDesiredCourse);?>';
</script>
      <form method="post" name="myForm" id="myForm" action="/customizedmmp/mmp/SubmitCmp">
      <input type="hidden" id="formid" name="formid" value="<?php echo $formid;?>" /> 
      <input type="hidden" id="gid" name="gid" value="<?php echo $gid;?>" />
      <input type="hidden" id="cid" name="cid" value="<?php echo $cid;?>" />
      <input type="hidden" id="pagetype" name="pagetype" value=<?php echo $pagetype ;?> />
      <input type="hidden" id="formId" name="formId" value="<?php echo $formid;?>" />

      <input type="hidden" id="ruleSet" name="ruleSet"/>
      <input type="hidden" id="toDoSet" name="toDoSet"/>
      <input type="hidden" id="ids" name="ids"/>	
      <input type="hidden" id="labels" name="labels"/>
      <input type="hidden" id="defaultValue" name="defaultValue"/>
      <input type="hidden" id="typeArr" name="typeArr"/>
      <input type="hidden" id="visibleArr" name="visibleArr"/>
      <input type="hidden" id="valuesArr" name="valuesArr"/>
      <input type="hidden" id="mandatoryArr" name="mandatoryArr"/>
      <input type="hidden" id="isCustomArr" name="isCustomArr"/>
      </form>
      <div id="main-wrapper">
      <div id="content-wrapper">
	      <div class="wrapperFxd">
		      <div id="cms-wrapper">
              <h3 style="font-size:18px; border-bottom:1px solid #d9d9d9; padding-bottom:3px; margin-bottom:10px; font-weight:normal; float:left; width:100%">Create New Customizations:</h3>
              
              <ul>
              <li>
		      <select id="maindd" onchange="toggleGroups(this)" class="option-dropDown" style="width:auto" >
		      
		      <?php 
			echo '<option value="0" selected>Please select course or group to edit</option>';
			if($pagetype == "abroadpage"){
				echo "<option value='abroadpage'  selected='selected' >Study Abroad</option>";

			}
			else{
                if(count($courses)>0){
		      echo '<optgroup label="Courses"> ';
		      foreach($courses as $k=>$v){
			      if ($cid == $v['courseid']){ 
				      echo "<option value='".$v['courseid']."' selected='selected' >".$v['coursename']."</option>";
			      }
			      else{
				      echo "<option value='".$v['courseid']."' >".$v['coursename']."</option>";
			      }
		      }
		      echo "</optgroup>";
              }
		      echo '<optgroup label="Groups">';
		      foreach($groups as $k=>$v){
			      if ($gid == $k){
				      echo "<option value='".$k."_g' selected='selected'>".$v."</option>";
			      }
			      else{
				      echo "<option value='".$k."_g'>".$v."</option>";
			      }
		      }
		      echo "</optgroup>";
			}
		      ?>
		  </select>
          </li>
          </ul>
          <br />
		<?php 
        if(count($editData)>0){
            echo "<ul> <li><strong>EDIT EXISTING CUSTOMIZATIONS: </strong></li> <li>";
        
		foreach($editData as $k=>$v){
                        if ($v['type'] == 'abroadpage')
                                echo 'EDIT : <a href="/customizedmmp/mmp/EditCustom/'.$formid.'/'.$v['type'].'">STUDY ABROAD</a>&nbsp;&nbsp;&nbsp;&nbsp;';

			elseif ($v['type'] == 'group')
				echo 'EDIT GROUP: <a href="/customizedmmp/mmp/EditCustom/'.$formid.'/'.$v['foreignid'].'/'.$v['type'].'">'.$v['name'].'</a>&nbsp;&nbsp;&nbsp;&nbsp;';
			else
				echo 'EDIT COURSE: <a href="/customizedmmp/mmp/EditCustom/'.$formid.'/'.$v['foreignid'].'/'.$v['type'].'">'.$v['name'].'</a>&nbsp;&nbsp;&nbsp;&nbsp;';
		}
            echo "</li></ul>";
        }
		?>		     
<div id="entirepage">
 
		  <strong id="coursesInGroup" class="opted-list" style="display:none">Courses in this group ------- <?php echo $coursesNames; ?></strong>
                    
                    <ul id="customtabs" class="custom-tabs">
                    	<li id="tab1" onclick="changeTab('tab1')" class="active">Add Custom Fields</li>
                        <li id="tab2" onclick="changeTab('tab2')">Specify Field</li>
                        <li id="tab3" onclick="changeTab('tab3')">Add Custom Rules</li>
                    </ul>

                    
                    <!--START:Custom Field Tab Section-->
			<div style="float:left;width:550px;">
                    <div id="custom-fields-section">
                        <div id="tab1page" class="pL-15">
				<h2 class="custom-title">Additional Fields</h2>
			<div class="spacer20 clearFix"></div>
                            <div class="course-option-box">
                            	<h4 onclick="document.getElementById('checkboxadd').style.display='block';" class="custom-sub-title"> <span>+</span> Check boxes</h4>
                                <div id="checkboxadd" class="course-option-child-box" style="display:none">
                                 <label style="line-height: 24px; padding: 0px;">Id:<br>Heading:</label>
                                    <div class="details-cont">
                                        <ul>
                                            <li><input id="cbid" type="text" class="course-field" /></li>
                                            <li><input id="cbl" type="text" class="course-field" /></li>
                                            <div id="cbdiv">
                                            <li id="cbcontainer"><input id="cb0" type="text" value="" /></li>
                                            </div>
                                            <li><a href="javascript:void(0);"  onclick="addFields('cbcontainer')">+ add choices</a> </li>
                                            <li><input type="radio" value="1" name="cbr" /> Mandatory<br />
                                                <input type="radio" value="0" name="cbr" /> Non Mandatory
                                            </li>
                                            <li class="buuton-row">
                                            	<input type="button" value=" " class="save-button" title="Save" onClick="saveCustom('cb');"/>&nbsp;
                                                <a href="javascript:void(0);" onclick="document.getElementById('checkboxadd').style.display='none';">Remove</a>
											</li>
                                            
                                        </ul>
                                    </div>
                                    <div class="clearFix"></div>
                                </div>
                                <div class="clearFix"></div>
                            </div>
                            <div class="spacer20 clearFix"></div>
                            <div class="course-option-box">
                            	<h4 onclick="document.getElementById('dropdownadd').style.display='block';" class="custom-sub-title"><span>+</span> Drop Down</h4>
                                <div id="dropdownadd" class="course-option-child-box" style="display:none">
                                 <label style="line-height: 24px; padding: 0px;">Id:<br>Heading:</label>
                                    <div class="details-cont">
                                        <ul>
                                            <li><input id="ddid" type="text" class="course-field" /></li>
                                            <li><input id="ddl" type="text" class="course-field" /></li>
                                            <div id="dddiv">
                                            <li id="ddcontainer"><input id="dd0" type="text" value="" /></li>
                                            </div>
                                            <li><a href="javascript:void(0);" onclick="addFields('ddcontainer')">+ add choices</a> </li>
                                            <li><input type="radio" value="1" name="ddr" /> Mandatory<br />
                                                <input type="radio" value="0" name="ddr" /> Non Mandatory
                                            </li>
                                            <li class="buuton-row">
                                            	<input type="button" value=" " class="save-button" title="Save" onClick="saveCustom('dd');"/>&nbsp;
                                                <a href="javascript:void(0);"   onclick="document.getElementById('dropdownadd').style.display='none';">Remove</a>
                                            </li>
                                            
                                        </ul>
                                    </div>
                                    <div class="clearFix"></div>
				 
                                </div>
                                <div class="clearFix"></div>
                            </div>
                            
                            <div class="spacer20 clearFix"></div>
                            <div class="course-option-box">
                            	<h4 onclick="document.getElementById('multiplechoiceadd').style.display='block';" class="custom-sub-title"><span>+</span> Radio Button</h4>
                                <div id="multiplechoiceadd" class="course-option-child-box" style="display:none">
                                 <label style="line-height: 24px; padding: 0px;">Id:<br>Heading:</label>
                                    <div class="details-cont">
                                        <ul>
                                            <li><input id="mcid" type="text" class="course-field" /></li>
                                            <li><input id="mcl" type="text" class="course-field" /></li>
                                            <div id="mcdiv">
                                            <li id="mccontainer"><input id="mc0" type="text" value="" /></li>
                                            </div>
                                            <li><a href="javascript:void(0);"  onclick="addFields('mccontainer')">+ add choices</a> </li>
                                            <li><input type="radio" value="1" name="mcr" /> Mandatory<br />
                                                <input type="radio" value="0" name="mcr" /> Non Mandatory
                                            </li>
                                            <li class="buuton-row">
                                            	<input type="button" value=" " class="save-button" title="Save" onClick="saveCustom('mc');"/>&nbsp;
                                                <a href="javascript:void(0);"  onclick="document.getElementById('multiplechoiceadd').style.display='none';">Remove</a>
                                            </li>
                                            
                                        </ul>
                                    </div>
                                    <div class="clearFix"></div>
				 					</div>
                                <div class="clearFix"></div>
                            </div>
                            <div class="spacer20 clearFix"></div>
                            <div class="course-option-box">
                            	<h4 onclick="document.getElementById('paragraphadd').style.display='block';" class="custom-sub-title"><span>+</span> paragraph text</h4>
                                <div id="paragraphadd" class="course-option-child-box" style="display:none">
                                    
                                        <ul>
                                            <li>
                                                <label>Id:</label>
                                                <div class="details-cont">
                                                    <input id="paraid" type="text" class="course-field" />
                                                </div>
                                            </li>
                                            <li>
                                            	<label>Heading:</label>
                                            	<div class="details-cont">
                                            		<input id="pl" type="text" class="course-field" />
                                            	</div>
                                            </li>
                                            <li>
                                            	<label>Paragraph:</label>
                                            	<div class="details-cont">
                                            		<textarea id="pa" disabled style="width:90%; height:60px; overflow:auto; font:normal 12px Arial, Helvetica, sans-serif"></textarea>
                                            	</div>
                                            </li>
                                            <li>
<!--
                                            	<label>Range:</label>
                                            	<div class="details-cont">
                                            		<select id="plimit"><option value="">Character limit</option>
							<option value="500">500</option>
							<option value="1000">1000</option>
							<option value="1500">1500</option>
							</select>
							
                                            	</div>
-->
                                            </li>
                                            
                                            <li class="buuton-row">
                                            	<input type="button" value=" " class="save-button" title="Save" onClick="saveCustom('para');"/>&nbsp;
                                                <a href="javascript:void(0);"  onclick="document.getElementById('paragraphadd').style.display='none';">Remove</a>
                                            </li>
                                            
                                        </ul>

                                    <div class="clearFix"></div>
					 				</div>
                                <div class="clearFix"></div>
                            </div>
                        
                            <div class="clearFix spacer15"></div>
                            <input type="button" value="Skip" class="orange-button" style="padding:3px 20px" onclick="Skip('tab2')"/>&nbsp;
                            <input type="button" value="Next" class="orange-button" style="padding:3px 20px" onclick="changeTab('tab2')"/>&nbsp;
                            <a href="javascript:void()" onclick="Cancel();" style="font-size:16px">Cancel</a>
                            <div class="clearFix spacer15"></div><div class="clearFix spacer15"></div>
                        </div>
                    </div>
		</div>
		<div id="cmpfields" style="width:360px;float:right; padding:10px;border:1px solid #cacaca;display:none">
		</div>
                    <!--ENDS:Custom Field Tab Section-->
                        
                    <!--START:Specify Field Tab Section-->
                    <div id="specify-fields-section">
                    	<div id="specify-field-left">
                        </div>
    
                        <div id="specify-field-right" style="display:none">
                        </div>
                        
                        <div class="spacer15 clearFix"></div>
                        <input type="button" style="padding:3px 20px" class="orange-button" value="Back" onclick="changeTab('tab1');">&nbsp;
                        <input type="button" style="padding:3px 20px" class="orange-button" value="Next" onclick="changeTab('tab3');">&nbsp;
                        <a href="javascript:void()" style="font-size:16px" onclick="Cancel();">Cancel</a>
                        <div class="clearFix spacer15"></div><div class="clearFix spacer15"></div>
                    </div>
                    <!--ENDS:Specify Field Tab Section-->
                    
                    <!--START:Custom Rules Tab Section-->
                    <div id="custom-rules-section">
                        <div id="tab3page" class="pL-15">
                            <div class="">
                            	<h4 class="custom-sub-title" style="color:#000; cursor:text; font-size:18px">Show or hide fields based on these rules.</h4>
                                <div id="cmprules" class="course-option-child-box" style="width:640px;float:left">
                                    <div class="">
                                        <ul>
                                            <li>
                                            	If &nbsp;&nbsp;<select id="rule1" onChange="populateValues(this);" style="width:230px"><option></option></select>&nbsp;
                                                is&nbsp;
                                                <select id="rule1values" style="width:230px"><option></option></select>
                                                &nbsp;&nbsp;
                                                <span id="rule1id" style="display:block">
                                                <a href="javascript:void(0);"  onclick="addRule();">+ Add more</a>
                                                </span>
                                            </li>
                                            <li class="tac" id="andorid" style="display:none"><input type="radio" name="andor" value="and"/> and &nbsp;&nbsp;&nbsp;&nbsp; <input name="andor" type="radio" value="or"/> or</li>
                                            <li id="rule2id" style="display:none">
                                            	If &nbsp;&nbsp;<select id="rule2" onChange="populateValues(this);" style="width:230px"><option></option></select>&nbsp;
                                                is&nbsp;
                                                <select id="rule2values" style="width:230px"><option></option></select>
                                                &nbsp;&nbsp;
                                                <a href="javascript:void(0);"  onclick="removeRuleHTML();">+ Remove</a>
                                            </li>
                                            <li>
						<div id="inserthereid">
						<div id="todocontainer0">
                                            	<div style="padding:15px 0 10px 15px">
                                            	<select id="showhide0" onChange="changeToDo(0);"><option value="show">Show</option><option value="hide">Hide</option></select>&nbsp;
                                                <select id="todo0" onChange="changeToDo(0);" style="width:130px"><option></option></select>&nbsp;
						</div>
						<div id="defaultid0" style="display:none">
						Choose Default Value:
                                                <select id="setdefault0"></select>
                                                </div>
						</div>
						</div>
						<a href="javascript:void(0);"  onclick="addToDo();">+ Add more</a>
                        <span id="removeRuleId" style="display:none">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="javascript:void(0);"  onclick="removeToDo();">- Remove</a>
                        <span>
                                            </li>
                                            <li class="buuton-row"><input type="button" onClick="saveRule();" value=" " class="save-button" title="Save" /></li>
                                            
                                        </ul>
                                    </div>
                                    <div class="clearFix"></div>
                                </div>
		<div style="width: 250px; float: right; padding: 10px; border: 1px solid rgb(202, 202, 202); display:none; word-wrap: break-word;" id="cmprulesdiv"></div>
                            </div>
                            
                          	<div class="clearFix spacer15"></div>
                            <input type="button" value="Back" class="orange-button" style="padding:3px 20px" onclick="changeTab('tab2');"/>&nbsp;
                            <input type="button" value="Save" class="orange-button" style="padding:3px 20px" onClick="saveData();"/>&nbsp;
                            <a href="javascript:void()" style="font-size:16px" onclick="Cancel();">Cancel</a>
                            <div class="clearFix spacer15"></div><div class="clearFix spacer15"></div>
                        </div>
                    </div>
                    <!--ENDS:Custom Rules Tab Section-->
                       
                    <div class="clearFix"></div>
                </div>
	</div>

    		</div>
    	</div>
    </div>
<?php $this->load->view('common/footer');?>

<script type="text/javascript">

var initialcbHtml = document.getElementById('checkboxadd').innerHTML;
var initialddHtml = document.getElementById('dropdownadd').innerHTML;
var initialmcHtml = document.getElementById('multiplechoiceadd').innerHTML;
var initialpaHtml = document.getElementById('paragraphadd').innerHTML;
var initialrulesHtml = document.getElementById('cmprules').innerHTML;
tabid = 'tab1';
changeTab(tabid);
init();

if(location.href.indexOf("gid") != -1){
document.getElementById("coursesInGroup").style.display = "block";	
}

var myindex  = document.getElementById('maindd').selectedIndex;
var SelValue = document.getElementById('maindd').options[myindex].value;
        if (SelValue == 0){
                $j('*').find('input, textarea, button, select').attr('disabled','disabled');
                document.getElementById('maindd').disabled = false
        }
        else{
                document.getElementById('entirepage').style.display = "block";
        }
 

</script>

