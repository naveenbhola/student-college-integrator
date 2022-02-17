<?php
$i =-1;
foreach($articles as $article) {
    $i++;
    $fullTitle= $article['blogTitle'];
    $content = strip_tags($article['summary']);
	if(strlen($content) > 200){
		$content = preg_replace('/\s+?(\S+)?$/', '',substr($content, 0,200))."...";
	}
    $url = $article['url'];
	switch($article['blogType']) {
		case 'kumkum': 
			$articleType = ' - By Kum Kum Tandon'; 
			$articleCaption = 'Kum Kum Tandon\'s Articles';
			break;
		case 'ht': 
			$articleCaption= 'Articles powered by HT Horizons'; 
			$articleType = 'By HT Horizons'; 
			break;
		case 'exam': 
			$articleCaption= 'Test Preparation Articles'; 
			$articleType = ''; 
			break;
		case 'iseet':
			$articleCaption= 'Know all about ISEET';
			$articleType = '';
			break;
		default: 
			$articleCaption= 'Shiksha Articles'; 
			$articleType = ''; 
			break;
	}
	
    if($isAcoursePage != 1 && $isShowTitle != 1) {
	if($i< 1 && !(isset($flavorFlag) && $flavorFlag)){
		if(isset($caption) && $caption!=''){
                        echo '<script> if(document.getElementById("criteriaLabel")) { document.getElementById("criteriaLabel").innerHTML = "'.$caption.' - '.'" } </script>';
                }
                else if( isset($articlePageType) && $articlePageType=='All' ){
                        echo '<script> if(document.getElementById("criteriaLabel")) { document.getElementById("criteriaLabel").innerHTML = "Shiksha Articles - '.'" } </script>';
                }
                else if( isset($articlePageType) && $articlePageType=='ALL_NEWS_ARTICLES'){
                        echo '<script> if(document.getElementById("criteriaLabel")) { document.getElementById("criteriaLabel").innerHTML = "Latest News and Articles - '.'" } </script>';
                }
                else{
                        echo '<script> if(document.getElementById("criteriaLabel")) { document.getElementById("criteriaLabel").innerHTML = "'. $articleCaption.' - '.'" } </script>';
                }
	}
    }
	
    $title = $article['blogTitle'];
    $blogType = ($article['blogType'] == 'news') ? 'News' : 'Articles';
    $articleImage = $article['blogImageURL'] == '' ? '/public/images/articlesDefault_s.gif' : $article['blogImageURL'];
    $numComments = $article['msgCount'];
    switch($numComments) {
        case '':
        case '0' : $numComments = ''; break; 
        case '1' : $numComments = '1 Comment'; break; 
        default : $numComments .= ' Comments'; break; 
    }
    if($numComments != '') {
        $caption = '<span style="font-size:14px;"><span style="margin: 0px 10px; color:#ccc; font-size:16px"> | </span> <img src="/public/images/alminiBlog.gif" align="absmiddle"/> <a href="' .$url .'#blogCommentSection">'. $numComments .'</a></span>';
    } else {
        $caption = '';
    }

    $interval = '';
    if(isset($flavorFlag) && $flavorFlag) { 
        $interval = date("jS F",strtotime($article['startDate'])) .' to '. date("jS F",strtotime($article['endDate']));
    }
    
    //@akhter
    // this code is useing to manage tag
    $mainTitle = '';
    if(strlen($title)>=65 && strlen($title)<=80){
	$arrTitle = explode(" ",$title);
	$titleCount = count($arrTitle);
	$titleLastword = $arrTitle[$titleCount-1];
	array_pop($arrTitle);
	$titleStr = implode(" ",$arrTitle);
	$mainTitle = $titleStr .'<br>'. $titleLastword;
    }
?>
     <div class="" id="listRow<?php echo $i;?>" style="padding:10px; line-height:25px;font-size:16px; color:#000000; text-decoration:none; border-bottom:1px solid #E1D7D7; margin-bottom:5px">
                <div class="float_L"><img src="<?php echo $articleImage; ?>" alt="<?php echo $title; ?>"/></div>
                <div style="margin-left:68px">
					<div style="font-size:18px;padding-bottom:3px;<?php echo $marginLeft ?>;"><a href="<?php echo $url; ?>" target="<?php echo $target;?>" title="<?php echo strip_tags($fullTitle);?>"><u><?php if($mainTitle !=''){ echo strip_tags($mainTitle);}else{echo strip_tags($title);} ?></u></a>&nbsp;&nbsp;<label class="graycolor"><?php echo $articleType;?></label>
					<?php if($isShowTag){ if($blogType == 'News'){?>
					    <img src="/public/images/news-badge.png" style="position:relative; top:4px;"/>
					<?php }else{?>
					    <img src="/public/images/article-badge.png" style="position:relative; top:4px;"/>
					<?php }}?>
					<?php echo $caption;?> </div>
                   <?php if($contentLine != '') { ?>
					<div style="<?php echo $marginLeft;?>" ><?php echo $contentLine; ?></div>
                    <?php } ?>
                    <?php if($content != '') { ?>
					<div style="<?php echo $marginLeft ?>;"><?php echo $content;?></div>
                    <?php } ?>
                    <?php if(isset($flavorFlag) && $flavorFlag && (!$latestUpdates)) { ?>
					<div style="<?php echo $marginLeft ?>;color:#6e6e6e; font-size:14px">Selected as flavor of the week from : &nbsp; <span style="background:#efefef; font-size:14px"> &nbsp; <?php echo $interval; ?> &nbsp; </span></div>
                    <?php } ?>
				</div>
                <div class="clearFix"></div>
    </div>
<?php
    }
?>
