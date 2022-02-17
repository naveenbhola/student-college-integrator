<?php 
if(empty($popular_questions['questions']))
    return;
?>
<div class="row">
            <div class="anl-card clearfix">
                <div class="col-md-12">
                    <div class="row">
                        <div class="anl-crd-blk clearfix">
                            <div class="col-md-8 col-sm-12 clearfix">
                                <h2 class="pull-left">Trending Questions</h2>
                            </div>
                            <div class="col-md-4 col-sm-12"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <?php 
                            $counter = 1;
                            foreach ($popular_questions['questions'] as $key => $value) {
                        ?>
                            <div class="que-area clearfix">
                                <div class="qu-label"><span class="qu-blk"><?php echo $counter++;?>.</span></div>
                                <div><p class="qu-det"><a href="<?php echo $value['link'];?>" target="<?php echo $linkTarget;?>"><?php echo $value['name'];?></a></p></div>
                                <div class="qu-grp pull-right"><?php echo $value['answers'];?> Answer<?php echo $value['answers'] > 1 ? 's' : '';?> </div>
                            </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
                <!-- <div class="col-md-12">
                    <p class="quw-hd">Ask Queries to Current Students of this college</p>
                    <div class="col-md-10">
                        <div class="row">
                            <input type="text" class="quw-fld" placeholder="Type your question here.." />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-default clr-or">Ask Question</button>
                    </div>
                </div> -->
            </div>
                    
</div>