<style>
.error
{
font-family:arial,verdana;
font-size:9px;
color:red;
}
</style>

<div id="joinSchoolNetworkOverlay" style="width:297px;position:absolute;display:none;z-index:20500000;">

   <div id="shadow-container">
        <div class="shadow1">
            <div class="shadow2">
                <div class="shadow3">
                    <div class="container">
                        <div class="lineSpace_1">&nbsp;</div>

  <?php 
if(!isset($callbackAfterJoin)){
$callbackAfterJoin = "addToSchoolNetwork(request.responseText);";
}
			   $redirecturl = $successfunction;
//echo $successfunction;
//echo base_url().'/network/Network/addtoNetwork';
			     echo $this->ajax->form_remote_tag( array(
                            'url' => base_url().'/network/Network/addtoSchoolNetwork',
                            'update' => '',
                            'success' => "javascript:$callbackAfterJoin",
                            'failure'=>'javascript:addToNetwork(request.responseText)'
) 
                    ); 
            ?>

<input type = "hidden" id = "collegeId" name = "collegeId" value = "<?php echo $collegeId ?>"/>
<input type = "hidden" id = "loggeduserid" name = "loggeduserid" value = "<?php echo $loggeduser?>"/>
<input type = "hidden" id = "GradYear" name = "GradYear" value = "<?php echo date('Y')?>"/>
<input type = "hidden" id = "grouptype" name = "grouptype" value = "<?php echo $grouptype?>"/>


						<div class="h33 normaltxt_11p_blk" style="background:#6391CC">
							<div class="lineSpace_1">&nbsp;</div>
							<div style="margin-right:4px" align="right"><img src="/public/images/crossImg.gif" onClick = "hideSchooljoinNetworkOverlay()" /></div>
							<div class="bld mar_left_10p fontSize_13p" style="color:#FFF">Join Network</div>							
						</div>
						<div class="bgOverLay">
							    <div class="bgOverLay lineSpace_13">&nbsp;</div>
                                <div class="row bgOverLay">    
                                    <div>
								        <div class="r1_2"><span>Select:</span></div><div class = "r2_2"><select style="height:18px;width:150px" class = "normaltxt_11p_blk_arial fontSize_12p" onChange = "showhideGraduationYear()" id = "status" name ="status">
			<option value = "Prospective Student">Prospective Student</option>
			<option value = "Student">Student</option>
			<option value = "Alumni">Alumni</option>
			<option value = "Faculty">Faculty</option>
			</select>
			</div>
                                       
                                        <div class="clear_L"></div>
                                    </div>
			</div>                
</div>           					    
            			    <div class="bgOverLay lineSpace_10">&nbsp;</div>                                                        

<div class = "bgOverLay row" id = "passingyear">			
	    <div class="r1_2">Pass Out Year:</div>
	<div class = "r2_2" style="height:18px;width:55px;border:1px solid #999999;background-color:#FFFFFF; border-right:none;" id="year" name="year" ><?php echo date('Y');?></div><div><img src="/public/images/selectArrowUp.gif" onclick="increase()" /><br/><img src="/public/images/selectArrowDown.gif" onclick="decrease()" style="cursor:pointer" /></div>
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
           if(val > (curryear - 57))
        val--;
    document.getElementById('year').innerHTML=val;
    document.getElementById('GradYear').value = val;

}
</script>


