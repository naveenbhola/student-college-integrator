 <div class="exam-accrodian clearfix">
    <?php
        $summary = new tidy();
        $summary->parseString($examPageData->getSyllabus()->getDescription(),array('show-body-only'=>true),'utf8');
        $summary->cleanRepair();
    ?>
    <dl style="border:0" class="examDetails">
        <dt><h1 class="flLt"><?php echo $examPageData->getSyllabus()->getLabel();?></h1><i class="exam-mini-sprite exam-minus-icon flRt"></i></dt>
        <dd><div class="mceContentBody"><?=html_entity_decode($summary);?></div></dd>
    </dl>
</div>
