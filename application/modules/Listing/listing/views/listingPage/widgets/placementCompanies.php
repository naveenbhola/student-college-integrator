<div class="section-cont">
<h4 class="section-cont-title">Placement - Companies</h4>
<div>
<?php
	$i=1;
	foreach($recrutingCompanies as $company){
		
		if($i > 4){
			break;
		}
		if($company->getLogoURL()){
			$i++;
			if($i%2 != 0){
				
				$class = "flLt mL-10";
			}else{
				echo '<div class="spacer10 clearFix"></div>';
				$class = "flLt";
			}
			echo '<a href="#" class="'.$class.'" onclick="showAllCompanies(); return false;"><img src="'.$company->getLogoURL().'" alt="'.$company->getName().'"  title="'.$company->getName().'"/></a>';
		}
	}
	if(count($recrutingCompanies) > 4){
?>
		<div class="spacer5 clearFix"></div>
		<a href="#" onclick="showAllCompanies(); return false;">View all companies</a>
<?php
	}
?>
</div>
<div id="allCompanies" style="display:none;text-align:center">
	<div style="height:190px;overflow:auto;text-align:center">
<?php
	$i=1;
	foreach($recrutingCompanies as $company){
		if($company->getLogoURL()){
			$i++;
			if($i%2 != 0){
				
				$class = "flLt mL-10";
			}else{
				echo '<div class="spacer10 clearFix"></div>';
				$class = "flLt";
			}
			echo '<a href="#" class="'.$class.'"><img src="'.$company->getLogoURL().'" alt="'.$company->getName().'"  title="'.$company->getName().'"/></a>';
		}
	}
?>
	</div>
</div>
<script>
function showAllCompanies(obj){
    var content = $('allCompanies').innerHTML;
    overlayParentAnA = $('allCompanies');
    overlayParentAnA = '';
    showOverlayAnA(320,400,'Top Recuriting Compaines',content);
}
</script>
</div>