<div class="mar_full_10p">
  	<div >
  		<div class="float_L" style="width:100%">
      			<div class="raised_lgraynoBG"> 
        			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
        			<div class="boxcontent_lgraynoBG">
					<div class="mar_full_10p">
					<div class="lineSpace_5">&nbsp;</div>
					<div class="row fontSize_18p OrgangeFont bld"><?php echo ucfirst($mis_reprot_type);?></div>
					<div class="grayLine row"></div>
					<div class="lineSpace_20">&nbsp;</div>
						<div id="dataLoader" style="display:none; width:100%;" align="center">
							Please wait .. List is loading ..<img src="/public/images/loader.gif" align="absmiddle" />
						</div>
					<div>
					<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Branch: &nbsp;</div>
					<div class="float_L">
						<select  id="sums_mis_branch" multiple name="sums_mis_branch[]" onchange="ajax_change_branch(this.value,'sums_mis_branch','sums_mis_executive')"  size="5" style="width:200px">
						<option value="-1" selected >All</option>
						<?php for($i=0;$i<count($branchlist);$i++) { ?>
						<option value="<?php echo $branchlist[$i]['BranchId'];?>" ><?php echo $branchList[$i]['BranchName'];?></option>
						<?php } ?>
						</select>
					</div>
            				<div class="clear_L"></div>
          			</div>
        			<div class="lineSpace_10">&nbsp;</div>
	 			<div>
            			<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Executive: &nbsp;</div>
            			<div class="float_L" ><select id ="sums_mis_executive" name="sums_mis_executive[]" multiple size="5" style="width:200px"><option value="-1" selected >All</option></select></div>
            			<div class="clear_L"></div>
			</div>
	 		<div class="lineSpace_10">&nbsp;</div>
	   		<div>
              		<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Client: &nbsp;</div>
              		<div class="float_L"  ><select id="sums_mis_client" multiple size="5" name="sums_mis_client[]" style="width:200px">
              		<option value="-1" selected >All</option>
              		<?php for($i=0;$i<count($clientList);$i++) { ?>
				<option value="<?php echo $clientList[$i]['userId'];?>" ><?php echo $clientList[$i]['ManagerName'];?></option>
			<?php } ?>
              		</select></div>
              		<div class="clear_L"></div>
          	</div>
		<div class="lineSpace_10">&nbsp;</div>