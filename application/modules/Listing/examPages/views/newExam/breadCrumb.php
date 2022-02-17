<?php 
$examTitle     = htmlentities($param['examName']);
$examUrl       = $param['examUrl'];
$course_id     = $param['course_id'];
$courseName    = $param['popularCourseName'];
$stream_id     = $param['stream_id'];
$streamName    = $param['streamName'];
$substream_id  = $param['substream_id'];
$substreamName = $param['subStreamName'];
$sectionName   = $param['sectionName'];
$domain        = ($param['domain']) ? $param['domain'] : SHIKSHA_HOME ;
?>
<div class="bread__crumbs">
    <span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope=""><a itemprop="url" href="<?php echo SHIKSHA_HOME;?>"><span itemprop="title">Home</span></a></span>
        <span class="breadcrumb-arrow">&rsaquo;</span>
        <?php if(!empty($examTitle)){
        		if($param['isRootUrl'] == 'Yes'){?>
                    <?php if(empty($sectionName)){?>
                        <span><?php echo $examTitle;?></span>
                    <?php }else{?>
                        <span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
                            <a itemprop="url" href="<?php echo $examUrl;?>">
                                <span itemprop="title"><?php echo $examTitle;?></span>
                            </a>
                        </span>
                        <span class="breadcrumb-arrow">&rsaquo;</span>
                        <span><?php echo $sectionName;?></span>
                    <?php }
                }else if($param['conductedBy']['url'] !=''){?>

                    <?php if(empty($sectionName)){?>
                        <span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
                            <a itemprop="url" href="<?php echo $param['conductedBy']['url'];?>">
                                <span itemprop="title"><?php echo $param['conductedBy']['name'];?></span>
                            </a>
                        </span>
                        <span class="breadcrumb-arrow">&rsaquo;</span>
                         <span><?php echo $examTitle;?></span>
                    <?php }else{?>
                        <span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
                            <a itemprop="url" href="<?php echo $param['conductedBy']['url'];?>">
                                <span itemprop="title"><?php echo $param['conductedBy']['name'];?></span>
                            </a>
                        </span>
                        <span class="breadcrumb-arrow">&rsaquo;</span>
                        <span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
                            <a itemprop="url" href="<?php echo $examUrl;?>">
                                <span itemprop="title"><?php echo $examTitle;?></span>
                            </a>
                        </span>
                        <span class="breadcrumb-arrow">&rsaquo;</span>
                        <span><?php echo $sectionName;?></span>
                    <?php }

                }else if($courseName){?>

    				<span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
			        	<a itemprop="url" href="<?php echo $domain.'/'.strtolower(seo_url($courseName, "-", 30)).'/exams-pc-'.$course_id;?>">
			        		<span itemprop="title">All <?php echo $courseName;?> Exams</span>
			        	</a>
		    		</span>
    				<span class="breadcrumb-arrow">&rsaquo;</span>

    				<?php if(empty($sectionName)){?>
    					<span><?php echo $examTitle;?></span>
    				<?php }else{?>
    					<span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
			        		<a itemprop="url" href="<?php echo $domain.'/'.strtolower(seo_url($courseName, "-", 30)).'/'.strtolower(seo_url($examTitle, "-", 30)).'-exam';?>">
			        			<span itemprop="title"><?php echo $examTitle;?></span>
			        		</a>
		    			</span>
    					<span class="breadcrumb-arrow">&rsaquo;</span>
    					<span><?php echo $sectionName;?></span>
                    <?php }?>    
    			<?php }else if($streamName !='' && empty($substreamName)){?>

    				<span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
			        	<a itemprop="url" href="<?php echo $domain.'/'.strtolower(seo_url($streamName, "-", 30)).'/exams-st-'.$stream_id;?>">
			        		<span itemprop="title">All <?php echo $streamName;?> Exams</span>
			        	</a>
		    		</span>
    				<span class="breadcrumb-arrow">&rsaquo;</span>
    				<?php if(empty($sectionName)){?>
    					<span><?php echo $examTitle;?></span>
    				<?php }else{?>
    					<span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
			        		<a itemprop="url" href="<?php echo $domain.'/'.strtolower(seo_url($streamName, "-", 30)).'/'.strtolower(seo_url($examTitle, "-", 30)).'-exam';?>">
			        			<span itemprop="title"><?php echo $examTitle;?></span>
			        		</a>
		    			</span>
    					<span class="breadcrumb-arrow">&rsaquo;</span>
    					<span><?php echo $sectionName;?></span>
                    <?php }?>


    			<?php }else if($streamName !='' && !empty($substreamName)){?>

    				<span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
			        	<a itemprop="url" href="<?php echo $domain.'/'.strtolower(seo_url($streamName, "-", 30)).'/exams-st-'.$stream_id;?>">
			        		<span itemprop="title">All <?php echo $streamName;?> Exams</span>
			        	</a>
		    		</span>
                    <span class="breadcrumb-arrow">&rsaquo;</span>
    				<span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
			        	<a itemprop="url" href="<?php echo $domain.'/'.strtolower(seo_url($streamName, "-", 30)).'/'.strtolower(seo_url($substreamName, "-", 30)).'/exams-sb-'.$stream_id.'-'.$substream_id;?>">
			        		<span itemprop="title">All <?php echo $substreamName;?> Exams</span>
			        	</a>
		    		</span>
					<span class="breadcrumb-arrow">&rsaquo;</span>
    				<?php if(empty($sectionName)){?>
    					<span><?php echo $examTitle;?></span>
    				<?php }else{?>
    					<span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
			        		<a itemprop="url" href="<?php echo $domain.'/'.strtolower(seo_url($streamName, "-", 30)).'/'.strtolower(seo_url($substreamName, "-", 30)).'/'.strtolower(seo_url($examTitle, "-", 30)).'-exam';?>">
			        			<span itemprop="title"><?php echo $examTitle;?></span>
			        		</a>
		    			</span>
    					<span class="breadcrumb-arrow">&rsaquo;</span>
    					<span><?php echo $sectionName;?></span>
                    <?php }?>

    			<?php }
    	}else if(empty($examTitle)){
    			if($courseName){?>
    				<span>All <?php echo $courseName;?> Exams</span>
    			<?php }else if($streamName !='' && empty($substreamName)){?>
    				<span>All <?php echo $streamName;?> Exams</span>
    			<?php }else if($streamName !='' && !empty($substreamName)){?>
    				<span itemtype="https://data-vocabulary.org/Breadcrumb" itemscope="">
			        	<a itemprop="url" href="<?php echo $domain.'/'.strtolower(seo_url($streamName, "-", 30)).'/exams-st-'.$stream_id;?>">
			        		<span itemprop="title">All <?php echo $streamName;?> Exams</span>
			        	</a>
		    		</span>
					<span class="breadcrumb-arrow">&rsaquo;</span>
    				<span>All <?php echo $substreamName;?> Exams</span>
    			<?php }?>		
    	<?php }?>	
</div>