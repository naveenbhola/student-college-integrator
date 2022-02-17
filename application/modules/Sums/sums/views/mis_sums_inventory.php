<div class="row fontSize_18p OrgangeFont bld"><?php echo ucfirst($mis_reprot_type);?></div>
<div>
	<table width='65%' border=0>
		<tr>
			<td>
				<?php
				if(array_key_exists(27,$sumsUserInfo['sumsuseracl']))
				{
				?>
				<input type="radio" name="shikshainventory" onclick=" return switchInventorysection('Byinventory');" value="bcpm" id="mis_inv_report1" /> Client Product MIS
				<?php
				}
				?>
        		</td>
        		<td>
				<?php
				if(array_key_exists(28,$sumsUserInfo['sumsuseracl']))
				{
				?>
				<input type="radio" name="shikshainventory" onclick=" return switchInventorysection('Byinventory_csm');" value="csm" id ="mis_inv_report2" /> Client Subscription MIS
				<br><br><br>
				<?php
				}
				?>
			</td>
		</tr>
		<tr>
			<td>
				<?php
				if(array_key_exists(29,$sumsUserInfo['sumsuseracl']))
				{
				?>
				<input type="radio"  name="shikshainventory" onclick=" return switchInventorysection('shikshainventory');" value="scpim" id="mis_inv_report3" /> Shiksha Category Product Inventory MIS
				<?php
				}
				?>
			</td>
			<td>
				<?php
				if(array_key_exists(30,$sumsUserInfo['sumsuseracl']))
				{
				?>
				<input type="radio" name="shikshainventory" onclick=" return switchInventorysection('BySearch');" id="mis_inv_report4" value="sspm" /> Shiksha Search Product  MIS
				<?php
				}
				?>
			</td>
		</tr>
	</table>
</div>
<div id="dataLoader" style="display:none; width:100%;" align="left">
	Please wait .. List is loading ..<img src="/public/images/loader.gif" align="absmiddle" />
</div>
<div  id='Byinventory'  >
	<div class="lineSpace_10">&nbsp;</div>
        <div>
        	<div  class="bld fontSize_12p" style="width:500px">Client Product MIS :-</div>
        	<div class="lineSpace_10">&nbsp;</div>
                <div>
                	<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Branch: &nbsp;</div>
			<div>
			<select  id="sums_mis_branch1" multiple name="sums_mis_branch1[]" onchange="ajax_change_branch(this.value,'sums_mis_branch1','sums_mis_executive1')"  size="5" style="width:200px">
			<option value="-1" selected >All</option>
			<?php for($i=0;$i<count($branchlist);$i++) { ?>
			<option value="<?php echo $branchlist[$i]['BranchId'];?>"><?php echo $branchList[$i]['BranchName'];?></option>
			<?php } ?>
			</select>
			</div>
			<div class="clear_L"></div>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div>
			<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Executive: &nbsp;</div>
			<div>
			<select id ="sums_mis_executive1" name="sums_mis_executive1[]" multiple size="5" style="width:200px"><option value="-1" selected >All</option></select>
			</div>
			<div class="clear_L"></div>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div>
			<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Client: &nbsp;</div>
			<div class="float_L" >
			<select id="sums_mis_client1" multiple size="5" name="sums_mis_client1[]" style="width:200px"><option value="-1" selected >All</option>
			<?php for($i=0;$i<count($clientList);$i++) { ?>
				<option value="<?php echo $clientList[$i]['userId'];?>" ><?php echo $clientList[$i]['ManagerName'];?></option>
			<?php } ?>
			
			</select>
			</div>
			<div class="clear_L"></div>
		</div>
        </div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div  id='Byinventory_csm' style="display:none;" >
	<div class="lineSpace_10">&nbsp;</div>
        <div>
        	<div  class="bld fontSize_12p" style="width:500px">Client Subscription MIS :-</div>
        	<div class="lineSpace_10">&nbsp;</div>
                <div>
                	<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Branch: &nbsp;</div>
			<div>
			<select  id="sums_mis_branch2" multiple name="sums_mis_branch2[]" onchange="ajax_change_branch(this.value,'sums_mis_branch2','sums_mis_executive2')"  size="5" style="width:200px">
			<option value="-1" selected >All</option>
			<?php for($i=0;$i<count($branchlist);$i++) { ?>
			<option value="<?php echo $branchlist[$i]['BranchId'];?>"><?php echo $branchList[$i]['BranchName'];?></option>
			<?php } ?>
			</select>
			</div>
			<div class="clear_L"></div>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div>
			<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Executive: &nbsp;</div>
			<div>
			<select id ="sums_mis_executive2" name="sums_mis_executive2[]"  multiple size="5" style="width:200px"><option value="-1" selected >All</option></select>
			</div>
			<div class="clear_L"></div>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
		<div>
			<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Client: &nbsp;</div>
			<div class="float_L" >
			<select id="sums_mis_client2" multiple size="5" name="sums_mis_client2[]" style="width:200px"><option value="-1" selected >All</option>
			<?php 
			print_r($clientList,true);
			for($i=0;$i<count($clientList);$i++) { ?>
				<option value="<?php echo $clientList[$i]['userId'];?>" ><?php echo $clientList[$i]['ManagerName'];?></option>
			<?php } ?>			
			</select>
			</div>
			<div class="clear_L"></div>
		</div>
		<div>
                        <div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Status: &nbsp;</div>
                        <div class="float_L" >
                        <select name="sums_client_subscription_status" style="width:200px">
			<option value="ACTIVE">Active</option>
			<option value="INACTIVE">Inactive </option>
			</select>
                        </div>
                        <div class="clear_L"></div>
                </div>

        </div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div id="shikshainventory" style="display:none;" >
        <div class="lineSpace_10">&nbsp;</div>
          <div >
                  <div class=" float_L bld fontSize_12p" style="width:500px">Shiksha Category Product Inventory MIS :- &nbsp;</div>
                  <div class="float_L"></div>
                  <div class="clear_L"></div>
          </div>
          <div class="lineSpace_10">&nbsp;</div>
</div>

<div   id="BySearch" style="display:none;" >
        <div class="lineSpace_10">&nbsp;</div>
          <div>
                  <div class=" float_L bld fontSize_12p" style="width:500px">Shiksha Search Product MIS  :- &nbsp;</div>
                  <div class="float_L"></div>
                  <div class="clear_L"></div>
          </div>
          <div class="lineSpace_10">&nbsp;</div>
</div>
<script language='javascript'>
	document.MisForm.shikshainventory[0].checked = true;
</script>

