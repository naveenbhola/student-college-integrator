<div style="border:solid 1px #acacac;position:absolute;z-index:200;width:300px;display:none; background:#ffffff;" id="overlayCategoryHolder" name="overlayCategoryHolder" onmouseover="this.style.display=''; overlayHackLayerForIE('overlayCategoryHolder', document.getElementById('overlayCategoryHolder'));" onmouseout="dissolveOverlayHackForIE();this.style.display='none'">
    <div class="mar_full_10p">
        <div class="lineSpace_5">&nbsp;</div>
        <div style="line-height:18px;padding-left:5px;" class="bld">Categories</div>
        <div class="lineSpace_5">&nbsp;</div>
	    <div style="position:relative;width:100%">
			<ul class="categoryHeaderContent">
				<?php
                $otherElementId = '';
                $otherElementUrlName = '';
                $otherElement = 'Others...';
				foreach($subCategoryList as $subcategory)
                {
                    if(strpos($subcategory['name'],'Others') !== false){
                        $otherElementId = $subcategory['boardId'];
                        $otherElementUrlName = $subcategory['urlName'];
                        $otherElement = $subcategory['name'];
                        continue;
                    }
					//Amit Singhal : Hiding some of the sub categories in Naukri shiksha page
					if($naukrshikshapage == 1){
						$naukrilearningexcultionlist = array(136,137,131,134,135);
						if(in_array($subcategory['boardId'],$naukrilearningexcultionlist)){
							continue;
						}
					}
					//Amit Singhal : Hiding some of the sub categories in Naukri shiksha page
					if($categoryId == $subcategory['boardId'])
					{
						echo "<li class=\"aselected\" style=\"width:100%\" id=\""."subCat_".$subcategory['boardId']."\"><a href=\"\" style = \"font-size:12px\" onClick=\"document.getElementById('catstartoffset').value = 0;document.getElementById('catsubcat').value = '".$subcategory['urlName']."';opencatpage(2);return false;\"><span>".$subcategory['name']."</span></a></li>";
					}
					else
					{
						echo "<li style=\"width:100%\" id=\""."subCat_".$subcategory['boardId']."\"><a href=\"\" style = \"font-size:12px\" onClick=\"document.getElementById('catstartoffset').value=0;document.getElementById('catsubcat').value = '".$subcategory['urlName']."';opencatpage(2);return false;\"><span>".$subcategory['name']."</span></a></li>";
					}
                }
                if($otherElementId != '' && !$naukrshikshapage) {
                    if($otherElementId == $categoryId) {
                        $selectedClass = 'aselected'; 
                    } else{
                        $selectedClass = '';
                    }
?>
             <li class="<?php echo $selectedClass; ?>" style="width:100%" id="subCat_<?php echo $otherElementId; ?>"><a href="" style = "font-size:12px" onClick="document.getElementById('catstartoffset').value=0;document.getElementById('catsubcat').value = '<?php echo $otherElementUrlName?>';opencatpage(2);return false;"><span><?=$otherElement?></span></a></li>
				<?php
				    }
				?>  
			</ul>
			<div class="clear_L" style="line-height:1px">&nbsp;</div>
	    </div>
        <div class="lineSpace_10">&nbsp;</div> 
    </div>
</div>
