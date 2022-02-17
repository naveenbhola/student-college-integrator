<?php
	$galleryContainerHeight = '120px';
	if($ListingMode != 'view') {
		$galleryContainerHeight = '150px';
	}
?>
<style>
    #galleryContainer{
       /* border:1px solid #CCCCCC;*/
        position:relative;
        overflow:hidden;
        padding:1px;

        height:<?php echo $galleryContainerHeight; ?>;   /* Height of the images + 2 */
        /* CSS HACK */
        height: 104px;  /* IE 5.x - Added 2 pixels for border left and right */
        height/* */:/**/<?php echo $galleryContainerHeight; ?>;  /* Other browsers */
        height: /**/<?php echo $galleryContainerHeight; ?>;
	padding:10px 0;
    }
    #arrow_left{
        position:absolute;
        left:0px;
        z-index:10;
        background-color: #FFF;
        padding:1px;
    }
    #arrow_right{
        position:absolute;
        right:0px;
        z-index:10;
        background-color: #FFF;
        padding:1px;        
    }
    #theImages{
        position:absolute;
/*        height:150px;*/
        top:15px;
        left:40px;
        width:100000px;
        
    }
    #theImages #slideEnd{
        float:left;
    }
    #theImages img{
        /*float:left;*/
        padding:1px;
        filter: alpha(opacity=50);
        opacity: 0.5;
        cursor:pointer;
        border:0px;
    }

     #theImages div.imgDIV{
        float:left;
        width:130px;
        margin:0 20px;
        text-align:center;
    }
    #theImages div.imgDIVHide{
        display:none;
    }
     #theImages div.imgDIV div.brdUpper{
        padding:5px;
        width:117px;
        border:1px solid #d9d6d6;
    }
     #theImages div.imgDIV a{
        font-size:11px;
    }
    #waitMessage{
        display:none;
        position:absolute;
        left:200px;
        top:150px;
        background-color:#FFF;
        border:3px double #000;
        padding:4px;
        color:#555;
        font-size:0.9em;
        font-family:arial;  
    }
    
    #theImages .imageCaption{
        display:none;
    }
</style>

<?php
$this->load->view('common/overlay');
$this->load->view('common/disablePageLayer');
?>
<!--Start_Gallery-->
<a name="gallery"></a>
<div style="margin-top:10px">
    <div class="contentBT" style="padding:0 0 0 10px;line-height:25px">
    <div class="float_R txt_align_r" style="width:50px;padding-right:10px">&nbsp;<span class="<?php echo (($ListingMode == 'view') ?'editLinkHide':'editLinkShow'); ?>">[ <a href="<?php echo $mediaUrlphotos; ?>" class="fontSize_12p">Edit</a> ]</span></div>

        <div class="fontSize_14p"><b>Gallery:</b></div>
    </div>
    <div class="lineSpace_10">&nbsp;</div>
    <div style="margin-left:10px">
        <!--START_Gallery_IMG-->
        <div id="previewPane" style="align:center;display:none;" class="brd">
		<div class="float_L">
            <span id="waitMessage" style="align:center;">Please Wait...</span>
            <div id="largeImageCaption"></div>
		<table border="0" width="99%" style="margin-left:2px;">
			<tr>
				<td height="500" width="30">
					<a href="javascript:void(0);" style="cursor:pointer;background:url(/public/images/sImgLeft.gif) no-repeat left top;display:block;width:25px;height:30px;"  onclick="return imgoverlayTraverse('previous',this);"> &nbsp; </a>
				</td>
				<td valign="middle" align="center" id="previewPaneImageStore" width="670"></td>
				<td width="30">
					<a href="javascript:void(0);" style="cursor:pointer;background:url(/public/images/sImgRight.gif) no-repeat left top;display:block;width:25px;height:30px;"  onclick="return imgoverlayTraverse('next',this);"> &nbsp; </a>
				</td>
			</tr>
		</table>
		</div>
        </div>
        <div id="galleryContainer">
            <div id="arrow_left"><img src="/public/images/sLeft.gif" alt="Next"></div>
            <div id="arrow_right"><img src="/public/images/sRight.gif" alt="Previous"></div>
            <div id="theImages">
            <?php
            if (count($imgArr) > 0) {
                $i = 0;
                foreach ($imgArr as $imgDetail) {
			$nameExt = '';
			$nameArr = explode('.', $imgDetail['name']);
			if(count($nameArr) > 1) {
				$nameExt = $nameArr[count($nameArr) -1];
				unset($nameArr[count($nameArr) -1]);
			}
			$nameCaption = implode('.', $nameArr);
            ?>
                <div class="imgDIV" id='main_img_gallery_<?php echo $i;?>'>
                    <div class="brdUpper">
                        <div style="width:116px;height:78px">
                            <a href="javascript:void(0);" onclick="showPreviewOverlay('<?php echo $imgDetail['url'];?>','<?php echo $imgDetail['media_id']; ?>','&nbsp;'+ this.getAttribute('title'));return false" title="<?php echo $nameCaption; ?>"><img id="Image_main_img_gallery_name_<?php echo $i;?>" src="<?php echo str_replace('_s','_m',$imgDetail['thumburl']); ?>" alt="<?php echo $nameCaption; ?>"></a>
                        </div>
                        <div align="center">
                            <a href="javascript:void(0);" id='main_img_gallery_name_<?php echo $i;?>' onclick="showPreviewOverlay('<?php echo $imgDetail['url'];?>','<?php echo $imgDetail['media_id']; ?>',this.getAttribute('title'));return false" title="<?php echo $nameCaption; ?>" style="font-size:12px" ><?php echo (strlen($nameCaption) > 30 ? substr($nameCaption,0, 30) .'...'  : $nameCaption); ?></a>
                        </div>                        
                    </div>
                    <div class="<?php if ($ListingMode =='view') { echo "imgDIVHide"; } else { echo "";} ?>">
                            <span class="editLinkShow">[ <a href="javascript:void(0);" onclick="renameAction('MediaImgEditId<?php echo $imgDetail['media_id']; ?>','photos');" >Rename</a> ] | [ <a href="javascript:void(0);" onclick="deleteFile('institute','<?php echo $institute_id; ?>','<?php echo $imgDetail['media_id']; ?>','photos','<?php echo $i;?>','photos');">Delete</a> ]</span>
                    </div>
                    <div style="display:none;" id='error_msg_<?php echo $i;?>'></div>
                    <div style="display:none;" id="MediaImgEditId<?php echo $imgDetail['media_id']; ?>">
                            Rename Photo:<br />
                            <input type="text" id="MediaImgEditNewValue<?php echo $imgDetail['media_id']; ?>" style="width:105px" value="<?php echo $nameCaption; ?>" maxlength="50" minlength="5" /><br />
                            <div class="lineSpace_3">&nbsp;</div>
                            <input type="button" onclick="EditImgMedia('institute','<?php echo $institute_id; ?>','<?php echo $imgDetail['media_id']; ?>','photos','MediaImgEditNewValue<?php echo $imgDetail['media_id']; ?>','<?php echo $i;?>','MediaImgEditId<?php echo $imgDetail['media_id']; ?>','<?php echo $nameExt; ?>','photos');return false" value="" class="gUpdateBtn" />
                            <input type="button" onclick="CancleImgMedia('<?php echo $i; ?>','MediaImgEditId<?php echo $imgDetail['media_id']; ?>','photos');return false" value="" class="gCancelBtn" />
                    </div>
                </div>
            <?php
            ?>
                <script>
	    	var pic_<?php echo $imgDetail['media_id']; ?> = document.createElement('img');
	    	pic_<?php echo $imgDetail['media_id']; ?>.setAttribute('id', 'ImgDisplay_<?php echo $imgDetail['media_id']; ?>');
	    	pic_<?php echo $imgDetail['media_id']; ?>.setAttribute('mediaid','<?php echo $imgDetail['media_id']; ?>');
	    	document.getElementById('previewPaneImageStore').appendChild(pic_<?php echo $imgDetail['media_id']; ?>);
		pic_<?php echo $imgDetail['media_id']; ?>.style.display = 'none';

	    	imageGalleryLargeUrl[<?php echo $i; ?>] = '<?php echo $imgDetail['url']; ?>';
		imageGalleryLargeId[<?php echo $i; ?>] = '<?php echo $imgDetail['media_id']; ?>';
		imageGalleryLargeCaption[<?php echo $i; ?>] = '<?php echo '&nbsp;'. $nameCaption; ?>';
                </script>
            <?php
                $i++;    
                }
            ?>
                <script>
                     window.onload = initSlideShow;
                </script>  
            <?php
            }
            ?>
                <div id="slideEnd"></div>
            </div>
        </div>
	<script>
		if((170 * <?php echo count($imgArr); ?>) < (document.getElementById('galleryContainer').offsetWidth -70)) {
		
		var B=(function x(){})[-5]=='x'?'FF3':(function x(){})[-6]=='x'?'FF2':/a/[-1]=='a'?'FF':'\v'=='v'?'IE':/a/.__proto__=='//'?'Saf':/s/.test(/a/.toString)?'Chr':/^function \(/.test([].sort)?'Op':'Unknown';//To check the Browser
		document.getElementById('theImages').style.left = Math.floor(((document.getElementById('galleryContainer').offsetWidth - 70) /2 ) - ((170 * <?php echo count($imgArr); ?>)/2)) + 'px';
	}else {
		var reduceMargin = (B == 'IE') ? -10:15;
		document.getElementById('theImages').style.left = reduceMargin + 'px' ;
	}
	</script>
        <!--End_Gallery_IMG-->
        <div style="font-size:1px;line-height:1px;clear:both">&nbsp;</div>
    </div>
    <div style="font-size:1px;line-height:1px;clear:both">&nbsp;</div>
</div>
<!--End_Gallery-->
