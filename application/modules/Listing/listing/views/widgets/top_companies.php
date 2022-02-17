<?php if($instituteType == 1){?>
<?php if(!empty($topCompaniesLogo)){?>

<div class="wdh100">
    <div id ="top_companies" style="display:none">
            
            <div class="whtRound">
            	<div class="wdh100">
                	<div style="height:190px;overflow:auto">
                    	<div align="center">
                            <?php foreach($topCompaniesLogo as $company){
                                $logoUrl = urldecode($company);
                             ?>
                            <img src="<?php echo $logoUrl;?>" vspace="5" hspace="5" />
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        

    </div>
<div class="nlt_head Fnt14 bld mb5">Placement - Companies</div>
<div>
<?php for($logoCount = 0 ;$logoCount<4 && $logoCount<count($topCompaniesLogo) ; $logoCount++){ ?>

                                        <?php $logoUrl = urldecode($topCompaniesLogo[$logoCount]);?>
                                        <img src="<?php echo $logoUrl;?>" border="0" class="mr7" vspace="4" />
                                        <?php }?>
                                    </div>
                                   <?php if(count($topCompaniesLogo)>4){?> <div><a href="javascript:void(0)" onClick="trackEventByGA('LinkClick','LISTING_OVERVIEW_VIEW_COMPANIES_LINK'); showAllCompanies();" id="viewAllCompaniesId" class="Fnt11">View all compaines</a></div><?php }?>





<script>
    function showAllCompanies(obj){
        var content = document.getElementById('top_companies').innerHTML;
        overlayParentAnA = document.getElementById('top_companies');
        overlayParentAnA = '';
        showOverlayAnA(367,78,'Top Recuriting Compaines',content);


    }
    function hideAllCompanies(){
        document.getElementById('top_companies').style.display = 'none';
    }

    </script>
</div>
 <div class="lineSpace_20">&nbsp;</div>
                                   <?php }?>
<?php }?>
