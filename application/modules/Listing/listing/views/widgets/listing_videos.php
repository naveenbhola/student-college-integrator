                        <div class="wdh100">
                            <div class="nlt_head Fnt14 bld mb1">
                            <div class="h16" style="overflow:hidden">
                               <h2><div class="float_L">More Videos (<?php echo $lenVideo; ?>)</div></h2>

                                <div class="float_R Fnt12 nbld">
                                <a id="prevVid" href="javascript: void(0)" style="color:#afafaf" onClick="prevSlide()">&laquo; Prev</a>
                                <span class="sepClr">&nbsp; | &nbsp;</span>
                                <span><span id="spnVidStart">1</span>-<?php if ($lenVideo < 3) {echo $lenVideo;}else {?><span id="spnVidEnd">3</span><?php }?> of <?php echo $lenVideo;?></span>
                                <span class="sepClr">&nbsp; | &nbsp;</span>

                                <a href="javascript: void(0)" id="nextVid" onClick="trackEventByGA('LinkClick','LISTING_PHOTOSTAB_NEXT_VIDEO_LINK'); nextSlide()"  >Next &raquo;</a>
                                </div>
                            </div>
                            </div>
                            <div class="lineSpace_10">&nbsp;</div>
                            <div class="wdh100">

                            <!-- Sliding video changes begin-->

                            <div style="width:475px">
                                <?php for( $i=0;$i<1;$i++){?>

                                     <div class="shik_box1" style="width:475px;height:140px">
                                       <div class="float_L nlt_video_thmNo">
                                         <div id="<?php echo $i ?>vidContainer" currentshowndiv="0" class="shik_box2" style="width:<?php echo ((round($lenVideo/3)+1)*475);?>px; left: 0px;">
                                              <?php
                                                 foreach( $detailPageComponents as $mediaTypeIndex => $mediaType )
                                                 {

                                                    if ( $lenPhoto > 0)
                                                    $lim=1;
                                                    else
                                                    $lim = 0;
                                                    if( $mediaTypeIndex == $lim )
                                                    {
                                                        foreach( $mediaType as $mediaIndex => $media)
                                                        {
                                                           //error_log(print_r($media,true),3,"/home/naukri/Desktop/mon.log");
                                                           if( $mediaIndex == 'value')
                                                            {
                                                                        $j=0;
                                                                        $container=0;
                                                                        foreach( $media as $key => $value)
                                                                        { if($j < $i) $container=1; else $container =0; if ($container ==0){?>

                                                                                <?php if ( $j <3){?>
                                                                                  <div class="shik_box3 nlt_video_thmNo" id="infoContainer<?php echo $j;?>" divnumber="<?php echo $j;?>" style="width:130px">
                                                                                     <div class="nlt_vInnBx">
                                                                                        <div class="Fnt12  mb4" ><?php  $name0= str_replace('.jpg','',$value['name']); $name1=str_replace('.gif','',$name0); $name2= str_replace('.JPG','',$name1); $name3= str_replace('.jpeg','',$name2); $name4= str_replace('.JPEG','',$name3); $name5=str_replace('.png','',$name4); $name6=str_replace('.PNG','',$name5);$name6= str_replace('.GIF','',$name6);if( strlen($name6) >16){$nam= substr($name6,0,16); $nam=$nam.'...' ;}else {$nam= $name6;} echo $nam;?></div>
                                                                                        <div style="position:relative" name="<?php if( strlen($name6) >50){$nam= substr($name6,0,50); $nam=$nam.'...' ;}else{$nam= $name6;}echo $nam;?>" id="videoName<?php echo $j;?>"><img width='120' height='90' style=" z-index:1"  name="<?php echo $value['url']; ?>&amp;hl=en&amp;fs=1&amp;rel=0"  id="video<?php echo $j;?>" src="<?php if($value['thumburl']== ''){ $sString= substr($value['url'],25);  echo "http://i1.ytimg.com/vi/".$sString."/2.jpg";}else {echo str_replace('_s','_m',$value['thumburl']); }?>" border="0" /><a href="javascript: void(0)" id="linkVideo<?php echo $j;?> " name="<?php echo $value['name']?>" href="#"><img border="0" onCLick="trackEventByGA('LinkClick','LISTING_PHOTOSTAB_VIDEO_CLICK'); showVid(<?php echo $j;?>,1)" style="position:absolute ; z-index:2; left:38% ; top:40%" src="/public/images/plav.png"/></a></div>
                                                                                     </div>
                                                                                  </div><?php }if( $j >2){?>


                                                                                  <div class="shik_box3 nlt_video_thmNo" id="infoContainer<?php echo $j;?>" divnumber="<?php echo $j;?>" style="width:130px">
                                                                                     <div class="nlt_vInnBx">
                                                                                        <div class="Fnt12 mb4"><?php  $name0= str_replace('.jpg','',$value['name']); $name1=str_replace('.gif','',$name0); $name2= str_replace('.JPG','',$name1); $name3= str_replace('.jpeg','',$name2); $name4= str_replace('.JPEG','',$name3); $name5=str_replace('.png','',$name4); $name6=str_replace('.PNG','',$name5);$name6= str_replace('.GIF','',$name6);if( strlen($name6) >16){$nam= substr($name6,0,16); $nam=$nam.'...' ;}else {$nam= $name6;}echo $nam;?></div>
                                                                                        <div style="position:relative" name="<?php if( strlen($name6) >50){$nam= substr($name6,0,50); $nam=$nam.'...' ;}else{$nam= $name6;}echo $nam;?>" id="videoName<?php echo $j;?>"><input type="hidden" name="<?php if($value['thumburl']== ''){ $sString= substr($value['url'],25);  echo "http://i1.ytimg.com/vi/".$sString."/2.jpg";}else {echo str_replace('_s','_m',$value['thumburl']); }?>" id="srcVideo<?php echo $j;?>" ><img  style=" z-index:1" name="<?php echo $value['url']; ?>&amp;hl=en&amp;fs=1&amp;rel=0"  id="video<?php echo $j;?>" src="/public/images/loader.gif" border="0" /><a href="javascript: void(0)" id="linkVideo<?php echo $j;?> " name="<?php echo $value['name']?>" href="#"><img border="0" onClick="trackEventByGA('LinkClick','LISTING_PHOTOSTAB_VIDEO_CLICK'); showVid(<?php echo $j;?>,1)" id="play_icon_video<?php echo $j;?>" style="position:absolute ; z-index:2; left:38% ; top:40%" src=""/></a></div>
                                                                                     </div>
                                                                                  </div>
                                                                                    <?php }?>






                                                                          <?php }  $j++;
                                                                        }
                                                            }
                                                       }
                                                   }
                                                }?>

                                               <div class="clear_B">&nbsp;</div>
                                        </div>
                                  </div>

                                </div> <?php }?>
                          </div>
                            <!-- Sliding video change ends -->

                         </div>
                     </div>

<script>


    var nextV=0;
    var prevV=0;
    var vidLen= <?php echo $lenVideo;?>;

    if( vidLen >= 3)
    {                  document.getElementById("spnVidEnd").innerHTML = '3';
                       if( vidLen == 3)
                       document.getElementById('nextVid').style.color="#afafaf";
    }
    else
    document.getElementById('nextVid').style.color="#afafaf";


    function nextSlide()
    {
        var nextLink = document.getElementById('nextVid');
        if( nextLink.style.color == "")
            {

                nextV++;
                prevV--;
                var startIndex= 3*(nextV)+1;
                document.getElementById("spnVidStart").innerHTML = startIndex;
                var endIndex= startIndex+2;
                if( endIndex > vidLen)
                  endIndex= vidLen;
                document.getElementById("spnVidEnd").innerHTML = endIndex;
                var slideDiv= document.getElementById('0vidContainer');
                var leftPosition = slideDiv.style.left;
                leftPosition= Number(leftPosition.substring(0,(leftPosition.length - 2)))
                slideListingMedia('slideRight','video');
                for( var j=1; j< 10; j++){setTimeout(function () { slideListingMedia('slideRight','video') },80);}
                var prevLink = document.getElementById('prevVid');
                prevLink.style.color="";
                if( ((vidLen/3)-nextV) <= 1 )
                     nextLink.style.color="#afafaf";
            }

   }

     function prevSlide()
    {
       var prevLink = document.getElementById('prevVid');
       if( prevLink.style.color == "")
            {

                prevV++;
                nextV--;
                var startIndex= 3*(nextV)+1;
                document.getElementById("spnVidStart").innerHTML = startIndex;
                var endIndex= startIndex+2;
                if( endIndex > vidLen)
                  endIndex= vidLen;
                document.getElementById("spnVidEnd").innerHTML = endIndex;
                var slideDiv= document.getElementById('0vidContainer');
                var leftPosition = slideDiv.style.left;
                leftPosition= Number(leftPosition.substring(0,(leftPosition.length - 2)))
                slideListingMedia('slideLeft','video');
                for( var j=1; j< 10; j++){setTimeout(function () { slideListingMedia('slideLeft','video') },80);}
                var nextLink = document.getElementById('nextVid');
                nextLink.style.color="";
                if( nextV ==0)
                     prevLink.style.color="#afafaf";

            }

    }

</script>
