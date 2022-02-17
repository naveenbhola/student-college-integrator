<?php if(!empty($applyContent)){?>
<section class="content-wrap clearfix">
<div class="more-app-process-sec clearfix" style="padding:15px; margin:0 !important">
<strong>Learn more about application process</strong>
    <ul class="apply-content-widget more-app-process-list2" style="width:100%; float:left;">
        <?php foreach ($applyContent as $key => $value) {
            if($value['type'] == 'CV')
            {
                $searchStr = 'stu-cv-icon';
                $replaceStr = 'cv-icn';
            }
            else
            {
                $searchStr = '-icon';
                $replaceStr = '-icn';
            }
            $value['icon'] = str_replace($searchStr,$replaceStr,$value['icon']);
            ?>
        <li>
            <i class="mobile-sop-sprite <?php echo $value['icon'];?> flLt"></i>
            <div class="app-process-block">
                <strong><a href="<?php echo $value['contentURL']; ?>"><?php echo $value['heading']; ?></a></strong>
                <p><?php echo $value['name']; ?></p>
            </div>
        </li>
        <?php
        } ?>
    </ul>
</div>
</section>
<?php } ?>