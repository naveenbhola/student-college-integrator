<input type = "hidden" id = "citystart" value = "0"/>	
<input type = "hidden" id = "citycount" value = "15"/>

<?php 
echo "<script  language=\"javascript\">";
echo "var TOTALCITIES = ".count($city).";";
echo "</script>";
?>

<div id="left_Panel_n">
		<div class="raised_skyn"> 
			<div class="boxcontent_skyn">			  
                  <div class="row normaltxt_12p_blk bld deactiveselectyear" style="width:100%;background:#E3EDF9; border-top:1px solid #C8ECFC;  border-bottom:1px solid #C8ECFC; height:33px">
                              <div style = "margin-left:20px;margin-top:10px" >Refine By City</div>
                  </div>
<div class="lineSpace_10 deactivequestion">&nbsp;</div>
<div class="row anchorColor deactivequestion1 fontSize_12p"  id = "Allcities" style = "cursor:pointer;padding-left:25px" onClick = "selectCity('All','All')">All Indian Cities</div>

<div class="lineSpace_10 deactivequestion">&nbsp;</div>
<?php if(count($city) > 10 ) {?>
<div class = "deactivequestion"><img src="/public/images/upslider.gif" style = "cursor:pointer;padding-left:20px" onmouseover = "this.src = '/public/images/highlight2.gif'" onmouseout = "this.src = '/public/images/upslider.gif'" onClick = "cities('up')"/></div>
<?php }?>
					<div class="lineSpace_15 deactivequestion">&nbsp;</div>
<div id = "IndianCities"><?php for($i = 0;$i < 15 ; ++$i)
{?><div class = "anchorColor fontSize_12p deactivequestion1" id = "<?php echo $city[$i]['name'] ?>" onClick = "selectCity('<?php echo $city[$i]['name']?>','<?php echo $city[$i]['name']?>')" style = "cursor:pointer;padding-left:25px"><?php echo $city[$i]['name']?></div><div class = "linespace_10 deactivequestion">&nbsp;</div><?php }?></div>
<?php if(count($city) > 10){ ?>
<div class = "deactivequestion"><img src="/public/images/downslider.gif" style = "cursor:pointer;padding-left:20px;" onmouseover = "this.src ='/public/images/highlight1.gif'" onmouseout = "this.src = '/public/images/downslider.gif'" onClick = "cities('down')"/></div>
<?php } ?>
<div class="lineSpace_10 deactivequestion">&nbsp;</div>
			  
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
	</div>

 
