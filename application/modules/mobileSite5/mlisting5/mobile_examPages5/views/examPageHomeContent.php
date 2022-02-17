<div class="exam-accrodian clearfix" id="examDetails">
<?php
        $count=0;
        foreach($examPageData->getHompageData() as $key=>$value){
        if(!in_array($value->getLabel(), array('Exam Title', 'Official Website')) && trim(strip_tags($value->getDescription())))
	{                
        ?>
        <?php
                $summary = new tidy();
                $summary->parseString($value->getDescription(),array('show-body-only'=>true),'utf8');
                $summary->cleanRepair();
        ?>
        <dl class="examDetails">
            <dt><h2 class="flLt"><?php echo $value->getLabel();?></h2><i class="exam-mini-sprite exam-plus-icon flRt" id="desc<?php echo $count;?>" ></i></dt>
            <dd><div class="mceContentBody"><?=html_entity_decode($summary);?></div></dd>
        </dl>
<?php $count++;} } ?>   
</div>
