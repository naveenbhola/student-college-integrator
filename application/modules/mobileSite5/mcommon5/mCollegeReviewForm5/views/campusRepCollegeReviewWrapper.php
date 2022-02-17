<?php
    $this->load->view("mCollegeReviewForm5/collegeReviewHeaderSection");
    $this->load->view("mCollegeReviewForm5/collegeReviewCollegeDetails");
    $this->load->view("mCollegeReviewForm5/collegeReviewPersonalInfo");
?>
<script>
var _gaq = _gaq || []; _gaq.push(['_setAccount', 'UA-4454182-1']); _gaq.push(['_trackPageview']); (function() { var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true; ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s); })(); function _pageTracker(e){ this.type=e; this._trackEventNonInteractive=function(a, b, c, d, e) { _gaq=_gaq||[]; _gaq.push(['_trackEvent', a, b, c, d, e]); }; this._setCallback=function(e){ _gaq.push(['_set', 'hitCallback', e]); }; this._setCustomVar=function(e,t,n,r){ _gaq=_gaq||[]; _gaq.push(["_setCustomVar",e,t,n,r]) }; }var pageTracker=new _pageTracker; var gaCallbackURL;

    try{
        addOnBlurValidate(document.getElementById('reviewForm'));
    } catch (ex) {
    }
    try{
        initializeAutoSuggestorInstances();
    }catch(e){
        setTimeout(function(){initializeAutoSuggestorInstances();},2000);
    }
</script>

<?php foreach ($ratingParams as $key => $value) { ?>
    <script>
        markStarRating('<?php echo $value;?>','<?php echo $key;?>');      
    </script>
<?php } ?>

 <?php
if($anonymousFlag=='YES'){
?>
<script>
$('#anonymousFlag').attr('checked','true');
</script>
<?php
}
?>
<script>
$(document).ready(function($) {
    $("#keywordSuggest").blur(function(){ 
        setTimeout(function(){ CollegePickCheck();},100);}); 
});
$(document).click(function (e)
{
    var container = $("#suggestions_container");
    
    if (!container.is(e.target) // if the target of the click isn't the container...
    && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
    container.hide().html('');
    }
});
var innoExcelScript = "<?php echo INNO_EXCEL_SCRIPT; ?>";
</script>
