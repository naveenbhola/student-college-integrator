<!--Start_Left_Panel-->
<div id="left_Panel_n">
	<div class="raised_skyn">
		<div class="boxcontent_skyn">                            
                  <div class="row normaltxt_12p_blk bld deactiveselectyear" style="width:100%;background:#E3EDF9; border-top:1px solid #C8ECFC;  border-bottom:1px solid #C8ECFC; height:33px">
                              <div style = "margin-left:20px;margin-top:10px" >Refine By Location</div>
                  </div>
				<div class="lineSpace_15 deactivequestion">&nbsp;</div>

				<div class="row anchorColor deactivequestion"  id="Allcountry" style="cursor:pointer" onClick="return selectCountry('All',1)">

<img src="/public/images/world-flag.gif" width="49" height="29" align="texttop" style="margin-left:3px" /> <span style="cursor:pointer;">All Countries</span>

				</div>
				<div class="lineSpace_10 deactivequestion">&nbsp;</div>


<?php 
global $countries; 
					foreach($countries as $countryId => $country) {
							$countryFlag = isset($country['flagImage']) ? $country['flagImage'] : '';

							$countryName = isset($country['name']) ? $country['name'] : '';
					?>
				<div class="row anchorColor deactivequestion" id = "<?php echo $countryName?>" style = "cursor:pointer" onClick = "return selectCountry('<?php echo $countryName?>',1)">
				<img src="<?php echo $countryFlag?>" width="49" height="29" align="texttop" style="margin-left:3px" /> <span style="cursor:pointer;"><?php echo $countryName?></span>
				</div>
				<div class="lineSpace_10 deactivequestion">&nbsp;</div>
<?php } ?>
		</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>

    </div>
</div>

<!--End_Left_Panel-->
