<?php
    $layerAnchorOpen = '<a href="JavaScript:void(0);" onclick="openRecruitingCompaniesLayer();"	class="flRt font-13">';
    $layerAnchorClosed = '</a>';
    //no need to show if on a non mba course
    //if($nonMba == true){
        $companyListClass = "animation-company-list";
        $companyListLimit = 4;
?>
	<ol class="<?=$companyListClass?> clear-width">
        <?php
        if(count($recruitingCompanies)<=$companyListLimit){
            $maxNoOfCompaniesToDisplay = count($recruitingCompanies);
            $layerAnchorOpen = $layerAnchorClosed = "";
        }
        else {
            $maxNoOfCompaniesToDisplay = $companyListLimit;
        }
        for($i=0;$i<$maxNoOfCompaniesToDisplay;$i++){ 
		echo '<li>'.$layerAnchorOpen.'<img src="'.$recruitingCompanies[$i]->getLogoURL().'" alt="'.$recruitingCompanies[$i]->getName().'"  title="'.$recruitingCompanies[$i]->getName().'" />'.$layerAnchorClosed.'</li>';
	 } ?>	
	</ol>
        <p>
            <?php
                if(count($recruitingCompanies)>$companyListLimit){
                    echo $layerAnchorOpen.'View all '.count($recruitingCompanies).' companies >'.$layerAnchorClosed;
                }
                
            ?>
        </p>

<?php //no need to show if on a non mba course
    if($nonMba == true){ ?>
        <!--</div> bubble-box-details ends-->
    <?php } ?>    
<?php if(count($recruitingCompanies)>4){ ?>
<div id="modal-overlay"></div>
<div class="management-layer" id="recruiting-companies-layer" style="<?php echo (count($recruitingCompanies)<=18?'width:380px;':'width:400px;');?>display:none;z-index:999;">
   <div class="layer-head">
    <a href="JavaScript:void(0);" onclick="closeRecruitingCompaniesLayer();" class="flRt sprite-bg cross-icon" title="Close"></a>
   	<div class="layer-title-txt flLt">Recruiting Companies</div>
    
   </div>
   <div class="clearFix"></div>
    <!-- <div class="company-thumbs scrollbar1 apply-softscroll" style="<?php echo (count($recruitingCompanies)>=19?"height: 320px;":"");?>width:410px;">-->
    <div class="company-thumbs  scrollbar1 testscroller" style="margin-top:15px;">
        <?php if(count($recruitingCompanies)>=19){ ?>
            <div class="scrollbar">
                    <div class="track">
                            <div class="thumb">
                                    <div class="end"></div>
                            </div>
                    </div>
            </div>
            <div class="viewport" style="height:290px;width:380px;">
                <div class="overview">
        <?php } ?>
                    <!-- my content-->
                    <ul style="margin-left:0px;">
                        <li style="list-style: none;padding-right:0px;">
                        <?php 
                            for($i=0;$i<count($recruitingCompanies);$i++){
                                if($i%3==0 && $i !=0){echo '</li><li style="padding-right:0px;">'; }
                                echo '<img class = "recruitmentLogoImg" src="" alt="'.$recruitingCompanies[$i]->getName().'" title="'.$recruitingCompanies[$i]->getName().'" />';
                            }
                        ?>
                        </li>
                    </ul>
        <?php if(count($recruitingCompanies)>=19){ ?>
                </div>
            </div>
        <?php } ?>
        <!--<div class="clearFix"></div>-->     
    </div>
</div>
<?php } ?>
<script>
    var recruitingURLs = [];
    var companyImgLoaded = false;
  <?php for($i=0;$i<count($recruitingCompanies);$i++){ ?>
  recruitingURLs[<?php echo $i?>] = "<?php echo $recruitingCompanies[$i]->getLogoURL() ?>";
  <?php }?>
function openRecruitingCompaniesLayer(){
    if (!companyImgLoaded) {
        $j(".recruitmentLogoImg").each(function(index){
            $j(this).attr('src',recruitingURLs[index]);
        });
        companyImgLoaded = true;
    }
    if(typeof($j("#recruiting-companies-layer"))!='undefined'){
        $j("#modal-overlay").css({
            'position':'fixed',
            'background-color':'#000000',
            'opacity':0.35,
            '-ms-filter': 'progid:DXImageTransform.Microsoft.Alpha(Opacity=35)',
            'height':$j(window).height()+'px',
            'width':$j(window).width()+'px',
            'top':'0px',
            'left':'0px',
            'z-index':999}).show();
        var posTop = ($j(window).height()/2) - ($j("#recruiting-companies-layer").height()/2);
        var posLeft = ($j(window).width()/2) - ($j("#recruiting-companies-layer").width()/2);;
        $j("#recruiting-companies-layer").css({
            'position':'fixed',
            'top':posTop+'px',
            'left':posLeft+'px',
            'z-index':9999}).show();
    }
    //alert(content);
    $j(".testscroller").tinyscrollbar();
}
function closeRecruitingCompaniesLayer(){
    $j("#modal-overlay").hide();
    $j("#recruiting-companies-layer").hide();
}
</script>