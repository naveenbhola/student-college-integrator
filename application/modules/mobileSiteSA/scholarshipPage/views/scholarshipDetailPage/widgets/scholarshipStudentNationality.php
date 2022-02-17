<?php 
if(!empty($applicableNationalities))
{
	$firstCountry = $applicableNationalities[0];
	unset($applicableNationalities[0]);
?>
<h2 class="titl-main">Eligible student nationality</h2>
<div class="sch-div">
    <p>This scholarship is applicable for students from <?php echo $firstCountry; 
    	if(!empty($applicableNationalities)){
            echo '<a href="javascript:;" id="viewAllNationalities">... +'.count($applicableNationalities).' more</a><span id="allNationalities" style="display:none;">, '.implode(', ', $applicableNationalities).'</span>';
        } ?></p>
</div>
<?php 
}
?>