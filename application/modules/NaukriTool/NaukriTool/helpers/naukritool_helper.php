<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
function getJobOperationShortName($pageData, $configData){
    $result = array();
    foreach($pageData as $chartType=>$data){
        
        if($chartType=='jobFuncData'){
            foreach($data as $key=>$value){
                if(array_key_exists($value['functional_area'], $configData))
                    $result[$chartType][$key]['functional_area'] = $configData[$value['functional_area']];
                else
                    $result[$chartType][$key]['functional_area'] = $value['functional_area'];
                $result[$chartType][$key]['totalEmployee']   = $value['totalEmployee'];
            }
        }else{
            $result[$chartType]   = $data;
        }
    }
    return $result;
}

function checkForOtherData($data, $jobFunc, $companies, $cities, $clickedDoughnut, $configData){
    $result = array();
    foreach($data as $chartType=>$innerArr){
        if($chartType == 'jobFuncData'){
            $retainData = '';
            $count = 0;$sumOfEmp=0;$countLessThanFivePercent = 0;$retainData = '';
            if(($clickedDoughnut=='companies' || $clickedDoughnut=='cities') && $jobFunc!=''){
                    $retainData = $jobFunc;
            }
            foreach($innerArr as $key=>$value){
                if($key>=0 && is_int($key) && $count<6){
                        $sumOfEmp += $value['totalEmployee'];
                        if(((($value['totalEmployee']/$innerArr['totalEmp'])*100)<5) && $retainData!=$value['functional_area']){
                            $countLessThanFivePercent += $value['totalEmployee'];
                        }
                        $count++;
                }
            }

            $allEmp =  $innerArr['totalEmp'];
            
            $fixedOtherData = round((2/3)*$sumOfEmp);
            $dynamicOtherData = $allEmp+$countLessThanFivePercent-$sumOfEmp;
            
            if($dynamicOtherData<=$fixedOtherData){
                $otherData = $dynamicOtherData;
            }else{
                $otherData = $fixedOtherData;
            }
            $finalEmpCount = $otherData+$sumOfEmp;
            $totalCount = 0;$matchCount = 0;$count = 0;
            foreach($innerArr as $key=>$value){
                if($key>=0 && is_int($key) && $totalCount<6){
                        if($retainData==''){
                            if((($value['totalEmployee']/$finalEmpCount)*100)>=5){
                                $result[$chartType][$key]['functional_area'] = $value['functional_area'];
                                $result[$chartType][$key]['totalEmployee']   = $value['totalEmployee'];
                                $count++;
                                $totalCount = $count;
                            }
                        }else{
                            if((($value['totalEmployee']/$finalEmpCount)*100)>=5 && $count<5 && $retainData!=$configData[$value['functional_area']]){
                                $result[$chartType][$totalCount]['functional_area'] = $value['functional_area'];
                                $result[$chartType][$totalCount]['totalEmployee']   = $value['totalEmployee'];
                                $count++;
                            }
                            if($retainData==$configData[$value['functional_area']] && $matchCount<1){
                                $result[$chartType][$totalCount]['functional_area'] = $value['functional_area'];
                                $result[$chartType][$totalCount]['totalEmployee']   = $value['totalEmployee'];
                                $matchCount++;
                            }
                            $totalCount = $count+$matchCount;
                        }
                }
            }
            $result[$chartType][$totalCount]['functional_area'] = 'View Others';
            $result[$chartType][$totalCount]['totalEmployee']   = $otherData;    
        }

        if($chartType == 'companiesData'){
                $retainData = '';
                if(($clickedDoughnut=='JobFunction' || $clickedDoughnut=='cities') && $companies!=''){
                        $retainData = $companies;
                }
                $count = 0;$sumOfEmp=0;$countLessThanFivePercent=0;
                foreach($innerArr as $key=>$value){
                    if($key>=0 && is_int($key) && $count<6){
                            $sumOfEmp += $value['totalEmployee'];
                            if(((($value['totalEmployee']/$innerArr['totalEmp'])*100)<5) && $retainData!=$value['functional_area']){
                                $countLessThanFivePercent += $value['totalEmployee'];
                            }
                            $count++;
                    }
                }
                $count = 0;
                $allEmp =  $innerArr['totalEmp'];
                $fixedOtherData = round((2/3)*$sumOfEmp);
                $dynamicOtherData = $allEmp-($sumOfEmp-$countLessThanFivePercent);
                
                if($dynamicOtherData<=$fixedOtherData){
                    $otherData = $dynamicOtherData;
                }else{
                    $otherData = $fixedOtherData;
                }
                $finalEmpCount = $otherData+$sumOfEmp;
                $totalCount = 0;$matchCount = 0;$count=0;
                foreach($innerArr as $key=>$value){
                    if($key>=0 && is_int($key) && $totalCount<6){
                        if($retainData==''){
                            if((($value['totalEmployee']/$finalEmpCount)*100)>=5){
                                    $result[$chartType][$key]['comp_label']      = $value['comp_label'];
                                    $result[$chartType][$key]['totalEmployee']   = $value['totalEmployee'];
                                    
                                    $count++;
                                    $totalCount = $count;
                            }
                        }else{
                            if((($value['totalEmployee']/$finalEmpCount)*100)>=5 && $count<5 && ($retainData!=$value['comp_label'])){
                                $result[$chartType][$totalCount]['comp_label']      = $value['comp_label'];
                               $result[$chartType][$totalCount]['totalEmployee']   = $value['totalEmployee'];
                               $count++;
                            }
                            if($retainData==$value['comp_label'] && $matchCount<1){
                                $result[$chartType][$totalCount]['comp_label'] = $value['comp_label'];
                                $result[$chartType][$totalCount]['totalEmployee']   = $value['totalEmployee'];
                                $matchCount++;
                            }
                            $totalCount = $count+$matchCount;
                        }
                    }
                }
                $result[$chartType][$totalCount]['comp_label'] = 'View Others';
                $result[$chartType][$totalCount]['totalEmployee']   = $otherData;
        }
        
        if($chartType == 'citiesData'){
                $retainData = '';
                if(($clickedDoughnut=='JobFunction' || $clickedDoughnut=='companies') && $cities!=''){
                        $retainData = $cities;
                }
                $count = 0;$sumOfInst=0;$countLessThanFivePercent=0;
                foreach($innerArr as $key=>$value){
                    if($key>=0 && is_int($key) && $count<6){
                            $sumOfInst += $value['totalInst'];
                            if((($value['totalInst']/$innerArr['totalInst'])*100)<5){
                                $countLessThanFivePercent += $value['totalEmployee'];
                            }
                            $count++;
                    }
                }
                $totalCount = 0;$matchCount = 0;$count=0;
                $allInst =  $innerArr['totalInst'];
                $fixedOtherData = round((2/3)*$sumOfInst);
                $dynamicOtherData = $allInst+$countLessThanFivePercent-$sumOfInst;
                
                if($dynamicOtherData<=$fixedOtherData){
                    $otherData = $dynamicOtherData;
                }else{
                    $otherData = $fixedOtherData;
                }
                $finalInstCount = $otherData+$sumOfInst;
                
                foreach($innerArr as $key=>$value){
                        if($key>=0 && is_int($key) && $totalCount<6){
                            if($retainData==''){
                                if((($value['totalInst']/$finalInstCount)*100)>=5 ){
                                    $result[$chartType][$key]['city_name']      = $value['city_name'];
                                    $result[$chartType][$key]['totalInst']      = $value['totalInst'];
                                    $count++;
                                    $totalCount = $count;
                                }
                            }else{
                                if((($value['totalInst']/$finalInstCount)*100)>=5 && $count<5 && ($retainData!=$value['city_name'])){
                                    $result[$chartType][$totalCount]['city_name']      = $value['city_name'];
                                    $result[$chartType][$totalCount]['totalInst']      = $value['totalInst'];
                                    $totalCount = $count;
                                    $count++;   
                                }
                                if($retainData==$value['city_name'] && $matchCount<1){
                                    $result[$chartType][$totalCount]['city_name'] = $value['city_name'];
                                    $result[$chartType][$totalCount]['totalInst']   = $value['totalInst'];
                                    $matchCount++;
                                }
                                $totalCount = $count+$matchCount;
                            }
                        }
                }
                
                $result[$chartType][$totalCount]['city_name'] = 'View Others';
                $result[$chartType][$totalCount]['totalInst']   = $otherData;
        }
    }
    return $result;
}

function getIndianDisplableAmt($amount, $decimalPointPosition = 1)
{
        if($amount < 1000)
                $finalAmount = number_format($amount, $decimalPointPosition, '.', '');
        else if($amount < 100000)
        {
                $finalAmount = $amount / 1000;
                $finalAmount = number_format($finalAmount, $decimalPointPosition, '.', '');
                $finalAmount .= " K";
        }
        else if($amount < 10000000)
        {
                $finalAmount = $amount / 100000;
                $finalAmount = number_format($finalAmount, $decimalPointPosition, '.', '');
                $finalAmount .= " L";
        }
        else
        {
                $finalAmount = $amount / 10000000;
                $finalAmount = number_format($finalAmount, $decimalPointPosition, '.', '');
                $finalAmount .= " Cr";
        }
        
        $finalAmount = $finalAmount;
        return $finalAmount;
}

function setTopSixDoghnutChartDataForSeo($functionType){
    $topSixJobFunc  = array(
                        'functionname'=>  array('sales','banking','marketing','finance','hr','it'),
                        'sales'       =>  array('index'=>0,'value'=>'Sales'),
                        'banking'     =>  array('index'=>1,'value'=>'Banking'),
                        'marketing'   =>  array('index'=>2,'value'=>'Marketing'),
                        'finance'     =>  array('index'=>3,'value'=>'Finance'),
                        'hr'          =>  array('index'=>4,'value'=>'Human Resources'),
                        'it'          =>  array('index'=>5,'value'=>'IT Software'),
                    );
    $topSixCompanies = array(
                        'functionname'=>  array('hdfc'),
                        'hdfc'       =>  array('index'=>0,'value'=>'HDFC Bank'),
                );
    $result = array();
    if(in_array($functionType,$topSixJobFunc['functionname'])){
        $result['url']   = SHIKSHA_HOME.'/mba/resources/best-mba-'.$functionType.'-colleges-based-on-mba-alumni-data';
        $result['title'] = 'MBA Career Compass | Shiksha.com';
        $result['seo_data'] = "JobFunc_".$topSixJobFunc[$functionType]['value']."_".$topSixJobFunc[$functionType]['index'];
    }else{
        setcookie('seo_data', '', time() - 3600,"/",COOKIEDOMAIN);
        $result['url']   = SHIKSHA_HOME.'/mba/resources/mba-alumni-data';
        $result['title'] = 'MBA Career Compass | Shiksha.com';
        $result['seo_data'] = '';
    }
    return $result;
}
?>
