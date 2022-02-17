<?php
$params = array('instituteId'=>$details['institute_id'],'instituteName'=>$details['title'],'type'=>'institute','locality'=>$details['location']['0']['locality'],'city'=>$details['location']['0']['city_name'],'courseName'=>$details['courseDetails']['0']['title'],'courseId'=>$details['courseDetails']['0']['course_id']);

if ( $createMediaTab == 1){?>


<div class="wdh100">
    <div class="nlt_head Fnt14 bld mb10">Photos &amp; Videos</div>
    <div class="mlr5">
        <div>
             <?php

               foreach( $detailPageComponents as $mediaTypeIndex => $mediaType )
               {
                    if ( $lenVideo == 0){
                    if( $mediaTypeIndex == 0 )
                    {
                            foreach( $mediaType as $mediaIndex => $media)
                            {
                                     if( $mediaIndex == 'value')
                                     {
                                           $count=0;
                                           foreach( $media as $key => $value)
                                           { $count++; if ($count < 4){?>
                                                <a href="<?php echo listing_detail_media_url($params);?>" onClick="doAllStuff('pic','<?php echo $count-1;?>')"><img width="71" height="44" src="<?php echo str_replace('_m','_s',$value['thumburl']); ?>" class="mr10" border="0"></a>
                                                                 <?php }
                                           }
                                     }
                            }
                    }
                    }
                    if ( $lenPhoto == 0){
                    if( $mediaTypeIndex == 0 )
                    {
                            foreach( $mediaType as $mediaIndex => $media)
                            {
                                     if( $mediaIndex == 'value')
                                     {
                                           $count=0;
                                           foreach( $media as $key => $value)
                                           { $count++; if ($count < 4){?>
                                                <a href="<?php echo listing_detail_media_url($params);?>" onClick="doAllStuff('vid','<?php echo $count-1;?>')"><img width="71" height="44" src="<?php if($value['thumburl']== ''){ $sString= substr($value['url'],25);  echo "http://i1.ytimg.com/vi/".$sString."/2.jpg";}else {echo str_replace('_s','_m',$value['thumburl']); }?>" class="mr10" border="0"></a>
                                                                 <?php }
                                           }
                                     }
                            }
                    }
                    }

               }

                    if ( $lenPhoto > 0 && $lenVideo > 0)
                  {


                        $one=array();
                        $two= array();
                        $one= $detailPageComponents[0];
                        $two= $detailPageComponents[1];

                        if ( $lenPhoto == 1)
                        {$ac=3; $bc=2;}

                        if($lenPhoto > 1)
                        {$ac=2; $bc=3;}


                            foreach( $two as $mediaIndex => $media)
                            {
                                     if( $mediaIndex == 'value')
                                     {
                                           $count=0;
                                           foreach( $media as $key => $value)
                                           { $count++; if ($count < $ac){?>
                                                <a href="<?php echo listing_detail_media_url($params);?>" onClick="doAllStuff('vid','<?php echo $count-1;?>')"><img width="71" height="44" src="<?php if($value['thumburl']== ''){ $sString= substr($value['url'],25);  echo "http://i1.ytimg.com/vi/".$sString."/2.jpg";}else {echo str_replace('_s','_m',$value['thumburl']); }?>" class="mr10" border="0"></a>
                                                                 <?php }
                                           }
                                     }
                            }

                            foreach( $one as $mediaIndex => $media)
                            {
                                     if( $mediaIndex == 'value')
                                     {
                                           $count=0;
                                           foreach( $media as $key => $value)
                                           { $count++; if ($count < $bc){?>
                                                <a href="<?php echo listing_detail_media_url($params);?>" onClick="doAllStuff('pic','<?php echo $count-1;?>')"><img width="71" height="44" src="<?php echo str_replace('_m','_s',$value['thumburl']); ?>" class="mr10" border="0"></a>
                                                                 <?php }
                                           }
                                     }
                            }


                    }



             ?>
        </div>
	<?php $vidString = ($lenVideo==1)?'View 1 video':'View all '.$lenVideo.' videos';
	$picString = ($lenPhoto==1)?'View 1 photo':'View all '.$lenPhoto.' photos'; ?>
        <div class="Fnt11"><?php if ( $lenVideo > 0){?> <a onClick="trackEventByGA('LinkClick','LISTING_OVERVIEW_VIEW_ALL_VIDEOS_LINK');" href="<?php echo listing_detail_media_url($params);?>"><?php echo $vidString; ?></a><?php }?> <?php if ( ($lenPhoto > 0)&& ($lenVideo > 0)){ ?><span class="sepClr">|</span><?php }?><?php if ( $lenPhoto > 0){?><a onClick="trackEventByGA('LinkClick','LISTING_OVERVIEW_VIEW_ALL_PHOTOS_LINK');" href="<?php echo listing_detail_media_url($params);?>" ><?php echo $picString; ?></a><?php }?></div>
   </div>
</div>
<div class="lineSpace_20">&nbsp;</div>
<?php }?>
<script>
function doAllStuff(mediaType, ind){
    var name = 'listing_media_widget_'+mediaType;
    var index= ind;
    setCookie(name,index,0);
    trackEventByGA('LinkClick','LISTING_OVERVIEWTAB_MEDIA_CLICK');
}
</script>
