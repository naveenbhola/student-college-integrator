<?php

class ldbTrackingLib {
    private $CI;
    
    public function __construct(){
        $this->CI = & get_instance();
        $this->usergroupAllowed = array("shikshaTracking");
    }

    function getLeadMatchingData($filterArray)
    {
        $pageData =array();

        $ldbmismodel = $this->CI->load->model('trackingMIS/ldbmismodel');

        $matchingLeadsCount = $ldbmismodel->countMatchingLeads($filterArray);
        

        $pageData['topTiles']['totalLeads'] = array_sum($matchingLeadsCount);    

        $pageData['DataForDifferentCharts'] = $this->prepareDataForDifferentCharts($matchingLeadsCount);
        
        return $pageData;
    }
 

    function getLeadAllocationData($filterArray)
    {
        $pageData =array();

        $ldbmismodel = $this->CI->load->model('trackingMIS/ldbmismodel');

        $allocationLeadsCount = $ldbmismodel->countAllocationLeads($filterArray);

        $pageData['topTiles']['totalLeads'] = array_sum($allocationLeadsCount);    

        $pageData['DataForDifferentCharts'] = $this->prepareDataForDifferentCharts($allocationLeadsCount);
        
        return $pageData;
    }

    function prepareDataForDifferentCharts($matchingLeadsCount)
    {

        $colorArray = array("blue","lime","purple","green","red","pink","silver","violet");
        $totalResponses = $pieChartDataOne['Desktop']+$pieChartDataOne['Mobile'];

        $lineChartData      = $this->prepareDataForLineChart($matchingLeadsCount);
        /*$pieChartOneData    = $this->prepareDataForPieChartOne($pieChartDataOne,$colorArray,$totalResponses);
        $pieChartTwoData    = $this->prepareDataForPieChartTwo($pieChartDataTwo,$colorArray,$totalResponses);
        
        if(!$isDataTable)
        {
            $DataForDataTable   = $this->prepareDataForDataTable($responsesData,$totalResponses);
            
        }*/
        
        $pageData['lineChartData'] = $lineChartData;
        $pageData['pieChartOneData'] = $pieChartOneData;
        $pageData['pieChartTwoData'] = $pieChartTwoData;
        $pageData['DataForDataTable'] = $DataForDataTable;    

        return $pageData;
    }

    function prepareDataForLineChart($lineChartData,$pageName,$responseType)
    {
        //$linechartHeading = $pageName.'  <small>'.$responseType.' Responses'.'</small>';
        $i=0;
        foreach ($lineChartData as $key => $value) {
            $lineChartArray[$i++] = array($key,$value);   
        }
        //_p($lineChartArray); die;
        //$lineChartDataForSelectedDuration[0] = array('linechartHeading',$linechartHeading);
        $lineChartData = array('lineChartArray', $lineChartArray);
        return $lineChartData;
    }

    function prepareDataForPieChartOne($pieChartDataOne,$colorArray,$totalResponses)
    {
        // prepare data for pie Chart1   
        $i = 0; 
        foreach ($pieChartDataOne as $key => $value) {
            $pieChartArrayOne[$i]['value'] = $value;
            $pieChartArrayOne[$i]['label'] = $key;
            $pieChartArrayOne[$i]['color'] = $colorArray[$i];
            
           
            $pieChartIndexDataOne=$pieChartIndexDataOne. 
                            '<tr>'.
                                '<td>'.
                                    '<p title="'.$key.'"><i class="fa fa-square '.$pieChartArrayOne[$i]['color'].'">'.'&nbsp'.$key.'&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'.'</i></p>'.
                                '</td>'.
                                '<td>'.number_format((($value*100)/$totalResponses), 2, '.', '').'%</td>'.
                            '</tr>';                            
            $i++;
        }
        //$pieChartOneDataForSelectedDuration[0] = array('pieChartArrayOne',$pieChartArrayOne);
        //$pieChartOneDataForSelectedDuration[1] = array('pieChartIndexDataOne',$pieChartIndexDataOne);

        $pieChartOneData = array($pieChartArrayOne,$pieChartIndexDataOne);
        //_p($pieChartOneDataForSelectedDuration);die;
        //$pieChartOneDataForSelectedDuration[1] = array('pieChartIndexDataOne',$pieChartIndexDataOne);
        return $pieChartOneData;
    }

    function prepareDataForPieChartTwo($pieChartDataTwo,$colorArray,$totalResponses)
    {
        // prepare data for pie Chart1
        $i=0;
        //reset($colorArray);
        foreach ($pieChartDataTwo as $key => $value) {
            $pieChartArrayTwo[$i]['value'] = $value;
            $pieChartArrayTwo[$i]['label'] = $key;
            $pieChartArrayTwo[$i]['color'] = $colorArray[$i];
            $pieChartIndexDataTwo=$pieChartIndexDataTwo. 
                            '<tr>'.
                                '<td>'.
                                    '<p title="'.$key.'"><i class="fa fa-square '.$pieChartArrayTwo[$i]['color'].'">'.'&nbsp&nbsp'.$key.'&nbsp&nbsp&nbsp&nbsp'.'</i></p>'.
                                '</td>'.
                                '<td>'.number_format((($value*100)/$totalResponses), 2, '.', '').'%</td>'.
                            '</tr>';                            
            $i++;
        }
        //$pieChartTwoDataForSelectedDuration[0] = array('pieChartArrayTwo',$pieChartArrayTwo);
        //$pieChartTwoDataForSelectedDuration[1] = array('pieChartIndexDataTwo',$pieChartIndexDataTwo);
        $pieChartTwoData = array($pieChartArrayTwo,$pieChartIndexDataTwo);
        return $pieChartTwoData;        
    }

    function prepareDataForDataTable($responsesData,$totalResponses)
    {
        // prepare data for dataTable
        foreach ($responsesData as  $value) {
            $prepareTableData[$value['type']][$value['widget']][$value['siteSource']]['responsesCount']+=$value['reponsesCount'];
        }
        //echo _p($prepareTableData);die;
        $dataTableHeading = "Response Data";
        $dataTable = '<thead>'.
                        '<tr class="headings">'.
                            '<th>'.
                                '<input type="checkbox" class="tableflat">'.
                            '</th>'.
                            '<th>Type </th>'.
                            '<th>Widget </th>'.
                            '<th>Site Source </th>'.
                            '<th>Response Count </th>'.
                            '<th>Response Percentage </th>'.
                        '</tr>'.
                    '</thead>'.
                    '<tbody>';


        foreach ($prepareTableData as $type => $typeArray) {
            foreach ($typeArray as $widget => $widgetArray) {
                foreach ($widgetArray as $siteSource => $value) {
                    $dataTable = $dataTable.
                        '<tr class="even pointer">'.
                            '<td class="a-center ">'.
                                '<input type="checkbox" class="tableflat">'.
                            '</td>'.
                            '<td class=" ">'.$type.'</td>'.
                            '<td class=" ">'.$widget.'</td>'.
                            '<td class=" ">'.$siteSource.'</td>'.
                            '<td class=" ">'.$value['responsesCount'].'</td>'.
                            '<td>'.number_format((($value['responsesCount']*100)/$totalResponses), 2, '.', '').'%'.'</td>'.
                        '</tr>';
                }    
            }
        }
        $dataTable = $dataTable.'</tbody>';
        //echo $dataTable;die;
        $DataForDataTable = array($dataTableHeading,$dataTable);
        //$dataTableDataForSelectedDuration[1] = array('dataTableHeading',$dataTableHeading);
        //echo _p($dataTableDataForSelectedDuration[1]);die;
        return $DataForDataTable;

    }
}
?>
    