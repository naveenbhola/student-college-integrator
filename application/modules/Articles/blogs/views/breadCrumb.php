<?php 
$articleTitle  = ucwords($param['articleTitle']);
$course_id     = $param['course_id'];
$courseName    = $param['popularCourseName'];
$stream_id     = $param['stream_id'];
$streamName    = $param['streamName'];
$substream_id  = $param['substream_id'];
$substreamName = $param['subStreamName'];
$domain        = ($param['domain']) ? $param['domain'] : SHIKSHA_HOME ;
$blogType      = $param['blogType'];
$streamCHPUrl  = $param['streamCHPUrl'];
$baseCourseCHPUrl = $param['baseCourseCHPUrl'];
?>
<div class="breadcrumb2">
    <span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope=""><a itemprop="url" href="<?php echo SHIKSHA_HOME;?>"><span itemprop="title"><i class="icons ic_brdcrm homeType1"></i></span></a></span>
        <span class="breadcrumb-arrow">&rsaquo;</span>
        <?php if(empty($articleTitle)){
        		if($courseName){?>
    				<span>All <?php echo $courseName;?> articles</span>
    			<?php }else if($streamName !='' && empty($substreamName)){?>
    				<span>All <?php echo $streamName;?> articles</span>
    			<?php }else if($streamName !='' && !empty($substreamName)){?>
    				<span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
			        	<a itemprop="url" href="<?php echo $domain.'/'.strtolower(seo_url($streamName, "-", 30)).'/articles-st-'.$stream_id;?>">
			        		<span itemprop="title">All <?php echo $streamName;?> articles</span>
			        	</a>
		    		</span>
					<span class="breadcrumb-arrow">&rsaquo;</span>
    				<span>All <?php echo $substreamName;?> articles</span>
    			<?php }else{?>
    				<span itemprop="title">All Articles</span>
    			<?php }
    		}else{
                if(!empty($courseName)){
                    if(!empty($baseCourseCHPUrl)) {
                ?>
                        <span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
                            <a itemprop="url" href="<?=$domain.$baseCourseCHPUrl?>">
                                <span itemprop="title"><?=$courseName?></span>
                            </a>
                        </span>
                        <span class="breadcrumb-arrow">&rsaquo;</span>
                <?php
                    }
        ?>
                    <span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
			        	<a itemprop="url" href="<?=$domain.'/'.strtolower(seo_url($courseName, "-", 30)).'/articles-pc-'.$course_id?>">
			        		<span itemprop="title">All <?=$courseName?> articles</span>
			        	</a>
		    		</span>
                    <span class="breadcrumb-arrow">&rsaquo;</span>
        <?php   }elseif (!empty($streamName)){
                    if (!empty($streamCHPUrl)){
                ?>
                        <span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
                            <a itemprop="url" href="<?=$domain.$streamCHPUrl?>">
                                <span itemprop="title"><?=$streamName?></span>
                            </a>
                        </span>
                        <span class="breadcrumb-arrow">&rsaquo;</span>
                <?php
                    }
        ?>
                    <span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
			        	<a itemprop="url" href="<?=$domain.'/'.strtolower(seo_url($streamName, "-", 30)).'/articles-st-'.$stream_id?>">
			        		<span itemprop="title">All <?=$streamName?> articles</span>
			        	</a>
		    		</span>
                    <span class="breadcrumb-arrow">&rsaquo;</span>
        <?php   }else{
        ?>
                    <span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
			        	<a itemprop="url" href="<?=$domain.'/articles-all'?>">
			        		<span itemprop="title">All Articles</span>
			        	</a>
		    		</span>
                    <span class="breadcrumb-arrow">&rsaquo;</span>
        <?php   }
        ?>
                    <span><?=$articleTitle?></span>
        <?php

    			/*if(in_array($blogType,array('boards','coursesAfter12th'))){?>
    			
    				<span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
			        	<a itemprop="url" href="<?php echo $domain.'/articles-all';?>">
			        		<span itemprop="title">All Articles</span>
			        	</a>
		    		</span>
		    		<span class="breadcrumb-arrow">&rsaquo;</span>
					<span><?php echo $articleTitle;?></span>

    			<?}else if($courseName){?>

        			<span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
			        	<a itemprop="url" href="<?php echo $domain.'/'.strtolower(seo_url($courseName, "-", 30)).'/articles-pc-'.$course_id;?>">
			        		<span itemprop="title">All <?php echo $courseName;?> articles</span>
			        	</a>
		    		</span>
		    		<span class="breadcrumb-arrow">&rsaquo;</span>
    				<span><?php echo $articleTitle;?></span>

    			<?php }else if($streamName !='' && empty($substreamName)){?>

    				<span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
			        	<a itemprop="url" href="<?php echo $domain.'/'.strtolower(seo_url($streamName, "-", 30)).'/articles-st-'.$stream_id;?>">
			        		<span itemprop="title">All <?php echo $streamName;?> articles</span>
			        	</a>
		    		</span>
		    		<span class="breadcrumb-arrow">&rsaquo;</span>
    				<span><?php echo $articleTitle;?></span>

    			<?php }else if($streamName !='' && !empty($substreamName)){?>
    				<span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
			        	<a itemprop="url" href="<?php echo $domain.'/'.strtolower(seo_url($streamName, "-", 30)).'/articles-st-'.$stream_id;?>">
			        		<span itemprop="title">All <?php echo $streamName;?> articles</span>
			        	</a>
		    		</span>

					<span class="breadcrumb-arrow">&rsaquo;</span>
    				
    				<span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
			        	<a itemprop="url" href="<?php echo $domain.'/'.strtolower(seo_url($streamName, "-", 30)).'/'.strtolower(seo_url($substreamName, "-", 30)).'/articles-sb-'.$stream_id.'-'.$substream_id;?>">
			        		<span itemprop="title">All <?php echo $substreamName;?> articles</span>
			        	</a>
		    		</span>
		    		<span class="breadcrumb-arrow">&rsaquo;</span>
    				<span><?php echo $articleTitle;?></span>

    			<?php }else{?>
    				<span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
			        	<a itemprop="url" href="<?php echo $domain.'/articles-all';?>">
			        		<span itemprop="title">All Articles</span>
			        	</a>
		    		</span>
		    		<span class="breadcrumb-arrow">&rsaquo;</span>
					<span><?php echo $articleTitle;?></span>
    			<?php } */

    		}?>
</div>