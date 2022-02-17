<?php 	
	$this->load->view('/mcommon/header',array('flag_off_home'=>true));
?>

<div id="head-sep"></div>
<div id="head-title">
	<h4 style="padding:5px 0">Request E-brochure</h4>
    <span>&nbsp;</span>
</div>
<?php global $user_logged_in;global $logged_in_usermobile;
	global $logged_in_user_name;
	global $logged_in_first_name;
	global $logged_in_last_name; 
	global $logged_in_user_email;global $logged_in_usermobile;
//	echo $user_logged_in;
?>
<div id="content-wrap">
	<div id="login-cont"><?php $hidden = array('currentUrl' => $current_url, 
			'referralUrl' => $referral_url,
			'courseArray'=>$courseArray,
			'user_email'=>$logged_in_user_email,
			'from_where'=>$from_where
			); $attributes = array("autocomplete" => "off", 'accept-charset' => 'utf-8');?>
	<?=form_open('muser/MobileUser/request_validation',$attributes,$hidden)?>
    	<ul>	
        <li><?php 
	if($user_logged_in!="false"){
		$attributes = array( 'name'=> 'user_first_name','value'=>$logged_in_first_name,'class'=>"login-field");}
	else
		$attributes = array( 'name'=> 'user_first_name','value'=>set_value('user_first_name'),'class'=>"login-field",'maxlength'=>"50",'minlength'=>"1");
	?>	
		<label><?=form_label('First Name', 'user_first_name')?></label>
                <div class="field-cont">
			<?=form_input($attributes)?>
	<div style="color:red;font-size:13px;"><?php $err=form_error('user_first_name');echo strip_tags($err);?> </div>
    	        </div>
	 </li>
	<li><?php 
 		        if($user_logged_in!="false"){ 
 		                $attributes = array( 'name'=> 'user_last_name','value'=>$logged_in_last_name,'class'=>"login-field");} 
 		        else 
 		                $attributes = array( 'name'=> 'user_last_name','value'=>set_value('user_last_name'),'class'=>"login-field",'maxlength'=>"50",'minlength'=>"1"); 
 		        ?> 
 		                <label><?=form_label('Last Name', 'user_last_name')?></label> 
 		                <div class="field-cont"> 
 		                        <?=form_input($attributes)?> 
 		        <div style="color:red;font-size:13px;"><?php $err=form_error('user_last_name');echo strip_tags($err);?> </div> 
 	</div>
 	</li> 
 	<li><?php if($user_logged_in!="false")$attributes = array('name'=> 'user_email','value'=>$logged_in_user_email,'disabled'=>"disabled",'class'=>"login-field"); else $attributes = array('pattern'=>"^(?:[-+âˆ¼=!#$%&amp;'*/?\^`{|}\w]+)(?:\.[-+âˆ¼=!#$%&amp;'*/?\^`{|}\w]+)*@(?:[a-zA-Z0-9][-a-zA-Z0-9]*[a-zA-Z0-9]\.)+[a-zA-Z]{2,6}$",'required'=>'','name'=> 'user_email','value'=>set_value('user_email'),'class'=>"login-field",'maxlength'=>"125");
		?>
            	<label><?=form_label('E-mail', 'user_email')?></label>
                <div class="field-cont">
			<?=form_email($attributes)?>
			<div style="color:red;font-size:13px;"><?php $err=form_error('user_email');echo strip_tags($err);?></div>
              </div>
           </li>
		<?php $courseAtrr_decoded=(base64_decode($courseArray,false));
		      $courseAtrr_unserialized=(unserialize($courseAtrr_decoded));
		?>		
	   <li><?php if(!empty($courseAtrr_unserialized)){?>
		<label><?=form_label('Course of Interest', 'list')?></label>
		<!--<div class="select-field"">-->
			<?php 	
					$flag =0;
					$countTotalCourses = 0;
					$courseListOptions = array();
					$countTotalCourses = count($courseAtrr_unserialized);
					$firstCityId;
					$tempArray = array();
					$i=0;
					$case ;
					$showCoursesBycookie=0;
					if(isset($_COOKIE['userCityPreference'])  &&  $_COOKIE['countryId']!=2){
							$cookieCityId  = explode(':',$_COOKIE['userCityPreference']);
							$cookieCityId = $cookieCityId[0];
							if($cookieCityId != 1) $flag =1;
					}
					foreach($courseAtrr_unserialized as $name=>$value){
							$value=explode('*', $value);
							$name = explode (' | ' ,$name);
							$a = $value[0].'|'.$value[4]  ;
							$b = $name[0] ;
							if($value[3] == $cookieCityId){
								$showCoursesBycookie = '1';
							}	
							if($i==0){
								$firstCityId = $value[3];
							}
							if($value[3] == $firstCityId)
							{
								$countTotalCourses--;
							}$i++;
						}
						
						if ($countTotalCourses == 0) {
							$name1='';
							$case = '1';
							foreach($courseAtrr_unserialized as $name=>$value){
								$value=explode('*', $value);
								$name = explode (' | ' ,$name);
								$a = $value[0].'|'.$value[3].'|'.$value[4];
								$b = $name[0] ;
								$courseListOptions[$a] = $b;
							}
						}
						if($case!=1){
						foreach($courseAtrr_unserialized as $name=>$value){
							
							$value=explode('*', $value);
							$name = explode ('|' ,$name);
							$a = $value[0].'|'.$value[3].'|'.$value[4] ;
                            if($name[2]=='' && $name[1]!=''){
                                $b= $name[0] . ' - ' . $name [1];
                            }
                            else{
							    $b = $name[0] ;
							}
                            if($value[3]!=$cookieCityId && $flag == 1 && $showCoursesBycookie){
									continue;
							}else{	
								$name1 = $name[2] ? $name[1]. ' - '. $name[2] : 'Other Cities';
								$courseListOptions[$name1][$a] = $b;
							}
				        }		
						$tempOtherCities = $courseListOptions['Other Cities'];	
						unset($courseListOptions['Other Cities']);
						$courseListOptions['Other Cities']= $tempOtherCities;	
					}
			$selected_value = "";
			//echo "ASDSDSDSD".$selected_course;
			foreach($courseListOptions as $key=>$value) {

				if(is_array($value) && count($value) > 0) {
					foreach($value as $key1=>$value1) {
						$temp_array = explode("|",$key1);
						if(!empty($temp_array[0]) && $temp_array[0] == $selected_course) {
							$selected_value= $key1;
							break;
						}
					}
				} else {
					$temp_array = explode("|",$key);
					if(!empty($temp_array[0]) && $temp_array[0] == $selected_course) {
						$selected_value= $key;
						break;
					}
				}
			}
			//echo "selected valeu".$selected_value; 	
			?>
			<?php// $courseOption =array();foreach($courseAtrr_unserialized as $name=>$value){$value=explode('*', $value);$value=$value[0].'|'.$value[4];
		//	$courseOption[$value]=$name;}?> 
			<?php echo form_dropdown('list', $courseListOptions , array($selected_value),"class='select-field'");?>
		<!--	</div> -->
          <?php }?> </li>
		<li><?php if($user_logged_in!="false")$attributes = array( "pattern"=>"\d{10}","required"=>"",'name'=> 'user_mobile','value'=>$logged_in_usermobile,'class'=>"login-field",'maxlength'=>"10");
		else $attributes = array( "pattern"=>"\d{10}","required"=>"",'name'=> 'user_mobile','value'=>set_value('user_mobile'),'class'=>"login-field",'maxlength'=>"10");?>
            	<label><?=form_label('Mobile', 'user_mobile')?></label>
                <div class="field-cont">
			<?=form_mobile($attributes)?>
			<div style="color:red;font-size:13px;"><?php $err=form_error('user_mobile');echo strip_tags($err);?></div>
              	</div>
           </li>
           <li>
            <div style="color:red;font-size:13px;"><?php if($show_error=="User already exists."){echo $show_error." Please  ";?><a href="/muser/MobileUser/login/">Login </a><?php }?></div>
           </li><?php $attributes = array( 'name'=> 'login','value'=>'Request E-brochure','class' => 'orange-button');?>
            <li style="padding-top:5px"><?=form_submit($attributes)?> &nbsp; &nbsp; <strong>
		<?php if($from_where == 'SEARCH'): ?><br/><a style="position:relative;top:10px;"href="<?php echo url_base64_decode($current_url);?>">Back to Search Results</a><?php else:?><a href="javascript: window.history.go(-1)"> Cancel</a><?php endif;?></strong></li>
	
    </ul>    
   <div class="clearFix"></div>	
    </div>
<?php $this->load->view('/mcommon/footer');?>

