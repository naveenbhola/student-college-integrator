<?php
$this->load->view("search/searchOverlay");
//echo "<pre>".print_r($scholarhips)."</pre>";
$i=0;
foreach($scholarhips as $scholarship) {
    $eligibility = '';
    $otherCriteria = '';
    $url = $scholarship['url'];
    foreach($scholarship['eligibility'] as $eligibilityCritrias) {
        if($eligibilityCritrias['criteria'] == 'res_stat') $eligibilityCritrias['criteria'] = 'Resident Status';
        if(strtolower($eligibilityCritrias['criteria']) == 'other') {
            $otherCriteria = $eligibilityCritrias['value'];    
        } else {
            $eligibility .= html_entity_decode($eligibilityCritrias['criteria']) .':'. html_entity_decode($eligibilityCritrias['value']) .'; ';
        }
    }
    $eligibility = rtrim($eligibility,';');
    $applicableTo = strlen($scholarship['applicableTo'])>50?substr($scholarship['applicableTo'],0,50):$scholarship['applicableTo'];
    $number = strlen($scholarship['number'])>50?substr($scholarship['number'],0,50):$scholarship['number'];
    $value = strlen($scholarship['value'],0,50)?substr($scholarship['value'],0,50):$scholarship['value'];
    $eligibilityRow="";
    if($eligibility != "")
    {
        $eligibilityRow.='<span class="dgreencolor">Eligibility:&nbsp;</span>'.$eligibility.' ; ';
    }
    if($applicableTo != '')
    {
        $eligibilityRow.='<span class="dgreencolor">Applicable to:&nbsp;</span>'.$applicableTo;
    }

    $numberRow = '';
    if($value != '')  {
        $numberRow .= '<span class="dgreencolor">Value:&nbsp;</span>'. $value.' ; ';
    }
    if($number != '') {
        $numberRow .= ' <span class="dgreencolor">Number:&nbsp;</span>'. $number .' ; ';
    }
    $numberRow = $numberRow == '' ? '&nbsp;' : $numberRow;
    $numberRow = '<div>'.$numberRow.'</div>';
    $contentLine =   '<div>'.$eligibilityRow.$numberRow.'</div>';
    if($validateuser=="false")
    {
        $smsClickAction = "javascript:showuserLoginOverLay(this,'SCHOLARSHIP_SCHOLARSHIPLIST_MIDDLEPANEL_SMSCLICK','refresh');";
        $mailClickAction = "javascript:showuserLoginOverLay(this,'SCHOLARSHIP_SCHOLARSHIPLIST_MIDDLEPANEL_MAILCLICK','jsfunction','showSearchMailOverlay','scholarship','".$scholarship['id']."','". $url ."');";
        $saveProductAction= "javascript:showuserLoginOverLay(this,'SCHOLARSHIP_SCHOLARSHIPLIST_MIDDLEPANEL_SAVERESULTCLICK','jsfunction','saveProduct', 'scholarship','".$scholarship['id']."');";
        $requestInfoUrl = "javascript:showuserLoginOverLay(this,'SCHOLARSHIP_SCHOLARSHIPLIST_MIDDLEPANEL_REQUESTINFOCLICK','refresh');";

    }
    else
    {
        if($validateuser[0]['quicksignuser'] == 1 && $validateuser[0]['requestinfouser'] == 1)
        {
            $base64url = base64_encode($_SERVER['REQUEST_URI']);
            $quickClickAction = "javascript:location.replace('/user/Userregistration/index/".$base64url."/1');return false;";
            $smsClickAction = $quickClickAction;
            $mailClickAction = $quickClickAction;
            $saveProductAction = $quickClickAction;
            $requestInfoUrl = $quickClickAction;
        }
        else
        {
            $smsClickAction="javascript:showSearchSmsOverlay('scholarship','".$scholarship['id']."','".$url."');";
            $mailClickAction="javascript:showSearchMailOverlay('scholarship','".$scholarship['id']."','".$url."');";
            $saveProductAction="javascript:saveProduct('scholarship','".$scholarship['id']."');";
        }
    }


    $smsOverlayUrl="<img src=\"/public/images/smsIcon.gif\" align=\"absmiddle\" />&nbsp;<span style=\"margin-right:22px\"><a href=\"javascript:void(0);\" onClick=\"".$smsClickAction."\" title=\"SMS result\">SMS result</a></span>";
    $saveProductInfo='<img src="/public/images/listing_save.gif" align="absmiddle" />&nbsp;<span id="scholarship'.$scholarship['id'].'" style="margin-right:22px"><a href="javascript:void(0);" onclick="'.$saveProductAction.'" title="Save result">Save result</a></span>';

    $showImage="";
    if(trim(imageUrl) == "") {
        $showImage = 'none';
    } else {
        $showImage = 'inline';
    }
    $showImage = 'inline';
    if(imageUrl != ''){
        //imgParam =   getImgSize(imageUrl);
    }
    $imageUrl=getSmallImage($imageUrl);
    $imageFrame="";
    $marginLeft="";
    if(trim($imageUrl)!='')
    {
        //$imageFrame='<img src="'.$imageUrl.'" border="0" align="left" style="width:58px; padding-right:10px"/>';
		$imageFrame=<<<MARKUP
			
		<div style="width:58px; background-image:url($imageUrl); background-repeat:no-repeat; padding-right:10px;float:left;height:58px"></div>
MARKUP;
		$marginLeft='margin-left:68px';
    }
    $title = $scholarship['title'];
?>

    <div class="" id="listRow<?php echo $i;?>" onMouseOver="showRowPane(this.id);" onMouseOut="hideRowPane(this.id);" style="padding-top:5px; font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#000000; text-decoration:none; border-bottom:1px solid #E1D7D7">
                <div style="line-height:16px;" onMouseOver="showRowPane(this.parentNode.id);" onMouseOut="hideRowPane(this.parentNode.id);" >
					<div style="padding-bottom:3px;<?php echo $marginLeft ?>;"><a href="<?php echo $url; ?>" class="fontSize_13p" target="<?php echo $target;?>" title="<?php echo strip_tags($title);?>"><u><?php echo $title; ?></u></a>&nbsp;&nbsp;<?php echo $caption;?></div>
					<div style="<?php echo $marginLeft ?>;"><?php echo $sponsoredHtml;?></div>
                   <?php if($contentLine != '') { ?>
					<div style="<?php echo $marginLeft;?>" class="fontSize_12p"><?php echo $contentLine; ?></div>
                    <?php } ?>
                    <?php if($content != '') { ?>
					<div class="fontSize_12p" style="<?php echo $marginLeft ?>;"><?php echo $content;?></div> 					
                    <?php } ?>
					<div style="margin:5px 0px 1px 0px; height:18px"><div id="listRow<?php echo $i;?>Pane" class="hideSpan"><?php echo $saveProductInfo?><img src="/public/images/mail_icon.gif" align="absmiddle" />&nbsp;<span style="margin-right:22px"><a href="javascript:void(0);" onClick="<?php echo $mailClickAction;?>" title="E-mail result">E-mail result</a></span><?php echo $smsOverlayUrl;?><?php echo $answerNowUrl;?><span style="margin-right:22px"><?php echo $joinGrpUrl;?></span><span style="margin-right:22px"><?php echo $inviteFriendUrl;?></span>&nbsp;<?php echo $reportAbuseLine; ?></div></div>
				</div>
				<?php echo $grayLine; ?> 
    </div>

		<div class="lineSpace_15">&nbsp;</div>
<?php
        $i++;
    }
?>
