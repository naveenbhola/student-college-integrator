<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$titleText = "Take $blog_title mock tests online";
        $criteriaArray = array(
                'category' => '',
                'country' => 2,
                'keyword'=>$acronym."_test"
                );
$headerComponents = array('js'=>array('common','lazyload','multipleapply','category','user'),
'jsFooter' =>array('ana_common'),
                        'css'=>array('modal-message','raised_all','mainStyle'),
						'product'=>'testprep',
                                'taburl' =>  site_url(),
								'bannerProperties' => array('pageId'=>'ONLINE_TEST', 'pageZone'=>'TOP', 'shikshaCriteria' => $criteriaArray),'tabName'	=>	'Event Calendar',
								'title'	=>	$titleText,
								'metaKeywords'	=>$metaKeywordText,
								'metaDescription' => $metaDescriptionText
);
?>

<?php $this->load->view('common/header', $headerComponents);?>
<?php
if (is_array($validateuser) && is_array($validateuser[0]) && (isset($validateuser[0]['userid'])) && !empty($validateuser[0]['userid'])) {
    $userEmail = substr($validateuser[0]['cookiestr'], 0, strpos($validateuser[0]['cookiestr'], '|'));}
    //echo "Email is ".$userEmail;
?>
<?php
        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, "www.topcatcoaching.com/user/Userregistration/submit");

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

curl_setopt($ch, CURLOPT_POSTFIELDS,  "email=".md5($userEmail)."@shikshatest.com&password=shikshatestprep&courseInterest=1&source=tc&google=0&termsandconditions=accepted&display_name=abhinav&contact_number=9899998899&city=Delhi");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);

        // $output contains the output string
        $output = curl_exec($ch);
	//if(substr_count($output, "Duplicate entry") > 0) echo "Already registered!!!!!!";
	//else echo "Now the user got registered";
        // close curl resource to free up system resources
        curl_close($ch);
?>
<div>&nbsp;&nbsp;&nbsp;<a href="<?php echo $url ?>">&#171;&#171; Go back to view list of institutes</a></div>
<br/><br/>
<?php if($bannerurl['bannerurl'] != '' && $bannerurl != NULL && $bannerurl != -1) { ?>
<div style="width:238px;height:108px;position:relative" class="float_L">

<div id="imagespace">
<div id = "floatad1" style = "position:absolute;z-index:1001;overflow:hidden;margin-left:10px">
    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="760" height="100" id = "flashcontent">
        <param name="movie" value="<?php echo $bannerurl;?>"/>
        <param name="quality" value="high" /><param name="allowScriptAccess" value="always" />
        <param name="wmode" value="transparent" />
        <embed src="<?php echo $bannerurl;?>" width="760" height="100" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" allowScriptAccess = "always" wmode = "transparent" name = "flashcontent1" id = "flashcontent1"></embed>
    </object>
</div>
</div>
</div>
                                       <?php } 
                                           ?>
                                           <br/><br/>


                                           <div id="tframe_div" style="">

                                               <iframe src="/shiksha/load_test/<?php echo $acronym?>" id="tframe" width="960px" height="700px" frameborder="0" style="margin-left:14px;" scrolling="">

</iframe>
                                               </div>

                                           <div style="padding: 6px; text-align: right;">
<?php
                                switch($acronym){
                                    case "Engineering" :
                                        $btnimage = 'tc_2.gif';break;
                                    case "Medical" :
                                        $btnimage = 'tc_5.gif';break;
                                    case "Foreign" :
                                        $btnimage = 'tc_3.gif';break;
                                    case "MBA" :
                                        $btnimage = 'tc_1.gif';break;
}
?>
                                               <span style="margin-top: 2px;font-size: 14px;">Online tests powered by :</span> <img align="absmiddle" src="/public/images/<?php echo $btnimage?>" alt="topcoaching.com" />
                                           </div>
<?php $this->load->view('common/footerNew');?>

