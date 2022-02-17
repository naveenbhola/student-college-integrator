<?php 
$j = 1;
foreach ($result as $streamId => $subStreamsAnsSpec) {
    if(in_array($streamId, array(3,5))){ //exlude design and law
        continue;
    }
?>
<ul class="others" style="<?php echo ($j > 10)? 'display:none;':''?>" >
    <li class="<?php echo $subStreamsAnsSpec['config']['liClass']?>">
        <a>
            <div class="<?php echo $subStreamsAnsSpec['config']['class']?>">
                <span>
                    <i>&nbsp;</i>
                </span>
                <div>
                    <h3><?php echo $subStreamsAnsSpec['name']?></h3>
                </div>
                <span>
                    <i>&nbsp;</i>
                </span>
            </div>
        </a>
    <?php 
    
    $finalArr1 = array();
    if(count($subStreamsAnsSpec['substreams']) > 0){
         foreach ($subStreamsAnsSpec['substreams'] as $key => $value) {
            $value['listType'] = 'subStream';
            $finalArr1[] = $value;
        }
    }
    $finalArr2 = array();
    if(count($subStreamsAnsSpec['specializations']) > 0){
         foreach ($subStreamsAnsSpec['specializations'] as $key => $value) {
            $value['listType'] = 'specialization';
            $finalArr2[] = $value;
        }
    }
    $finalArr = array();
    $finalArr = array_merge($finalArr1,$finalArr2);
    $collegeCutoffData =array();
    if($subStreamsAnsSpec['collegeCutoffPageLink']==1){
        $collegeCutoffData = $subStreamsAnsSpec['collegeCutoffData']; 
    }
    if(count($finalArr)>0){
        $this->load->view('homepageWidgets/otherCategorySubcatList',array('collegeCutoffData'=>$collegeCutoffData ,'subStreamsAnsSpec'=>$finalArr,'key'=>$streamId,'streamName' => $subStreamsAnsSpec['name']));
    }else{
        $this->load->view('homepageWidgets/defaultTabData',array('collegeCutoffData'=>$collegeCutoffData ,'key'=>$streamId,'streamName' => $subStreamsAnsSpec['name']));
    }
?>
    </li>
<?php $j++; if($j == 11){?>
<li>
    <a id="vmrStrm">
        <div><h3>View more</h3></div>
    </a>
</li>
<?php }?>
</ul>
<?php }?>