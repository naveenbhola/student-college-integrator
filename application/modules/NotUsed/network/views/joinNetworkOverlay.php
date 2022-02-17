
<style>
.error
{
font-family:arial,verdana;
font-size:9px;
color:red;
}
</style>


<div id="joinNetworkOverlay" style="width:597px;position:absolute;display:none;z-index:20500000;">

   <div id="shadow-container">
        <div class="shadow1">
            <div class="shadow2">
                <div class="shadow3">
                    <div class="container">
                        <div class="lineSpace_1">&nbsp;</div>

 <?php 
if(!isset($callbackAfterJoin)){
$callbackAfterJoin = "addToNetwork(request.responseText);";
}
			   $redirecturl = $successfunction;
			     echo $this->ajax->form_remote_tag( array(
                            'url' => base_url().'/network/Network/addtoNetwork',
                            'update' => '',
                            'success' => "javascript:$callbackAfterJoin",
                            'failure'=>'javascript:addToNetwork(request.responseText)'
) 
                    ); 
            ?>

<input type = "hidden" id = "collegeId" name = "collegeId" value = "<?php echo $collegeId ?>"/>
<input type = "hidden" id = "cityid" name = "cityid" value = ""/>
<input type = "hidden" id = "courseId" name = "courseId" value = "0"/>
<input type = "hidden" id = "loggeduserid" name = "loggeduserid" value = "<?php echo $loggeduser?>"/>
<input type = "hidden" id = "GradYear" name = "GradYear" value = "<?php echo date('Y')?>"/>
<input type = "hidden" id = "collegeName" name = "collegeName" value = "<?php echo $collegeName?>"/>
<input type = "hidden" id = "grouptype" name = "grouptype" value = "<?php echo $grouptype?>"/>



						<div class="h33 normaltxt_11p_blk"  style="background:#6391cc">
							<div class="lineSpace_1">&nbsp;</div>
							<div style="margin-right:4px;margin-top:3px" align="right"><img src="/public/images/crossImg.gif" onClick = "hidejoinNetworkOverlay()" style="cursor:pointer" /></div>
							<div class="bld mar_left_10p fontSize_13p" style="color:#FFF">Join Network</div>							
						</div>
						<div class="bgOverLay">
							    <div class="bgOverLay lineSpace_13">&nbsp;</div>
                                <div class="row bgOverLay">    
                                    <div>
								        <div class="r1_3"><span>Select:</span></div><div class = "r2_3"><select style="height:18px;width:150px" class = "normaltxt_11p_blk_arial fontSize_12p" onChange = "showhideGraduationYear()" id = "status" name ="status">
			<option value = "Prospective Student">Prospective Student</option>
			<option value ="Student">Student</option>
			<option value = "Alumni">Alumni</option>
			<option value = "Faculty">Faculty</option>
			</select>
			</div>
                                       
                                        <div class="clear_L"></div>
                                    </div>
                                    </div>
			</div>                           					    
<div class="bgOverLay lineSpace_10">&nbsp;</div>  
<?php if($grouptype != "TestPreparation") { ?>
<div id = "CoursesDiv"> 
<?php $courses = $listingDetails[0]['courses'];
				 $locationlist = $listingDetails[0]['locations'];
				$totalCount = count($courses);
                $locationCount = count($locationlist);
               
                if($totalCount > 0)
                {?>
                <div class="bgOverLay row">
					<div>			                          
							<div class="r1_3">Course :</div>
							<div class="r2_3" id = "Collegecourse" name = "Collegecourse">
								<span>
									<select id = "courseTitle" name = "courseTitle" style="height:18px;width:400px;" class = "normaltxt_11p_blk_arial fontSize_12p">
										<?php	for($i=0;$i<$totalCount;$i++) {	?>
											<option value = "<?php echo $courses[$i]['courseId']?>" title = "<?php echo $courses[$i]['courseTitle']?>"><?php echo substr($courses[$i]['courseTitle'],0,60)?>"</option>
										<?php }?>                 
									</select>
								</span>
							</div>
                            <div class="clear_L"></div>
                   </div>                                                                   
               </div>
<div class="bgOverLay lineSpace_10">&nbsp;</div>    
<?php } ?>
</div>

               <?php
                if($locationCount > 0)
                { ?>
<div id = "locationDiv"> 
                            <div class="bgOverLay row">
                                   <div>
			                          
                                    <div class="r1_3">Location :</div>
  <div class="r2_3" id = "locationname" name = "locationname">
  
				<select id = "collegeLocation" onChange = "selectLocation();"  name = "collegeLocation" style="height:18px;width:400px;" class = "normaltxt_11p_blk_arial fontSize_12p">';

			<?php	for($i=0;$i<count($locationlist);$i++)		
				{	
                    $locationname = $locationlist[$i]['countryName'] . (($locationlist[$i]['cityName'] == '')? '':', ' .  $locationlist[$i]['cityName']) . (($locationlist[$i]['address'] == '')?'' : ', ' . $locationlist[$i]['address']) ;?>
					<option value = '<?php echo $locationlist[$i]['iltId']?>' title = '<?php echo $locationname?>'><?php echo substr($locationname,0,60)?></option>
			<?php }	?>
            </select>
                <!--document.getElementById('cityid').value = locationlist[0].iltId;-->
                
  
  
  </div>
                                        <div class="clear_L"></div>
                                   </div>                                  
                                 
                            </div>    
   <div class="bgOverLay lineSpace_10">&nbsp;</div>
</div>
<?php }} ?>
<div class = "bgOverLay row" id = "passingyear">			
	    <div class="r1_3">Graduation Year :</div>
	<div class = "r2_3" style="height:18px;width:55px;border:1px solid #999999;background-color:#FFFFFF; border-right:none;" id="year" name="year" ><?php echo date('Y');?></div><div><img src="/public/images/selectArrowUp.gif" onclick="increase()" /><br/><img src="/public/images/selectArrowDown.gif" onclick="decrease()" style="cursor:pointer" /></div>
</div>                                                      
                    
 <div class="bgOverLay lineSpace_5">&nbsp;</div>
<div class="row bgOverLay">
	<div class="errorPlace">
		<div class="r1_3 bgOverLay">&nbsp;</div>
		<div class="r2_3 errorMsg bgOverLay" id= "GradYear_error"></div>
		<div class="clear_L"></div>
	</div>
</div>

 <div class="bgOverLay lineSpace_5">&nbsp;</div>
<div class = "bgOverLay row">			
	    <div style = "margin-left:130px" class = "fontSize_12p">Type in the characters you see in the picture below  :</div>
<div>
<img  align = "absmiddle" style = "padding-left:130px" src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&randomkey=<?php echo rand(); ?>&secvariable=seccode2" id = "networkCaptacha"/>
<input type="text" validate = "validateSecurityCode" required = "1" minlength = "5" maxlength = "5" caption = "Security Code" style="width:150px" id = "joinseccode" name = "joinseccode"/>
</div>
</div>                                                      
                    
 <div class="bgOverLay lineSpace_5">&nbsp;</div>
<div class="row bgOverLay">
	<div class="errorPlace">
		<div class="r1_3 bgOverLay">&nbsp;</div>
		<div class="r2_3 errorMsg bgOverLay" id= "joincode_error"></div>
		<div class="clear_L"></div>
	</div>
</div>

 <div class="bgOverLay lineSpace_5">&nbsp;</div>
		<div class="row bgOverLay">								
                                <div style="margin-left:120px">
						       <div class="buttr2">
					<button class="btn-submit5 w16" value="" type="submit" onClick = "return checkGradYear();">
					<div class="btn-submit5"><p class="btn-submit6">Join</p></div>
					</button>
				</div>
						        <div class="clear_L"></div>
                                </div>
						    </div>
 <div class="bgOverLay lineSpace_5">&nbsp;</div>
						   
<?php echo '</form>'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<script>

document.getElementById('GradYear').value = parseInt(document.getElementById('year').innerHTML) ;

function increase()

{

           var val=parseInt(document.getElementById('year').innerHTML);
	   var year = new Date();
           var curryear = year.getFullYear();
           if(val < (curryear + 6))
	   val++;
           document.getElementById('year').innerHTML=val;
	   document.getElementById('GradYear').value = val;

}

function decrease()

{
            var val=parseInt(document.getElementById('year').innerHTML);
	    var year = new Date();
            var curryear = year.getFullYear();
            if(val > (curryear - 83))
            val--;
            document.getElementById('year').innerHTML=val;
	   document.getElementById('GradYear').value = val;

}
</script>
