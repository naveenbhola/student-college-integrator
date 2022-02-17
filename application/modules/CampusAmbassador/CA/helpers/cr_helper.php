<?php
/***
* functionName : getCREarning
* functionType : return type
* param        : $timeDiff, $userId
* desciption   : this function is calling on to prepare incentives for campus rep/mentor on the basis of his category(Eng/Mba)
* @author      : akhter
* @team        : UGC
***/
function getCREarning($timeDiff,$isEnginerringIncentive='')
{     
        if($isEnginerringIncentive == "Engineering"){
                global $mywalletEnginerringIncentive;
                $wallet = $mywalletEnginerringIncentive;
        }else{
                global $mywalletAttributesArray;  //define in shikshaConstants.php
                $wallet = $mywalletAttributesArray;
        }

        //$timeDiff = (strtotime(date('Y-m-d H:i:s')) - strtotime($postedDatetime));
        
        $j=1;
        $money = Array();
        for($i =0; $i<count($wallet);$i++)
        {            
            if(($timeDiff > 0) && ($timeDiff <= $wallet[0]['time']))
            {
                $money = array('money'=>$wallet[$i]['money'],'featured'=>$wallet[$i]['featured']);
                return $money;
                exit;
            }elseif(($timeDiff > $wallet[count($wallet)-1]['time'])){
                $money = array('money'=>0,'featured'=>0);
                return $money;
                exit;
            }elseif(($timeDiff > $wallet[$i]['time']) && ($timeDiff <= $wallet[$j]['time']))
            {
                if($j==(count($wallet)-1)){                
                        $money = array('money'=>$wallet[$j-1]['money'],'featured'=>$wallet[$j-1]['featured']);
                        return $money; 
                        }else{
                        $money = array('money'=>$wallet[$j]['money'],'featured'=>$wallet[$j]['money']);        
                        return $money;
                        }
            }
            if($j==count($wallet)){$j=6;}else{$j=$j+1;}
        }
}

function formatTaskData($tasks, $taskId, $taskType){
        $taskArr = array();
        $tabNames = array('open','closed','upcoming');
        $openTabCount=0;$closedTabCount=0;$upcomingTabCount=0;
        foreach($tasks[$tabNames[0]] as $key=>$value){
                if(!in_array($value['id'],$tmp)){
                        $tmp[] = $value['id'];
                        $openTabCount++;
                }
        }
        $taskArr['totalCount'][$tabNames[0].'Count'] = $openTabCount;
        foreach($tasks[$tabNames[1]] as $key=>$value){
                if(!in_array($value['id'],$tmp)){
                        $tmp[] = $value['id'];
                        $closedTabCount++;
                }
        }
        $taskArr['totalCount'][$tabNames[1].'Count'] = $closedTabCount;
        foreach($tasks[$tabNames[2]] as $key=>$value){
                if(!in_array($value['id'],$tmp)){
                        $tmp[] = $value['id'];
                        $upcomingTabCount++;
                }
        }
        $taskArr['totalCount'][$tabNames[2].'Count'] = $upcomingTabCount;
        
        if(empty($tasks[$taskType])){
                //$taskArr['taskList'] =  array();
                //return $taskArr;
        }
        if(empty($tasks['closed'])){
                $taskArr['taskInfo']['closed'] = array();
        }
        if(empty($tasks['open'])){
                $taskArr['taskInfo']['open'] = array();
        }
        if(empty($tasks['upcoming'])){
                $taskArr['taskInfo']['upcoming'] = array();
        }
       
        foreach($tasks as $k=>$v){
                $rewardCount = 0;$count = 0;$storeFirstId='';
                foreach($v as $key=>$value){
                        if(!in_array($value['id'],$tmpArr)){
                                $taskArr['taskInfo'][$k]['taskList'][$count]['id'] = $value['id'];
                                $taskArr['taskInfo'][$k]['taskList'][$count]['taskName'] = $value['name'];
                                $tmpArr[] = $value['id'];
                        }
                        //if($count==0){
                        if($taskId=='' && ($storeFirstId == '' || $storeFirstId == $value['id'])){
                                $storeFirstId = $value['id'];
                                $taskArr['taskInfo'][$k]['defaultId'] = $value['id'];
                                $taskArr['taskInfo'][$k]['defaultName'] = $value['name'];
                                $taskArr['taskInfo'][$k]['defaultDescription'] = $value['description'];
                                $taskArr['taskInfo'][$k]['defaultStartDate'] = $value['start_date'];
                                $taskArr['taskInfo'][$k]['defaultEndDate'] = $value['end_date'];
                                $taskArr['taskInfo'][$k]['defaultRewards'][$rewardCount]['prize_id'] = $value['prize_id'];
                                $taskArr['taskInfo'][$k]['defaultRewards'][$rewardCount]['prize_name'] = $value['prize_name'];
                                $taskArr['taskInfo'][$k]['defaultRewards'][$rewardCount]['prize_amount'] = $value['prize_amount'];
                                $rewardCount++;
                        }
                        //}
                        if($taskId!=''){
                                if($value['id']==$taskId){
                                        $taskArr['taskInfo'][$k]['defaultId'] = $taskId;
                                        $taskArr['taskInfo'][$k]['defaultName'] = $value['name'];
                                        $taskArr['taskInfo'][$k]['defaultDescription'] = $value['description'];
                                        $taskArr['taskInfo'][$k]['defaultStartDate'] = $value['start_date'];
                                        $taskArr['taskInfo'][$k]['defaultEndDate'] = $value['end_date'];
                                        $taskArr['taskInfo'][$k]['defaultRewards'][$rewardCount]['prize_id'] = $value['prize_id'];
                                        $taskArr['taskInfo'][$k]['defaultRewards'][$rewardCount]['prize_name'] = $value['prize_name'];
                                        $taskArr['taskInfo'][$k]['defaultRewards'][$rewardCount]['prize_amount'] = $value['prize_amount'];
                                        $rewardCount++;                        
                                }
                        }
                        $count++;
                }
        } //_p($taskArr);die;
        return $taskArr;
}

function checkIfTaskIdExits($taskId, $tasks, $taskType){
        if($taskId==''){
                $status = 'true';
                return $status;
        }
        $status = 'false';
        foreach($tasks[$taskType] as $key=>$value){
                if($value['id']==$taskId){
                        $status = 'true';                
                }
        }
        return $status;
}

function getUniqeTasks($tasks){
        $taskArr = array();
        foreach($tasks as $key=>$value){
                if(!in_array($value['id'],$taskArr)){
                        $taskArr[] = $value['id'];
                }
        }
        return count($taskArr);
}

function formatMentorSlots($mentorSlots){
    $returnArr = array();
    foreach ($mentorSlots['slotTime'] as $key => $value) {
        foreach ($value as $k => $v) {
            $returnArr[$key][$k] = date('j F, h:i A',strtotime($v));
        }
    }
    return $returnArr;
}
?>