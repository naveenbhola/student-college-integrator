                        <div class="wdh100">

                            <div class="nlt_head Fnt14 bld mb1">
                            <div class="h16" style="overflow:hidden">
                                <h3><div class="float_L">More Photos (<?php echo $lenPhoto; ?>)</div></h3>

                                <div class="float_R Fnt12 nbld">
                                <a id="prev" href="javascript: void(0)" style="color:#afafaf" onClick="prevSlide2()">&laquo; Prev</a>
                                <span class="sepClr">&nbsp; | &nbsp;</span>
                                <span><span id="spnPicStart">1</span>-<?php if ($lenVideo==0){$currentShown= 6;}else $currentShown=3;   if ($lenPhoto < $currentShown) {echo $lenPhoto;}else {?><span id="spnPicEnd"><?php echo $currentShown; ?></span><?php }?> of <?php echo $lenPhoto;?></span>
                                <span class="sepClr">&nbsp; | &nbsp;</span>

                                <a href="javascript: void(0)" id="next" onClick="trackEventByGA('LinkClick','LISTING_PHOTOSTAB_NEXT_PHOTO_LINK'); nextSlide2()">Next &raquo;</a>
                                </div>
                            </div>
                            </div>
                            <div class="lineSpace_10">&nbsp;</div>

                            <div class="wdh100">
                            <!-- Sliding image changes begin-->

                            <div style="width:475px">
                                <?php for( $i=0;$i<1;$i++){ ?>
                                     <div class="shik_box1" style="width:475px;height:140px"><?php ?>
                                       <div class="float_L nlt_video_thmNo" >
                                           <div id="picContainer" currentshowndiv="0" class="shik_box2" style="width:<?php echo ((round($lenPhoto/3)+1)*475);?>px; left: 0px;">
                                              <?php
                                                 foreach( $detailPageComponents as $mediaTypeIndex => $mediaType )
                                                 {
                                                    if( $mediaTypeIndex == 0 )
                                                    {
                                                        foreach( $mediaType as $mediaIndex => $media)
                                                        {
                                                           // error_log(print_r($media,true),3,"/home/naukri/Desktop/fyi.log");
                                                            if( $mediaIndex == 'value')
                                                            {
                                                                        $j=0;
                                                                        $container=0;
                                                                        foreach( $media as $key => $value)
                                                                        { if($j < $i) $container=1; else $container =0; if ($container ==0){?>


                                                                                <?php if( intval($j/3)%2 ==0 || $lenVideo != 0){ if ( $j <3){?>
                                                                                  <div class="shik_box3 nlt_video_thmNo" id="Container<?php echo $j;?>" divnumber="<?php echo $j;?>" style="width:130px">
                                                                                     <div class="nlt_vInnBx">
                                                                                        <div class="Fnt12  mb4" ><?php  $name0= str_replace('.jpg','',$value['name']); $name1=str_replace('.gif','',$name0); $name2= str_replace('.JPG','',$name1); $name3= str_replace('.jpeg','',$name2); $name4= str_replace('.JPEG','',$name3); $name5=str_replace('.png','',$name4); $name6=str_replace('.PNG','',$name5);$name6= str_replace('.GIF','',$name6);if( strlen($name6) >16){$nam= substr($name6,0,16); $nam=$nam.'...' ;}else {$nam= $name6;} echo $nam;?></div>
                                                                                        <div id="photoName<?php echo $j;?>" name="<?php if( strlen($name6) >50){$nam= substr($name6,0,50); $nam=$nam.'...' ;}else{$nam= $name6;}echo $nam;?>" ><a href="javascript: void(0)" ><img width="120" height="90" onClick="trackEventByGA('LinkClick','LISTING_PHOTOSTAB_PHOTO_CLICK'); showPic(<?php echo $j;?>,1)" title="<?php echo $value[url];?>" id="picture<?php echo $j;?>" src="<?php echo str_replace('_s','_m',$value['thumburl']); ?>" border="0" /></a></div>
                                                                                     </div>
                                                                                  </div><?php }if( $j >2){?>

                                                                                   <div class="shik_box3 nlt_video_thmNo" id="Container<?php echo $j;?>" divnumber="<?php echo $j;?>" style="width:130px">
                                                                                     <div class="nlt_vInnBx">
                                                                                        <div class="Fnt12  mb4" ><?php  $name0= str_replace('.jpg','',$value['name']); $name1=str_replace('.gif','',$name0); $name2= str_replace('.JPG','',$name1); $name3= str_replace('.jpeg','',$name2); $name4= str_replace('.JPEG','',$name3); $name5=str_replace('.png','',$name4); $name6=str_replace('.PNG','',$name5);$name6= str_replace('.GIF','',$name6);if( strlen($name6) >16){$nam= substr($name6,0,16); $nam=$nam.'...' ;}else { $nam= $name6;}echo $nam;?></div>
                                                                                        <div id="photoName<?php echo $j;?>" name="<?php if( strlen($name6) >50){$nam= substr($name6,0,50); $nam=$nam.'...' ;}else{$nam= $name6;}echo $nam;?>"><input type="hidden" name="<?php echo str_replace('_s','_m',$value['thumburl']); ?>" id="srcPicture<?php echo $j;?>" ><a href="javascript: void(0)" ><img width="48" height="48" onClick="trackEventByGA('LinkClick','LISTING_PHOTOSTAB_PHOTO_CLICK'); showPic(<?php echo $j;?>,1)" title="<?php echo $value[url];?>" id="picture<?php echo $j;?>" src="/public/images/loader.gif" border="0" /></a></div>
                                                                                     </div>
                                                                                  </div>
                                                                                    <?php }}?>







                                                                          <?php }  $j++;
                                                                        }
                                                            }
                                                       }
                                                   }
                                                }?>

                                               <div class="clear_B">&nbsp;</div>
                                        </div>
                                     </div>
                                  <?php ?>
                                </div> <?php }?>
                             </div>
                            <!-- Sliding image change ends -->
                          </div>
                          <?php if ($lenVideo==0){?>

                            <div class="lineSpace_10">&nbsp;</div>

                            <div class="wdh100">
                            <!-- Sliding image changes begin-->

                            <div style="width:475px">
                                <?php for( $i=0;$i<1;$i++){ ?>
                                     <div class="shik_box1" style="width:475px;height:140px"><?php ?>
                                       <div class="float_L nlt_video_thmNo" >
                                           <div id="picContainer2" currentshowndiv="0" class="shik_box2" style="width:<?php echo ((round($lenPhoto/3)+1)*475);?>px; left: 0px;">
                                              <?php
                                                 foreach( $detailPageComponents as $mediaTypeIndex => $mediaType )
                                                 {
                                                    if( $mediaTypeIndex == 0 )
                                                    {
                                                        foreach( $mediaType as $mediaIndex => $media)
                                                        {
                                                           // error_log(print_r($media,true),3,"/home/naukri/Desktop/fyi.log");
                                                            if( $mediaIndex == 'value')
                                                            {
                                                                        $j=0;
                                                                        $container=0;
                                                                        foreach( $media as $key => $value)
                                                                        { if($j < $i) $container=1; else $container =0; if ($container ==0){?>


                                                                                <?php if( intval($j/3)%2 ==1 ){        if ( $j <6){?>
                                                                                  <div class="shik_box3 nlt_video_thmNo" id="Container<?php echo $j;?>" divnumber="<?php echo $j;?>" style="width:130px">
                                                                                     <div class="nlt_vInnBx">
                                                                                        <div class="Fnt12  mb4" ><?php  $name0= str_replace('.jpg','',$value['name']); $name1=str_replace('.gif','',$name0); $name2= str_replace('.JPG','',$name1); $name3= str_replace('.jpeg','',$name2); $name4= str_replace('.JPEG','',$name3); $name5=str_replace('.png','',$name4); $name6=str_replace('.PNG','',$name5);$name6= str_replace('.GIF','',$name6);if( strlen($name6) >16){$nam= substr($name6,0,16); $nam=$nam.'...' ;}else {$nam= $name6;} echo $nam;?></div>
                                                                                        <div id="photoName<?php echo $j;?>" name="<?php if( strlen($name6) >50){$nam= substr($name6,0,50); $nam=$nam.'...' ;}else{$nam= $name6;}echo $nam;?>" ><a href="javascript: void(0)" ><img width="120" height="90" onClick="trackEventByGA('LinkClick','LISTING_PHOTOSTAB_PHOTO_CLICK'); showPic(<?php echo $j;?>,1)" title="<?php echo $value[url];?>" id="picture<?php echo $j;?>" src="<?php echo str_replace('_s','_m',$value['thumburl']); ?>" border="0" /></a></div>
                                                                                     </div>
                                                                                  </div><?php }if( $j >5){?>

                                                                                   <div class="shik_box3 nlt_video_thmNo" id="Container<?php echo $j;?>" divnumber="<?php echo $j;?>" style="width:130px">
                                                                                     <div class="nlt_vInnBx">
                                                                                        <div class="Fnt12 mb4" ><?php  $name0= str_replace('.jpg','',$value['name']); $name1=str_replace('.gif','',$name0); $name2= str_replace('.JPG','',$name1); $name3= str_replace('.jpeg','',$name2); $name4= str_replace('.JPEG','',$name3); $name5=str_replace('.png','',$name4); $name6=str_replace('.PNG','',$name5);$name6= str_replace('.GIF','',$name6);if( strlen($name6) >16){$nam= substr($name6,0,16); $nam=$nam.'...' ;}else { $nam= $name6;}echo $nam;?></div>
                                                                                        <div id="photoName<?php echo $j;?>" name="<?php if( strlen($name6) >50){$nam= substr($name6,0,50); $nam=$nam.'...' ;}else{$nam= $name6;}echo $nam;?>"><input type="hidden" name="<?php echo str_replace('_s','_m',$value['thumburl']); ?>" id="srcPicture<?php echo $j;?>" ><a href="javascript: void(0)" ><img width="48" height="48" onClick="trackEventByGA('LinkClick','LISTING_PHOTOSTAB_PHOTO_CLICK'); showPic(<?php echo $j;?>,1)" title="<?php echo $value[url];?>" id="picture<?php echo $j;?>" src="/public/images/loader.gif" border="0" /></a></div>
                                                                                     </div>
                                                                                  </div>
                                                                                    <?php }}?>







                                                                          <?php }  $j++;
                                                                        }
                                                            }
                                                       }
                                                   }
                                                }?>

                                               <div class="clear_B">&nbsp;</div>
                                        </div>
                                     </div>
                                  <?php ?>
                                </div> <?php }?>
                             </div>
                            <!-- Sliding image change ends -->
                          </div>







                         <?php }?>
                      </div>

<script>

    var next=0;
    var prev=0;
    var picLen= <?php echo $lenPhoto;?>;

    if (<?php echo $lenVideo;?> ==0)
    var currentShown=6;
    else
    var currentShown=3;
    if( picLen >= currentShown)
    {                document.getElementById("spnPicEnd").innerHTML = currentShown;
                     if( picLen == currentShown)
                     document.getElementById('next').style.color="#afafaf";
    }
    else
    document.getElementById('next').style.color="#afafaf";




    function nextSlide2()
    {
        var nextLink = document.getElementById('next');
        if( nextLink.style.color == "")
            {
                next++;
                prev--;
                var startIndex= currentShown*(next)+1;
                document.getElementById("spnPicStart").innerHTML = startIndex;
                var endIndex= startIndex+currentShown-1;
                if( endIndex > picLen)
                  endIndex= picLen;
                document.getElementById("spnPicEnd").innerHTML = endIndex;
                var slideDiv= document.getElementById('picContainer');
                var leftPosition = slideDiv.style.left;
                leftPosition= Number(leftPosition.substring(0,(leftPosition.length - 2)))
                slideListingMedia('slideRight','image');
                for( var j=1; j< 10;j++ ){
                    setTimeout(function () { slideListingMedia('slideRight','image') },80);
                }

                var prevLink = document.getElementById('prev');
                prevLink.style.color="";
                if( ((picLen/currentShown)-next) <= 1 )
                     nextLink.style.color="#afafaf";
            }

   }

     function prevSlide2()
    {

       var prevLink = document.getElementById('prev');
       if( prevLink.style.color == "")
            {

                prev++;
                next--;
                var startIndex= currentShown*(next)+1;
                document.getElementById("spnPicStart").innerHTML = startIndex;
                var endIndex= startIndex+currentShown-1;
                if( endIndex > picLen)
                  endIndex= picLen;
                document.getElementById("spnPicEnd").innerHTML = endIndex;
                var slideDiv= document.getElementById('picContainer');
                var leftPosition = slideDiv.style.left;
                leftPosition= Number(leftPosition.substring(0,(leftPosition.length - 2)));
                slideListingMedia('slideLeft','image');
                for( var j=1; j< 10; j++){setTimeout(function () { slideListingMedia('slideLeft','image') },80);}

                var nextLink = document.getElementById('next');
                nextLink.style.color="";
                if( next ==0)
                     prevLink.style.color="#afafaf";
            }

    }




</script>
