<?php 
$faqQuestions = $widgets_data['faqQuestions'];
?>
<?php if(count($faqQuestions)>0):
$count= 1;
?>
<div class="faq-widget-box widget-wrap">
        <h2><?=$widgetObj->getWidgetHeading();?></h2>
       	<div class="faq-banner">
        	<a href="<?php echo $coursePagesSeoDetails['Faq']['URL'];?>"> <img src="//<?php echo IMGURL; ?>/public/images/faq_11dec.jpg" alt="faq" /></a>
        </div>
         <ul class="faq-qna">
         <?php foreach ($faqQuestions as $row):
         	$url = $row['questionUrl'];
         	if(strpos($url,"http://") === FALSE && strpos($url,"https://") === FALSE) {
         		$url = "http://".$url;
         	}
         ?>
            <li>
                <i class="qa-icon">Q</i>
                <p class="qna-details"><a uniqueattr="CPGS_SESSION_<?php echo $widgetObj->getWidgetKey()?>/<?php echo "position".$count?>" href="<?php echo $url;?>"><?php echo formatArticleTitle(html_entity_decode(stripslashes($row['questionText'])),100);?></a></p>
            </li>          
            <?php 
            	$count++;
            	endforeach;
            ?>
            <li>
                <a href="<?php echo $allTabsSeoDetails['Faq']['URL']?>" class="all-faq flRt">View all <?php echo $COURSE_HOME_PAGES_LIST[$courseHomePageId]['Name'];?> FAQ &raquo;</a>
            </li>
        </ul>
    </div>
<?php endif;?>    
