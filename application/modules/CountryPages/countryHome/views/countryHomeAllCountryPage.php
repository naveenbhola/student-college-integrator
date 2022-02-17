<?php
    $headerComponents = array(
			    'css'               => array('studyAbroadCommon', 'countryHome'),
			    'canonicalURL'      => $seoData['canonicalUrl'],
			    'title'             => ucfirst($seoData['seoTitle']),
			    'metaDescription'   => ucfirst($seoData['seoDescription']),
                'pageIdentifier'    => $beaconTrackData['pageIdentifier']			
			);
    
    // Study Abroad Header file
    $this->load->view('common/studyAbroadHeader', $headerComponents);
    
    echo jsb9recordServerTime('SA_COUNTRY_HOME_PAGE',1);
?>

<!-- Country Home Bread Crumb File -->
<?php $this->load->view('countryHomeBreadcrumb');?>


    <div class="content-wrap clearwidth" style="padding-top:0;">  
        <div class="country-wrapper clearwidth">
            
            <!-- Country Home All Country Page Title -->
            <div class="country-title" style="margin-top:0;">Study Abroad</div>
            <p class="country-content">Find the study abroad destination that's perfect for you. Choose from the list of countries below to know all about its top universities & colleges for undergraduate & postgraduate programs, and more. Get all important information you need from our articles and guides. Get ready for all the important information you need to make a decision about the study abroad destination of your choice!</p>
            
            <div class="country-list-wrap">
                <div class="popular-widget noMargin" style="width:100%;">
                    <div class="popular-widget-title">Browse study abroad countries</div>
                    <div class="popular-widget-detail" style="width:100%;">
                        <strong style="margin-bottom:5px">Click on one to get more information</strong>
                        <table cellpadding="0" cellspacing="0" class="country-list-table">
                            <?php   //_p($abroadCountriesData);
                                    $i=0;
                                    $rowbgClassFlag = 1;
                            while($i<count($abroadCountriesData)){
                            ?>
                            <tr <?php if((++$rowbgClassFlag)%2 == 1){echo 'class="alt-rowbg"';}?>>
                            	<td <?php if(!$abroadCountriesData[$i+1]['name']){echo 'colspan="3"';} ?>><a href="<?=$abroadCountriesData[$i]['countryHomeUrl']?>"><i class="flags <?=strtolower(str_replace(' ','',$abroadCountriesData[$i]['name']))?> flLt"></i><span class="country-link">Study in <?=$abroadCountriesData[$i++]['name']?></span></a></td>
                                <?php if($abroadCountriesData[$i]['name']){?>
                                    <td <?php if(!$abroadCountriesData[$i+1]['name']){echo 'colspan="2"';} ?>><a href="<?=$abroadCountriesData[$i]['countryHomeUrl']?>"><i class="flags <?=strtolower(str_replace(' ','',$abroadCountriesData[$i]['name']))?> flLt"></i><span class="country-link">Study in <?=$abroadCountriesData[$i++]['name']?></span></a></td>
                                <?php }?>
                                <?php if($abroadCountriesData[$i]['name']){?>
                                    <td><a href="<?=$abroadCountriesData[$i]['countryHomeUrl']?>"><i class="flags <?=strtolower(str_replace(' ','',$abroadCountriesData[$i]['name']))?> flLt"></i><span class="country-link">Study in <?=$abroadCountriesData[$i++]['name']?></span></a></td>
                                <?php }?>
                            </tr>
                            <?php   }
                            ?>
                        </table>
                    </div>
                </div>
	    </div>
        </div>
    </div>

<?php
    $footerComponents = array(
			    'js'                => array('countryHome')
			);
	$this->load->view('common/studyAbroadFooter',$footerComponents);
?>
