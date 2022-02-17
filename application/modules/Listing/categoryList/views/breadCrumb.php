<script>
	function setAllCitiesCookieBreadCrumb(){
		setCookie("userCityPreference","1:1:2");
		return true;
	}
</script>
<div class="breadcrumb2">
                    <?php
                    $totalBreadCrumbs = count($breadCrumb);
                    for($i = 0; $i < $totalBreadCrumbs; $i++) {?>
                         <span itemscope itemtype="https://data-vocabulary.org/Breadcrumb">                     
                         <?php if($breadCrumb[$i]['url'] != ""){?>
                         <a href="<?php echo $breadCrumb[$i]['url']?>" <?php if($breadCrumb[$i]['allCityFlag']){echo "onclick=\"setAllCitiesCookieBreadCrumb()\"";} ?>itemprop="url"><span itemprop="title"><?php echo $breadCrumb[$i]['name'];?></span></a>
                         <?php } else {?>
                         <?php echo $breadCrumb[$i]['name'];?>
                         <?php } ?>
                         </span>
                     <?php if($i != ($totalBreadCrumbs -1)) {?>   
                     <span class="breadcrumb-arrow">&rsaquo;</span>
                     <?php
                          }                     
                     } ?>
</div>