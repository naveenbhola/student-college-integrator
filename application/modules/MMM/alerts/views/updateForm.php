
	<div class="row">
		<div class="mar_left_10p normaltxt_11p_blk bld fontSize_12p" id="alertName1"><?php echo $alertDetails['alert_name'] ?></div>
	</div>
		<div class="lineSpace_5">&nbsp;</div>

	<div class="row">
		<div class="mar_left_10p normaltxt_11p_blk bld fontSize_12p" id="updateformTitle1"></div>
	</div>
		<div class="lineSpace_5">&nbsp;</div>

	<div class="row">
		<div class="mar_left_10p normaltxt_11p_blk">Send me an alert, if</div>
	</div>
		<div class="lineSpace_5">&nbsp;</div>
	
	<div class="row">
		<div class="mar_left_10p normaltxt_11p_blk" id="updateformCatTitle1"></div>
	</div>
		<div class="lineSpace_10">&nbsp;</div>
	
	<?php  $updateUrl = site_url('alerts/Alerts/updateAlert').'/'.$appId;
		echo $this->ajax->form_remote_tag( array('url'=> $updateUrl,'update' => 'form_update'));  ?>

	<input type="hidden" name="alertId" id="alertId"  value=""/>		

	<div class="row">
		<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20">Frequency:</div>
		<div class="float_L normaltxt_11p_blk">
			<select class="w20" name="frequency">
				<option value="daily">Once a day</option>
				<option value="weekly">Once a week</option>
				<option value="monthly">Once a month</option>
			</select>
		</div>
		<br clear="left" />
	</div>
		<div class="lineSpace_10">&nbsp;</div>	

	<div class="row">
		<div class="float_L normaltxt_11p_blk w12 pd_left_10p lineSpace_20 bld">Deliver to:</div>
		<div class="float_L normaltxt_11p_blk">
			<div> <input type="checkbox" name="emailCheck" value="on" onClick="checkAction(this,'emailText2')" <?php if(strcmp($alertDetails['MAIL'],'on')==0) echo "checked";?> /> Email <input type="text" name="emailText1" id="emailText2" style="<?php if(strcmp($alertDetails['MAIL'],'on')==0) echo "display:none;";?>margin-left:22px" /></div> 
<div class="lineSpace_5">&nbsp;</div>
			<div> <input type="checkbox" name="smsCheck" value="on" onClick="checkAction(this,'smsText2')" <?php if(strcmp($alertDetails['SMS'],'on')==0) echo "checked";?> /> Mobile <input type="text" name="smsText1" id="smsText2" style="<?php if(strcmp($alertDetails['SMS'],'on')==0) echo "display:none;";?>margin-left:18px" /> &nbsp; <a href="#">verify</a></div> 
<div class="lineSpace_5">&nbsp;</div>
			<div> <input type="checkbox" name="imCheck" value="on" onClick="checkAction(this,'imText2')" <?php if(strcmp($alertDetails['IM'],'on')==0) echo "checked";?>/> Messager <input type="text" name="imText1" id="imText2" style="<?php if(strcmp($alertDetails['IM'],'on')==0) echo "display:none;";?>" /></div> 
<div class="lineSpace_5">&nbsp;</div>
		</div>
		<br clear="left" />
	</div>
		<div class="lineSpace_10">&nbsp;</div>								

	<div class="row">
		<div style="margin-left:120px">
			<div class="buttr3">
				<button class="btn-submit13 w3" type="Submit">
					<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Update alerts</p></div>
				</button>
			</div>
			<div class="clear_L"></div>
		</div>			
	</div>
	
	</form>
