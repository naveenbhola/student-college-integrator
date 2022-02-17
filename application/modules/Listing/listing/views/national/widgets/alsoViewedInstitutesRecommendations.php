<div class="section-cont clear-width" id="alsoOnShiksha">
<?php 
$jsonData = array();
$jsonData = Modules::run('listing/ListingPage/alsoOnShiksha', $course->getId(), 'course');
$jsonData = json_decode($jsonData, true);
echo $jsonData['recommendationHTML'];
$_REQUEST['recommendedInstitutes'] = $jsonData['recommendedInstitutes'];
?>
</div>
<script>
var showAlsoViewedInstitutesRecommendation = 1;
var recommendedInstitutesCount = '<?php echo count($jsonData["recommendedInstitutes"]); ?>';
</script>