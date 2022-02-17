<div class="newExam-widget">
    <h2>
        <div class="widget-head"><p>Colleges accepting <?php echo $exam;?> <i class="common-sprite blue-arrw"></i></p></div>
    </h2>
    <ul>
    <?php
    $count=0;
    foreach($widgetData[$exam] as $key=>$val)
    {
        if($count%2==1){
           $style="float:right !important;";
           $class = "";
        }else{
           $style="";
           $class ="flLt";
        }
        $count++;
        if($count==3){
           $class ="flLt mrgnLeft"; 
        }
    ?>    
        <li class="<?php echo $class;?>" style="<?php echo $style;?>">
            <div class="examClg-sec"><i class="common-sprite examClg-icon"></i></div>
            <div class="examClg-link"><a href="<?php echo $val['url'];?>"><?php echo htmlentities($val['title']);?></a><span><?php echo $val['totalCount'].(($val['totalCount']>1)? " colleges ":" college");?> </span></div>
        </li>
    <?php } ?>    
    </ul>
</div>