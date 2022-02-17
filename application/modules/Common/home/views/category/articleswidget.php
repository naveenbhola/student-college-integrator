                                <?php
                                if($countryNameSelected == "UK-ireland"){
									$countryNameSelected = "UK-Ireland";
								}
                                if(count($blogs['article'][0]) > 0) { ?>
                                <div class="wdh100">
                                    <div class="shik_skyBorder">                                	
                                        <div class="shik_roundCornerHeaderSpirit shik_skyGradient"><span class="Fnt14" style="padding-left:10px"><b> Most discussed about <?php echo $countryNameSelected?></b></span></div>
                                        <div class="mlr10">
                                        <?php                            		    
                                        if($countryNameSelected == 'Europe') {
                                        ?>

                                            <div class="lh10">&nbsp;</div>
											<div class="mlr10 txt_align_r"><img src="/public/images/campus_france.gif" border="0"></div>
                                            <?php } ?>
											<div class="lh10">&nbsp;</div>
                                            <div class="wdh100">
                                                <?php for($i = 0;$i<count($blogs['article'][0]);$i++) { 
                                                ?>            
                                                <div class="mb20">
                                                    <div class="float_L w85"><div class="wdh100" align="center"><img src="<?php echo (!isset($blogs['article'][0][$i]['blogImageURL']) || ($blogs['article'][0][$i]['blogImageURL'] == '')) ? '/public/images/faqSA.jpg' : str_replace('_s','_m',$blogs['article'][0][$i]['blogImageURL']) ?>" width = "85px" height = "72px"/></div></div>
                                                    <div class="ml95">
                                                        <div class="mb5"><a href="<?php echo $blogs['article'][0][$i]['url'];?>" title="<?php echo $blogs['article'][0][$i]['blogTitle']?>"><?php echo $blogs['article'][0][$i]['blogTitle']?></a></div>
                                                           <!-- <div class="mb5"><?php echo ($blogs['article'][0][$i]['blogtext']) . '...' ?></div> -->
                                                        
                                                        <?php 
                                                        if($blogs['article'][0][$i]['messageCount'] > 0) { ?>
                                                        <span><a href="<?php echo $blogs['article'][0][$i]['url'] .'#blogCommentSection'?>" class = "comnt comntA Fnt11"><?php echo ($blogs['article'][0][$i]['messageCount']); ?> Comments</a></span>                                                    
                                                        <?php  } else { ?>
                                                        <span><a href="<?php echo $blogs['article'][0][$i]['url']?>" class = "comnt comntA Fnt11">No Comments</a></span>                                                    
                                                        <?php } ?>
                                                        <a href="<?php echo $blogs['article'][0][$i]['url'].'#addblogCommentSection'?>" class = "comntA Fnt11">Comment Now</a>                                                   
                                                    </div>
                                                    <div class="clear_B"></div>
                                                </div>
                                             <?php } ?>
											</div>
                                           <?php if(strpos($countryId,',') === false) { ?>
                                            <div class="txt_align_r"><a href="<?php echo '/blogs/shikshaBlog/showArticlesList?c='.rand().'&country='.$countryId?>">View all Articles</a></div>
                                          <?php } else
                                           {?>
                                            <div class="txt_align_r"><a href="#" onClick = "showCountryOverlay('articles',this);return false;">View all Articles</a></div>
                                            <?php } ?>
                                        </div>
                                        <div class="lh10"></div>
                                    </div>
                                </div>
                                <?php } 
                                $this->load->view('home/shiksha/countrySelectionOverlay');
                                ?>
    <script>
    var urltoencode = '';
function showCountryOverlay(name,obj)
{
    document.getElementById('countrySelectionOverlay').style.display = '';
    overlayHackLayerForIE('countrySelectionOverlay', document.body);
    var h = document.body.scrollTop;
    var h1 = document.documentElement.scrollTop;
    var h2 = h > h1 ? h : h1;
    var divX = parseInt(screen.width/2 - $('countrySelectionOverlay').offsetWidth/2) + 20;
//    var divY = parseInt(screen.height/2 - $('countrySelectionOverlay').offsetHeight/2) + h2 - 150;

    var divY = obtainPostitionY(obj);

    if((h2 + screen.height) < (divY + $('countrySelectionOverlay').offsetHeight))
    {
        divY = h2 + screen.height;
    }
    $('countrySelectionOverlay').style.left = (divX) +  'px';
    $('countrySelectionOverlay').style.top = (divY) + 'px';
    switch(name)
    {
        case 'ask' :  urltoload = '<?php echo SHIKSHA_ASK_HOME_URL.'/messageBoard/MsgBoard/discussionHome/1/2/';?>';
                      break;
        case 'articles' : 
                      urltoload = '<?php echo '/blogs/shikshaBlog/showArticlesList?c='.rand().'&country=';?>';
                      break;
        case 'faq' : 
                      urltoload = '<?php echo '/blogs/shikshaBlog/showArticlesList?type=faq&country=';?>';
                      break;
    }
    return false;
}
</script>
