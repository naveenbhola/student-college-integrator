<?php
class RelatedData extends MX_Controller {
    function cmsUserValidation() {
        $validity = $this->checkUserValidation();
        if(($validity == "false" )||($validity == "")) {
            header('location:/enterprise/Enterprise/loginEnterprise');
            exit();
        }
        else
        {
            $usergroup = $validity[0]['usergroup'];
            if($usergroup !="cms")
            {
                header("location:/enterprise/Enterprise/unauthorizedEnt");
                exit();
            }
        }

    }
    function init() {
    $this->load->library(array('relatedClient','listing_client'));
    }

    function index($pageId = 1, $productId = 0, $productType = 'institute') {
        $this->init();
        $this->cmsUserValidation();
        $RelatedClient = new RelatedClient();
        $ListingClientObj = new Listing_client();
        $listingDetails = $ListingClientObj->getListingDetails('12',$productId , $productType);
        $IncludeList = $RelatedClient->getIncludeQuestionList($productId , $productType);
        //echo "Shirish ".$IncludeList;
        $ExcludeList = $RelatedClient->getExcludeQuestionList($productId , $productType);
        //echo "Shirish".$ExcludeList;
        $relatedQuestionQuery = $RelatedClient->getQueryStringForRelatedQuestion($productId , $productType);
        //echo "Shirish".$relatedQuestionQuery;

        if(isset($listingDetails[0]))
        {
            if($relatedQuestionQuery == "")
            {
                $relatedQuestionQuery = $listingDetails[0]['title'];
            }
            /*echo "<pre>";
            print_r($listingDetails);
            echo "</pre>";*/
            ?>
            <style>
.inputBorder{font-family:Verdana,Arial;font-size:11px;border-top:1px solid #000000;border-left:1px solid #000000;border-bottom:1px solid #c7c7c7;border-right:1px solid #c7c7c7;height:20px}
.inputBorderGray{font-family:Verdana,Arial;font-size:11px;border:1px solid #cdcdcd;height:17px}
.errorMsg {font-family:Arial, Helvetica, sans-serif; font-size:11px; text-decoration:none; color:#ff0000;}
.errorPlace {display:none;}
.row{ width:100%;}
.row1{float:left;width:265px;text-align:right;padding:3px 10px 5px 0}
.row2{margin-left:277px;line-height:normal;padding:3px 0;text-align:justify;}
            </style>
            <form method="post" action="/relatedData/relatedData/updateRelatedQuestionParams"/>
            <input type="hidden" id="productId" name="productId" value="<?php echo $productId; ?>"/>
            <input type="hidden" id="productName" name="productName" value="<?php echo $productType; ?>"/>
             <div class="row">
                <div class="row1"><b>Query to be searched :</b></div>
                <div class="row2">
                <input type="text" class="inputBorder" style="width:180px" id="queryString" name="queryString" maxlength="200" validate="validateStr" caption="name" required="true" minlength="5" value="<?php echo $relatedQuestionQuery; ?>"/>
                <div style="display:none"><div class="errorMsg" id="name_error" style="*margin-left:3px;"></div></div>
                </div>
             </div>
              <div style="line-height:3px;clear:both">&nbsp;</div>
             <div class="row">
                <div class="row1"><b>Questions to be included:</b></div>
                <div class="row2">
                <input type="text" class="inputBorder" style="width:180px" id="includeList" name="includeList" maxlength="50" validate="validateStr" caption="name" required="true" minlength="5" value="<?php echo $IncludeList; ?>"/>
                <div style="display:none"><div class="errorMsg" id="name_error" style="*margin-left:3px;"></div></div>
                </div>
             </div>
              <div style="line-height:3px;clear:both">&nbsp;</div>
             <div class="row">
                <div class="row1"><b>Questions to be excluded:</b></div>
                <div class="row2">
                <input type="text" class="inputBorder" style="width:180px" id="excludeList" name="excludeList" maxlength="50" validate="validateStr" caption="name" required="true" minlength="5" value="<?php echo $ExcludeList; ?>"/>
                <div style="display:none"><div class="errorMsg" id="name_error" style="*margin-left:3px;"></div></div>
                </div>
             </div>
             <input type="submit" name="submit" id="submit"/>
             </form>
        <?php
        }
        else
        {
            echo "This listing does not exist or has been deleted";
        }

    }

    function updateRelatedQuestionParams()
    {
        $productId = $_REQUEST['productId'];
        $productType = $_REQUEST['productName'];
        $newIncludeList = isset($_REQUEST['includeList'])?$_REQUEST['includeList']:0;
        $newExcludeList = isset($_REQUEST['excludeList'])?$_REQUEST['excludeList']:0;
        $newQueryString = isset($_REQUEST['queryString'])?$_REQUEST['queryString']:0;

        error_log("Shirish ".print_r($_REQUEST,true));

        $this->init();
        $this->cmsUserValidation();
        $RelatedClient = new RelatedClient();
        $ListingClientObj = new Listing_client();
        $listingDetails = $ListingClientObj->getListingDetails('12',$productId , $productType);
        if(isset($listingDetails[0]['title']))
        {
            $IncludeList = $RelatedClient->getIncludeQuestionList($productId , $productType);
            //echo "Shirish ".$IncludeList;
            $ExcludeList = $RelatedClient->getExcludeQuestionList($productId , $productType);
            //echo "Shirish".$ExcludeList;
            $relatedQuestionQuery = $RelatedClient->getQueryStringForRelatedQuestion($productId , $productType);
            $changedSomething=0;
            if($newIncludeList != $IncludeList && $newIncludeList!=0)
            {
                $RelatedClient->updateIncludeQuestionList($productId,$productType,$newIncludeList);
                $changedSomething =1;
            }
            if($newExcludeList != $ExcludeList && $newExcludeList!=0)
            {
                $RelatedClient->updateExcludeQuestionList($productId,$productType,$newExcludeList);
                $changedSomething =1;
            }
            error_log(" Shirish ". $relatedQuestionQuery ."  :  ".$newQueryString ." : ".($newQueryString==$relatedQuestionQuery)." : ".($newQueryString===0));
            if($newQueryString != $relatedQuestionQuery && $newQueryString!==0)
            {
                error_log("Shirish121213231");
                $RelatedClient->updateQueryStringForRelatedQuestion($productId,$productType,$newQueryString);    
                $changedSomething =1;
            }
            if($changedSomething==1|| 1)
            {
                $searchResultFromQuery = array();
                $includeListArray = explode(',',$newIncludeList);
                $searchResultsOnTop=array();
                foreach($includeListArray as $value)
                {
                    $searchResultTemp = $ListingClientObj->documentApiSearch($appId,'question',$value);
                    if(isset($searchResultTemp['results'][0]))
                    {
                        array_push($searchResultsOnTop,$searchResultTemp['results'][0]);
                    }
                }
                $filterList = implode(' ',array_merge(explode(',',$IncludeList),explode(',',$ExcludeList)));
                $appId = 12;
                $keyword = $newQueryString;
                $start =0;
                $rows = 10;
                $listingType = 'relatedData';
                $categoryId = "";
                $searchResultFromQuery = $ListingClientObj->shikshaApiSearch($appId,$keyword,$start,$rows,$listingType,$relaxFlag,$categoryId,$filterList);
                $outputData['numOfResults'] = $searchResultFromQuery['numOfRecords'];
                $outputData['keyword']= $keyword;
                $outputData['start']= $start;
                $outputData['rows']= $rows; 
                $outputData['listingType']= 'relatedData'; 
                $outputData['outputType']= "json";
                $outputData['resultList'] = $searchResultFromQuery['results'];
                for($i=0;$i<count($searchResultsOnTop);$i++)
                {
                    array_unshift($outputData['resultList'],$searchResultsOnTop[$i]);
                }
                $out = $RelatedClient->insertUpdateRelatedQuestion($productType,$productId,"ask",base64_encode(json_encode($outputData)),$keyword);
            }
            /*echo "<pre>";
            print_r($searchResult);
            echo "</pre>";*/
            echo "<script>alert('Your Changes have been updated');window.close();</script>";
        }
        else
        {
            echo "<script>alert('This listing does not exist or has been deleted');window.close();</script>";
        }

    }

    function getRelatedSearchQuery($productId,$productType,$relatedProductType="ask")
    {
            $this->init();
            $RelatedClient = new RelatedClient();
            $relatedQuestionQuery = $RelatedClient->getQueryStringForRelatedQuestion($productId , $productType);
            echo $relatedQuestionQuery;
    }
}
?>
