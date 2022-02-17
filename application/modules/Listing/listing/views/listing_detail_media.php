<?php

$tagsDescription = get_listing_seo_tags($details['title'],$details['locations']['0']['locality'],$details['courseDetails']['0']['title'],$details['locations']['0']['city_name'],$details['locations']['0']['country_name'],'PhotoTab',$details['abbreviation']);
$headerComponents = array('js'=>array('multipleapply','cityList','listing_detail','listingDetail','listingMedia','facebook','customCityList'),
'css'	=>	array('raised_all','mainStyle','header'),
    'jsFooter' =>array('alerts','myShiksha','common','lazyload'),           
//'css'=>array('modal-message','raised_all','mainStyle'),
'css'=>array('listing','online-styles'),
						'product'=>'categoryHeader',
    'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
                                'taburl' =>  site_url(),
								'bannerProperties' => array('pageId'=>'CATEGORY', 'pageZone'=>$categoryData['page'] .'_TOP', 'shikshaCriteria' => $criteriaArray),'tabName'	=>	'Event Calendar',
								'title'	=>	$tagsDescription['Title'],
								'metaKeywords'	=>$tagsDescription['Keywords'],
								'metaDescription' => $tagsDescription['Description']
);
if($instituteType == 2){
    $headerComponents['product'] = "testprep";
}
?>

<?php $this->load->view('common/header', $headerComponents);
echo jsb9recordServerTime('SHIKSHA_LISTING_DETAIL_MEDIA_PAGE',1);
?>
<?php $this->load->view('listing/widgets/breadcrumb');?>

<!-- SITE HEADER END -->

<!-- Code start by Ankur for HTML caching -->
<script>
var currentPageName = 'LISTING_DETAIL_MEDIA_TAB';
var loggedUserEMailTemp = '<?php echo $loggedUserEMail;?>';
if(loggedUserEMailTemp=='')
flagForSignedUser = false;
else
flagForSignedUser = true;

</script>

<?php
if(file_exists($overviewFile) && $isCached == 'true'){
    echo file_get_contents($overviewFile);
    ?>
    <script>
    //Code to show the Shiksha Analytics by JS whenever the detail page is displayed through HTML
    var sA = '<?php echo $countResponseFormDetails;?>';
    var sVC = '<?php echo $details['viewCount'] ?>';
    var sSC = '<?php echo $details['summaryCount'] ?>';
    fillAnalyticsData(sA,sVC,sSC);
    </script>
    <?php
}
else
{
  ob_start(); 

?>
<!-- Code End by Ankur for HTML caching -->


<div class="wrapperFxd">
        <!--Start_listing_detail_head-->
 	<?php $this->load->view('listing/widgets/header');?>
        <!--End_listing_detail_head-->


        <!--Start_Sub_Navigation-->
                <?php $this->load->view('listing/widgets/navigation');?>
        <!--End_Sub_Navigation-->

        <!--Start_Mid_Panel-->
        <div class="wdh100 mt10">

        	<div class="mlr10">
                <div class="wdh100">
                    <div class="float_L" style="width:465px">
                    	<div class="mlr10">
                            <div style="width:426px">
                            	<div class="nlt_head Fnt14 bld mb1">
                                	<div class="h16" style="overflow:hidden">
                                		<div class="float_L" id="previewMediaName"></div>
                                        <div class="float_R">
                                            <a href="javascript: void(0)" id="mainPrev" onClick="onPrev()" class="float_L sprt_nlt_btn nlt_btn6" style="margin-right:5px">&nbsp;</a>
                                            <a href="javascript: void(0)" id="mainNext" onClick="onNext()" class="float_L sprt_nlt_btn nlt_btn7">&nbsp;</a>
                                        </div>
                                    </div>
                                </div>
                                <div id="preview">
                                    <div <?php if ($lenVideo >0){?> id="vid"<?php }else {?> id="pic"<?php }?> >
                                        <div id="previewPic" style="display: none; width:425px; height:385px;text-align: center; overflow:hidden; position:relative; line-height:385px;">
                                        <img id="nlt_mainImg" name=""  src="" width="425" height="385" align="" style="margin-top: 30%;" border="0"/>
                                        <div style="display:none"><img id="nlt_mainImg_dummy"  src="" /></div>
                                        </div>
                                        <div>
                                            <div id="previewVid" style="display: none">
                                              <!--  <object id="obj" name="" width="425" height="385"><param id="param1" name="movie" value="bhuvi"></param><param id="param2" name="allowFullScreen" value="true"></param><param id="param3" name="allowscriptaccess" value="always"></param><embed name="" id="embed" src="" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="385"></embed></object>-->
                                            </div>
                                        </div>


                                    </div>
                                </div>
                          <!--      <div class="nlt_vMsg">JK Business School (JKBS) is the business education service provider founded to groom and develop efficient managers and business leaders. JK Business School (JKBS) is the business education service provider founded to groom and develop efficient managers and business leaders </div> -->
                            </div>

                        </div>
                    </div>
                    <div class="float_R" style="width:490px">


                        <!--Start_Videos-->
                        <?php if( $lenVideo > 0){$this->load->view('listing/widgets/listing_videos');}?>
                        <!--End_Videos-->


                        <?php if( $lenVideo > 0){?>
                        <div class="lineSpace_20">&nbsp;</div>
                        <?php }?>


                        <!--Start_Photos-->
                        <?php if( $lenPhoto > 0) {$this->load->view('listing/widgets/listing_photos');}?>
                        <!--End_Photos-->

                    </div>
                    <div class="clear_B">&nbsp;</div>
                </div>
                <div class="lineSpace_20">&nbsp;</div>
                <div class="lineSpace_20">&nbsp;</div>
                <div class="lineSpace_20">&nbsp;</div>
                <div class="wdh100">

                    <!-- I am Interested bottom widget begins -->
                    <div style="width:677px">
                            <?php $this->load->view('listing/widgets/i_am_interested_bottom');  ?>
                            <?php $this->load->view('listing/widgets/connectInstituteBottomWidget');  ?>
                    </div>
                    <!-- I am Interested bottom widget Ends -->
                </div>
                <div class="lineSpace_30">&nbsp;</div>

                <!--Start_Google_Ads-->
                <div >
                    <?php
                    if ( $ListingMode == 'view' ) {
                    if(!isset($cmsData)){
                            $bannerProperties = array('pageId'=>'LISTINGS', 'pageZone'=>'FOOTER');
                            $this->load->view('common/banner',$bannerProperties);
                                        }
                                                    }
                    ?>
                </div>
                <!--Start_Google_Ads-->

            </div>
        </div>



        <!--End_Mid_Panel-->
    </div>
    <!--End_pagewrapper-->

<script>
var currentVideoIndex=0;
var currentPicIndex=0;

var previewDivChild= document.getElementById("preview").getElementsByTagName('div');
var flag= previewDivChild[0].id;

var firstIndex=0;


if(getCookie('listing_media_widget_vid') != '')
{
    flag= 'vid';
    firstIndex= getCookie('listing_media_widget_vid');
    //deleteCookie('listing_media_widget_vid');
    setCookie('listing_media_widget_vid','',0);
}
if(getCookie('listing_media_widget_pic') != '' )
{
    flag= 'pic';
    firstIndex=getCookie('listing_media_widget_pic');
    //deleteCookie('listing_media_widget_pic');
    setCookie('listing_media_widget_pic','',0);
}


if( flag == 'vid')
{

    document.getElementById("previewVid").style.display = "block";
    showVid(firstIndex,0);}

if ( flag == 'pic')
{
    document.getElementById("previewPic").style.display = "block";
    showPic(firstIndex,0);}

function onPrev()
{
    var pDivChild= document.getElementById("preview").getElementsByTagName('div');
    var fg= pDivChild[0].id;
    if(fg == 'vid')
    {    var index= document.getElementById("embed").name;
         index= index.substring(5,(index.length+1));
         if( index > 0)
         showVid(--index,0);
    }
    if(fg == 'pic')
    {
         var index= document.getElementById("nlt_mainImg").name;
         index= index.substring(7,(index.length+1));
         if( index > 0)
         showPic(--index,0);


    }
}

function onNext()
{
    var pDivChild= document.getElementById("preview").getElementsByTagName('div');
    var fg= pDivChild[0].id;
    if(fg == 'vid')
    {
            var index= document.getElementById("embed").name;
            index= index.substring(5,(index.length+1));
            if( index < <?php echo $lenVideo;?>-1)
            showVid(++index,0);
    }
    if(fg == 'pic')
    {
            var index= document.getElementById("nlt_mainImg").name;
            index= index.substring(7,(index.length+1));
            if( index < <?php echo $lenPhoto;?>-1)
            showPic(++index,0);



    }
}

function showVid(ind, type){



                var index= ind;
                var previewDivChild= document.getElementById("preview").getElementsByTagName('div');
                previewDivChild[0].id='vid';
                document.getElementById("previewPic").style.display = "none";
                document.getElementById("previewVid").style.display = "block";
                document.getElementById("infoContainer"+currentVideoIndex).className="shik_box3 nlt_video_thmNo";
                if((<?php echo $lenPhoto;?> > 0))
                document.getElementById("Container"+currentPicIndex).className="shik_box3 nlt_video_thmNo";
                var temp = currentVideoIndex;
                currentVideoIndex= index;
                document.getElementById("previewMediaName").innerHTML=document.getElementById("videoName"+index).getAttribute('name');
                document.getElementById("infoContainer"+index).className="shik_box3 nlt_video_thmBrd";
                var url = document.getElementById("video"+index).name;
                url= url+"&autoplay=1";
                var innerContent='<div id="embed" name="video'+index+'" ><object id="obj" name="" width="425" height="385"><param id="param1" name="movie" value="'+url+'"></param><param id="param2" name="allowFullScreen" value="true"></param><param id="param3" name="allowscriptaccess" value="always"></param><embed wmode="transparent" src="'+url+'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="385"></embed></object></div>'
                document.getElementById('previewVid').innerHTML = innerContent;
                document.getElementById("embed").name="video"+index;

                if ( type ==0)
                {
                    if ( (index)%3 == 0 && temp < index && index/3 != nextV)
                    {
                        if(nextV < index/3)
                        {
                            while( nextV != index/3)
                                              nextSlide();

                        }
                        else
                        {
                            while( nextV != index/3)
                                              prevSlide();
                        }
                    }

                    if ( (index+1)%3 == 0 && temp > index && Math.floor(index/3) != -prevV)
                    {

                        if( -prevV > Math.floor(index/3))
                        {
                                while(-prevV != Math.floor(index/3))
                                                    prevSlide();
                        }
                        else
                        {
                                while(-prevV != Math.floor(index/3))
                                                    nextSlide();
                        }

                    }


                }


}

function showPic(ind, type){


                var index= ind;
                var previewDivChild= document.getElementById("preview").getElementsByTagName('div');
                previewDivChild[0].id='pic';
                document.getElementById("previewVid").style.display = "none";
                document.getElementById("previewPic").style.display = "block";
                document.getElementById("nlt_mainImg").src= "/public/images/loader.gif";
                document.getElementById("nlt_mainImg").width=48;
                document.getElementById("nlt_mainImg").height=48;
                document.getElementById("nlt_mainImg").style.marginTop="30%";
                document.getElementById("Container"+currentPicIndex).className="shik_box3 nlt_video_thmNo";
                if((<?php echo $lenVideo;?> > 0))
                document.getElementById("infoContainer"+currentVideoIndex).className="shik_box3 nlt_video_thmNo";
                var temp = currentPicIndex;
                currentPicIndex= index;
                document.getElementById("previewMediaName").innerHTML=document.getElementById("photoName"+index).getAttribute('name');
                document.getElementById("Container"+index).className="shik_box3 nlt_video_thmBrd";
                document.getElementById("nlt_mainImg").name="picture"+index;
                var src=document.getElementById("picture"+index).title;

                //document.getElementById("nlt_mainImg_dummy").src= src;
                var dummyObj = new Image();
                dummyObj.onload=function(){

                            document.getElementById("nlt_mainImg").src= src;
                            document.getElementById("nlt_mainImg").width=425;
                            document.getElementById("nlt_mainImg").height=385;
                            document.getElementById("nlt_mainImg").style.marginTop="0";
                }
                dummyObj.src= src;

                if ( type == 0)
                {
                    if ( (index)%currentShown == 0 && temp < index && index/currentShown != next)
                    {
                        if(next < index/currentShown)
                        {
                            while(next != index/currentShown)
                                            nextSlide2();

                        }
                        else
                        {
                            while( next != index/currentShown)
                                            prevSlide2();
                        }

                    }

                    if ( (index+1)%currentShown == 0 && temp > index && Math.floor(index/currentShown) != -prev)
                    {

                        if( -prev > Math.floor(index/currentShown))
                        {
                            while( -prev != Math.floor(index/currentShown))
                                               prevSlide2();

                        }
                        else
                        {
                            while( -prev != Math.floor(index/currentShown))
                                                nextSlide2();
                        }

                    }

                }
}

function slideListingMedia(slide,media)
{

        var khisko= 47;
        var slideDirection= slide;
        var mediaType= media;
        if(mediaType=='image')
            var slideD= document.getElementById('picContainer');
        else
            var slideD= document.getElementById('0vidContainer');
        var leftPos = slideD.style.left;
        leftPos= Number(leftPos.substring(0,(leftPos.length - 2)));
        if(slideDirection == 'slideLeft')
            slideD.style.left=(leftPos+khisko)+'px';
        else
            slideD.style.left=(leftPos-khisko)+'px';


        if (<?php echo $lenVideo ;?> ==0)
        {
            slideD= document.getElementById('picContainer2');
            leftPos = slideD.style.left;
            leftPos= Number(leftPos.substring(0,(leftPos.length - 2)));
            if(slideDirection == 'slideLeft')
                slideD.style.left=(leftPos+khisko)+'px';
            else
                slideD.style.left=(leftPos-khisko)+'px';
        }

}


function lazyLoadMedia(){

       var index = 3;
       var vLen= <?php echo $lenVideo;?>;
       var pLen= <?php echo $lenPhoto;?>;
       loadMedia(pLen, vLen, index);
}


function loadMedia(plen, vlen, ind)
{

        var pl= plen;
        var vl= vlen;
        var index= ind;

        if ( pl > index && vl > index)
        {

              var flag =0;

                if( pl > index+3)
                {
                    loadPictures(index,index+2);
                    flag= 1;
                }
                else
                    loadPictures(index,pl-1);


                if( vl > index+3)
                {
                    loadVideos(index,index+2);
                    flag= 1;
                }
                else
                loadVideos(index,vl-1);


                if(flag == 1)
                loadMedia(pl,vl,index+3);

        }

        else if ( pl > index)
                loadPictures( index, pl-1);

        else if ( vl > index)
                loadVideos(index, vl-1);

        for(var z=3; z < vidLen; z++)
        {

            var iconObj=document.getElementById('play_icon_video'+z);
            iconObj.src="/public/images/plav.png";
        }
}


function loadPictures(lower, upper)
{

    var l= lower;
    var u = upper;

    for( var i =l; i<= u; i++)
    {

        if (currentShown == 6 && i <6)
        continue;

        var srcObj = document.getElementById('srcPicture'+i);
        var source = srcObj.name;
        var imgObj = document.getElementById('picture'+i);
        imgObj.src= source;
        imgObj.onload = function() {

                         for(var z=3; z < picLen;z++)
                         {
                             imgObj=document.getElementById('picture'+z);
                             if( imgObj.complete)
                             {
                                imgObj.height= 90;
                                imgObj.width= 120;
                             }
                         }

                    }



    }


}

function loadVideos( lower,  upper)
{
    var l= lower;
    var u = upper;
    for( var i =l; i<= u; i++)
    {
        var srcObj = document.getElementById('srcVideo'+i);
        var source = srcObj.name;
        var imgObj = document.getElementById('video'+i);
        imgObj.src= source;
        imgObj.onload = function() {
                         for(var z=3; z < vidLen;z++)
                         {
                             imgObj=document.getElementById('video'+z);

                             if( imgObj.complete)
                             {
                                imgObj.height= 90;
                                imgObj.width= 120;

                             }
                         }
                    }

    }
}

</script>

<!-- Code start by Ankur for HTML caching -->
<?php
  $pageContent = ob_get_contents();
  ob_end_clean();
  echo $pageContent;
  //In case the HTML caching on On and also the Listing type is not a link
  if( strpos($listing_type,'http') === false  && $HTMLCaching=='true'){
    $fp=fopen($overviewFile,'w+');
    flock( $fp, LOCK_EX ); // exclusive lock
    fputs($fp,$pageContent);
    flock( $fp, LOCK_UN ); // release the lock
    fclose($fp);

    ?>
        <script>
                var data = "";
                var url = '/listing/Listing/callToCopyFileToServers/<?php echo base64_encode($overviewFile);?>';
                new Ajax.Request (url,{ method:'post', parameters: (data), onSuccess:function (request) {}});
        </script>
     <?php
  }
}
?>
<?php
$params = array('instituteId'=>$details['institute_id'],'instituteName'=>$details['title'],'type'=>'institute','locality'=>$details['location']['0']['locality'],'city'=>$details['location']['0']['city_name'],'courseName'=>$details['courseDetails']['0']['title'],'courseId'=>$details['courseDetails']['0']['course_id']);
$overviewTabUrl = getSeoUrl($details['institute_id'], 'institute', $details['title'], array('location' => array($details['locations']['0']['locality'], $details['locations']['0']['city_name'])));
$askNAnswerTabUrl = listing_detail_ask_answer_url($params);
$mediaTabUrl = listing_detail_media_url($params);
$alumniTabUrl = listing_detail_alumni_speak_url($params);
$courseTabUrl = listing_detail_course_url($params);
?>
<script>
//Code to show the I am interested widgets filled with User's data in case the User is logged in
var loggedUserName = '<?php echo $loggedUserName; ?>';
var loggedUserMobile = '<?php echo $loggedUserMobile; ?>';
var loggedUserEMail = '<?php echo $loggedUserEMail;?>';
fillUserData(loggedUserName,loggedUserMobile,loggedUserEMail);
var saved = "'<?php echo $saved; ?>'";
var source = "'<?php echo $source."_TOP_SAVEINFO"?>'";
var quickClickAction = "javascript:window.location = '/user/Userregistration/index/<?php echo base64_encode($thisUrl);?>/1'";
var listing_type = "'<?php echo $identifier; ?>'";
var type_id = '<?php echo $type_id; ?>';
var div_id = "<?php echo $identifier.$type_id;?>";
fillSaveInfo(saved,loggedUserName,source,quickClickAction,listing_type,type_id,div_id);
</script>
<?php
//Create the HTML files for other tabs only when the HTML file for this tab is created
if( !file_exists($overviewFile) || $isCached != 'true' ){
?>
<script>
window.onload = function(){
lazyLoadMedia();
makeHTMLFiles('media','<?php echo $overviewTabUrl;?>','<?php echo $mediaTabUrl;?>','<?php echo $alumniTabUrl;?>','<?php echo $courseTabUrl;?>');
}
</script>
<?php } else{ ?>
<script>
window.onload = function(){
lazyLoadMedia();
}
</script>
<?php } ?>


<!-- Code End by Ankur for HTML caching -->
<!-- beacon code start-->
<img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 >
<script>
       var img = document.getElementById('beacon_img');
       var randNum = Math.floor(Math.random()*Math.pow(10,16));
       img.src = '<?php echo BEACON_URL; ?>/'+randNum+'/0010004/<?php echo $type_id; ?>+<?php echo $identifier; ?>';
       //fillProfaneWordsBag() ;
</script>
<?php $this->load->view('common/footerNew');?>

<!-- Code added to add tracking when the user is shown the FConnect button on Listing detail page -->
<script>
if(loggedUserName==''){
trackEventByGA('LinkClick','FCONNECT_BUTTON_SHOWN_ON_LISTING_DETAIL');
}else{
trackEventByGA('LinkClick','FCONNECT_BUTTON_NOT_SHOWN_ON_LISTING_DETAIL');
}
</script>
