<?php
$totalCmp[] = '';
$totalShrt = $courseShortArray;
$sum = 0;
$cookieCmpName = 'compare-mobile-global-categoryPage';
if($_COOKIE[$cookieCmpName]){$totalCmp = explode('|||',$_COOKIE[$cookieCmpName]);$totalCmp = count($totalCmp);}else{$totalCmp = 0;}
if(sizeof($totalShrt)>0 && $totalShrt[0] !=''){$total = sizeof($totalShrt);}else{$total = 0;}

$sum = ($totalCmp + $total);
?>

<div class="mylist-head" id="mylist-head">
<div style="position:relative">
    <i class="sprite myList-icon"></i>
    <div class="notification" id="notification" <?php if($sum == 0){?> style="display: none;" <?php }?> ><?php echo $sum;?></div>
    <p>My Lists</p>
        <div style="display:none;" class="mylist-layer" id="mylist-layer">
            <div class="list-title">My lists<i class="sprite pointer"></i></div>
            <div class="list-details">
                <ul>
                    <li><a href="javascript:void(0);" onclick="getGlobalCompareToolUrl();"><i class="sprite comp-list-icon"></i><span id="total-college-compare">College Compare (<?php echo $totalCmp;?>)</span></a></li>
                    <li class="last"><a href="<?=SHIKSHA_HOME?>/shortlisted-colleges"><i class="sprite shortlist-list-icon"></i><span id="total-shortlisted-colleges">Shortlisted Colleges (<?php echo $total;?>)</span></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            
        </div>
    </div>
</div>