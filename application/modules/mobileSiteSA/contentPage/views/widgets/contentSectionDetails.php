<article class="content-inner2" style="padding-top:0;">
        <div class="article-guide-content">
        <div class="article-info" >
            <?php  // Getting title heading
        $title=$content['data']['strip_title'];
        if(empty($levelTwoNavBarData)){
                $title_heading="<h1 itemprop='headline name'> $title </h1>"; }
            else
                $title_heading="<h2  class='article-info-title'> $title </h2>"; 
            echo $title_heading;
        ?>
            
            <div class="article-author-info">
                    <span class="flLt">
                        <strong>By</strong>:
                        <?=$content['data']['username']?> ,
                        <?php echo date("d M'y",strtotime($content['data']['contentUpdatedAt']));?> | <?php echo date("h:i A",strtotime($content['data']['contentUpdatedAt']));?>
                    </span>
                <a href="javascript:void(0)" id="gotoComments" class="flRt">
                        <i class="sprite comment-icon"></i>
                        <?php if($comments['total'] == 0){ echo "Post your comment";}else{ ?>
                                <?=$comments['total']?> <?=$comments['total'] ==1?"Comment":"Comments"?>
                        <?php } ?>
                </a>
                <div class="clearfix"></div>
            </div>
            <div itemprop="articlebody">
            <div id="summary">
                <?=$content['data']['summary']?>
            </div>
            <div id="sectionData" style="height:auto; overflow: hidden;" class="dyanamic-content">
                <?php $i = 0; ?>
                <?php foreach($content['data']['sections'] as $section){ ?>
                  <div class="sectionDiv">
                    <strong class="article-info-title" id="sectionData<?=$i++?>"><?=$section['heading']?></strong>
                    <?=$section['details']?>
                  </div>
                <?php } ?>
            </div>
            </div>
        </div>
    </div>
</article>
