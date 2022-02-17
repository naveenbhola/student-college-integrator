<!--Right_Form-->
<?php 
if(!isset($cmsData)){
	if($registerText['paid']!="yes"){
		$this->load->view("listing_forms/widgetConnectInstitute");
	}
}
?>
<div id="">
    <div>
        <div class="float_L row">
        <?php 
                if($registerText['paid'] == "yes" ){
        ?>
            <div id="reqInfoContainersContainer">
                <div id="reqInfoContainer">
                <?php
                if(!isset($cmsData)) {
                        if($registerText['paid'] == "yes" ){
                            $this->load->view("naukrishiksha/get_free_alerts");
                        }
                }   
                ?>
                </div>            
            </div>
        <div class="lineSpace_10">&nbsp;</div>
                     <?php } ?>

        <?php 
    switch(strtolower($listing_type)){
    case 'institute':
        $this->load->view("naukrishiksha/relatedInstitutes",array('resultArr' => $details['relatedListings'])); 
        break;
    default:
        $this->load->view("common/relatedInstitutes",array('resultArr' => $relatedListings)); 
        break;
}

?>

            <?php 
	    if(!isset($validateuser[0]) && $registerText['paid'] == "yes" ){
			?>
            <div class="raised_pink" id="reqInfoContainersContainer"> 
						 <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
						 <div id="reqInfoContainer" class="boxcontent_pink">
						 <?php
						 if(!isset($cmsData)){
							 if(isset($reqInfo) && count($reqInfo) > 0){
								 $this->load->view("listing/requestInfo_after"); 
							 }
						 }
							?>

						 </div>
						 <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					 </div>
					 <div class="lineSpace_10">&nbsp;</div>
                     <?php } ?>

        <div class="lineSpace_10">&nbsp;</div>
        <div id="rightpanelads">
        </div>
        </div>
    </div>
</div>
<!--End_Right_From-->
