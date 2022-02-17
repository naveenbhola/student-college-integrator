<div class="section-cont" id="similarOnShiksha">
<?php 
$jsonData = array();
$institutesToBeExcluded = ''; 
$subCategory = $course->getDominantSubCategory()->getId(); 
if(!empty($_REQUEST['recommendedInstitutes'])) 
{ 
    $institutesToBeExcluded = implode(',', $_REQUEST['recommendedInstitutes']); 
    unset($_REQUEST['recommendedInstitutes']); 
} 
$jsonData = Modules::run('listing/ListingPage/similarOnShiksha', $course->getId(), intval($_REQUEST['city']), $institutesToBeExcluded, $subCategory, 'institute'); 
$jsonData = json_decode($jsonData, true);
echo $jsonData['recommendationHTML'];
?>
</div>
<script>
var showSimilarInstitutesRecommendation = 1;
var similarInstitutesCount = '<?php echo count($jsonData["recommendedInstitutes"]); ?>';
</script>