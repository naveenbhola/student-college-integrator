<?php
/**
 * This library will be used across the site to generate a guide for a exam
 * @package     Exam page
 * @author      Yamini Bisht
 *
 */

class examGuideGenerator {
    private $SHIKSHA_FOLDER = "/var/www/html/shiksha";
    private $GUIDE_FOLDER = "/var/www/html/shiksha/mediadata/examGuides/";
    function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->builder('ExamBuilder','examPages');
        $examBuilder          = new ExamBuilder();
        $this->examRepository     = $examBuilder->getExamRepository();
        $this->ExamPageCache = $this->CI->load->library('examPages/cache/ExamPageCache');
        $this->exammodel = $this->CI->load->model('examPages/exammodel');
        $this->examCache = $this->CI->load->library('examPages/cache/ExamCache');
    }

    function generateGuide($guideData) {
        $showHtml = true;
        if($_REQUEST['showHtml'] == 1) {
            $showHtml = null;
        }
        $displayData = array();
    
        if($guideData['examContentObj'] && $guideData['examBasicObj']) {
            $displayData['examBasicObj'] = $guideData['examBasicObj'];
            $displayData['examContentObj'] = $guideData['examContentObj'];
            $displayData['groupId'] = $guideData['groupId'];
            $displayData['examId'] = $guideData['examId'];
            $this->prepareExamGuideData($displayData);
        }
        if(empty($displayData)) {
            return array('status' => 'error','msg' => 'Could not populate data for group id: '.$displayData['groupId']);
        }

        $contentHtml = $this->CI->load->view('examPages/newExam/examGuideOverview', $displayData, $showHtml);

        // Generating HTML file which will be finally converted into the PDF..
        $contentFileServerPath = $this->SHIKSHA_FOLDER."/public/examGuideTemplates/exam_guide.html";
        $contentFileWebPath = SHIKSHA_HOME."/public/examGuideTemplates/exam_guide.html";
        $this->writeInFile($contentFileServerPath, $contentHtml);
        
        // Generating footer html
        $footerServerPath = $this->SHIKSHA_FOLDER."/public/examGuideTemplates/footer.html";
        $footerData['examName'] = $displayData['examBasicObj']->getName();
        $footerData['createdDate'] = date('d-M-Y', time());
        $footer_html_data = $this->CI->load->view("examPages/newExam/guideWidgets/guideFooter", $footerData, $showHtml);
        if(!$showHtml) {
            return array();
        }

        $footer_file_template = $this->SHIKSHA_FOLDER."/public/examGuideTemplates/footer.html";
        $this->writeInFile($footer_file_template, $footer_html_data);
        // Naming the pdf Brochure here..
        $current_date = date('Y-m-d-H-i', time());
        $examGuideName = "examGuide".$displayData['groupId']."_".$current_date.".pdf";
        $pdfServerPath = $this->GUIDE_FOLDER.$examGuideName;
        //converting html to brochure
        $command = '/usr/local/bin/wkhtmltopdf';
        if(ENVIRONMENT == 'development') {
            $command = '/usr/bin/wkhtmltopdf';
        }
        exec($command." --margin-left 0 --margin-right 0 --margin-top 20 --margin-bottom 25 --footer-html $footerServerPath $contentFileServerPath $pdfServerPath ", $shellOutput, $shellReturn);
        
        if($shellReturn != 0) {
            error_log("\n".'Some error exists for group  '.$displayData['groupId'] .'with error code '. $shellReturn,3,"/tmp/examAutogenerateGuide.log");
        }
        echo "$command --margin-left 0 --margin-right 0 --margin-top 20 --margin-bottom 30 --footer-html $footerServerPath $contentFileServerPath $pdfServerPath";
        
        unlink($contentFileServerPath);
        unlink($footer_file_template);

        $guideUrl = MEDIA_SERVER."/mediadata/examGuides/".$examGuideName;
        
        // fetching content from generated pdf
        $fileContent['FILE_CONTENT'] = base64_encode(gzcompress(file_get_contents($pdfServerPath)));
        $fileContent['BROCHURE_NAME'] = $examGuideName;
        $fileContent['guide_url'] = $guideUrl;
        $fileContent['guide_size'] = filesize($pdfServerPath);
        $fileContent['guide_year'] = $displayData['groupYear'];
        $fileContent['status'] = 'live';
        $fileContent['userId'] = 1;
        $fileContent['creation_date'] = date('Y-m-d H:i:s');

         // User id of 'edy@shiksha.com' to indicate this update has been done by the Guide Auto Generate Script.
        $fileContent['folderName'] = 'examGuides';
        unlink($pdfServerPath);
        
        $success = $this->uploadFileToServer($fileContent);
        //changing guide absolute url to relative URL
        if($success == 1) {
            //update exampage_guide table    
            $fileContent['examId'] = $displayData['examId'];       
            $fileContent['guide_url'] = "/mediadata/examGuides/".$examGuideName;
            $this->exammodel->updateExamGuide($fileContent, $displayData['groupId']);
            $response_array = array('RESPONSE' => 'Guide_FOUND' , 'Guide_URL' => $guideUrl);              
            return array('status' => 'success','msg' => 'Created successfully for group id: '.$displayData['groupId']);
        } else {
            return array('status' => 'success','msg' => 'Unable to upload file on media server for group id: '.$displayData['groupId']);
        }
    }

    function prepareExamGuideData(&$displayData) {

        if(!empty($displayData['groupId']) && !empty($displayData['examId'])){
            $groupObj  = $this->examRepository->findGroup($displayData['groupId']);
            if(!empty($groupObj) && is_object($groupObj)){
                $mapping   = $groupObj->getEntitiesMappedToGroup();
                $groupYear = $mapping['year'][0];
                $displayData['groupYear'] = $groupYear;
                $displayData['groupName'] = htmlentities($groupObj->getName());
            }
            $this->CI->load->builder('ExamBuilder','examPages');
            $examBuilder          = new ExamBuilder();
            $this->examRepository     = $examBuilder->getExamRepository();
            $examPageLib = $this->CI->load->library('examPages/ExamPageLib');
            //get exam content data
            $this->CI->load->config('examPages/examPageConfig');
           
            $displayData['examName'] = htmlentities($displayData['examBasicObj']->getName());
            $displayData['examFullName'] = htmlentities($displayData['examBasicObj']->getFullName());
            $displayData['examPageUrl'] = $displayData['examBasicObj']->getUrl();
             //get all groups
            $displayData['groupList'] = $displayData['examBasicObj']->getGroupMappedToExam();
            $wikiFields = $this->CI->config->item('wikiFields');
            $sectionNameMapping = $this->CI->config->item('sectionNamesMapping');
            $displayData['examContent'] = $displayData['examContentObj'];

            $displayData['wikiFields'] =  $wikiFields;
            $displayData['sectionNameMapping'] =  $sectionNameMapping;
            $displayData['examSections'] = $displayData['examContent']['sectionname'];
            $displayData['noSnippetSections'] = array('Exam Title', 'Phone Number', 'Official Website');
            $applyOnlineData = $examPageLib->getApplyOnline($displayData['examId'], $displayData['groupId']);

            if(!empty($applyOnlineData)){
                $applyOnlineData['of_creationDate'] = date('M d, Y',strtotime($applyOnlineData['of_creationDate']));
                $applyOnlineData['isInternal'] = $applyOnlineData['isExternal'] ? 0 : $applyOnlineData['isExternal'];
            }
            
            $displayData['applyOnlineData'] = $applyOnlineData;

            $conductedBy = $displayData['examBasicObj']->getConductedBy();
            $conductedByArr = $examPageLib->getConductedBy($conductedBy);
            $displayData['conductedBy'] = is_array($conductedByArr) ? $conductedByArr['conductedBy'] : $conductedBy;
            $displayData['streamCheck'] = $examPageLib->checkToShowExamCal($mapping);

            $examPageLib->prepareContactInfoData($displayData);
            $examPageLib->prepareExamSamplePaperData($displayData);
            $examPageLib->prepareExamImportantDatesData($displayData);
            $examPageLib->prepareAppFormData($displayData);
            $examPageLib->prepareResultData($displayData);
        }   

    }

    function formatDatesData($dates){
        $datesData['past'] = array();
        $datesData['future'] = array();
        //_p(date("Y-m-d"));
        foreach ($dates as $key => $date) {
            if($date->getEndDate() >= date("Y-m-d") || $date->getStartDate() >= date("Y-m-d")){
                $datesData['future'][] = $date;
            }
            else{
                $datesData['past'][] = $date;
            }
        }
        return $datesData;
    }

    function generatePdfForExamSections($Ids){
//        $Ids = array(113=>7);
        if(!is_array($Ids)){
            return ;
        }
        $groupIds = array();
        foreach ($Ids as $groupId => $exampageId) {
            $groupIds[] = $groupId;
        }
        $sectionNames = array('homepage','pattern','syllabus','cutoff','admitcard','answerkey','counselling','slotbooking','results','applicationform','importantdates');
        $entityTypes = array(   "Summary" => "Overview",
                                "Eligibility" => "Eligibility",
                                "Exam Centers" => "Exam Centers",
                                "Exam Analysis" => "Exam Analysis",
                                "admitcard" => "Admit Card",
                                "importantdates" => "Dates",
                                "applicationform" => "Application Form",
                                "counselling" => "Counselling",
                                "cutoff" => "Cut Off",
                                "pattern" => "Pattern",
                                "results" => "Results");
        $groupObjs = $this->examRepository->findMultipleGroup($groupIds);
        $examIds = array();
        foreach ($groupObjs as $groupId => $groupObj) {
            $examIds[] = $groupObj->getExamId();
        }
        $examObjs = $this->examRepository->findMultiple($examIds);
        $failedIds = array();
        foreach ($groupIds as $key1 => $groupId) {
            error_log("Generating PDF for groupId : ".$groupId);
            $status = true;
            $groupObj = $groupObjs[$groupId];
            if (empty($groupObj) || !is_object($groupObj)) {
                continue;
            }
            $contentObject = $this->examRepository->findContentFromAPI($groupId,"all");

            $examObj = $examObjs[$groupObj->getExamId()];
            $guideUrlDatas = array();
            foreach ($sectionNames as $key2 => $value) {
                $guideUrlData = array();
                $wikiData = $contentObject[$value];
                if($value == 'importantdates'){
                    $data = array();
                    unset($wikisList);
                    unset($wiki2);
                    unset($wiki1);
                    $wikisList = $contentObject[$value]['wiki'];
                    foreach ($wikisList as $key => $wikiPart) {
                        if(is_object($wikiPart)){
                            if ($wikiPart->getEntityType() == "importantdates") {
                                $wiki2 = $wikiPart;
                            } elseif ($wikiPart->getEntityType() == "upperWiki") {
                                $wiki1 = $wikiPart;
                            }
                        }
                    }
                    $dates = $contentObject[$value]['date'];
                    if (is_object($wiki2)) {
                        $data['entityValue'] = $wiki2->getEntityValue();
                    }
                    if (is_object($wiki1)) {
                        $data['upperWiki'] = $wiki1->getEntityValue();
                    }
                    $data['examName'] = $examObj->getName();
                    $data['year'] = $groupObj->getEntitiesMappedToGroup()['year'][0];
                    $data['groupId'] = $groupId;
                    $data['sectionName'] = "Dates";
                    $data['examUrl'] = $examObj->getUrl();
                    $data['exampage_id'] = $Ids[$groupId];
                    $data['datesData'] = $this->formatDatesData($dates);
                    $returnData = $this->formatContentAndUpload($data,$examObj);
                    if(!is_array($returnData) || $returnData['status'] != "success"){
                        error_log("Failed For ".$data['sectionName']);
                        $status = false;
                    }
                    else{
                        $guideUrlData['section_name'] = $value;
                        $guideUrlData['entity_type'] = "importantdates";
                        $guideUrlData['cta_url'] = $returnData['url'];
                        $guideUrlData['status'] = 'live';
                        $guideUrlData['page_id'] = $data['exampage_id'];
                        $guideUrlData['creationTime'] = date('Y-m-d H:i:s');
                        $guideUrlDatas[] = $guideUrlData;
                    }
                    continue ;
                }

                if(!is_object($wikiData[0])){
                    $wikiData = $wikiData['wiki'];
                }

                foreach ($wikiData as $key3 => $wiki) {
                    if(!is_object($wiki)){
                         if (is_object($wiki['wiki'])) {
                             $wiki = $wiki['wiki'];
                         }
                         else{
                            continue;
                         }
                    }
                    $wikiEntityType = $wiki->getEntityType();
                    if(!empty($wiki) && isset($entityTypes[$wikiEntityType])){
                        $data = array();
                        $data['entityValue'] = $wiki->getEntityValue();
                        $data['examName'] = $examObj->getName();
                        $data['year'] = $groupObj->getEntitiesMappedToGroup()['year'][0];
                        $data['groupId'] = $groupId;
                        $data['sectionName'] = $entityTypes[$wiki->getEntityType()];
                        $data['examUrl'] = $examObj->getUrl();
                        $data['exampage_id'] = $Ids[$groupId];
                        $returnData = $this->formatContentAndUpload($data,$examObj);
//                        _p($returnData);
                        if(!is_array($returnData) || $returnData['status'] != "success"){
                            error_log("Failed For ".$data['sectionName']);
                            $status = false;
                        }
                        else{
                            $guideUrlData['section_name'] = $value;
                            $guideUrlData['entity_type'] = $wiki->getEntityType();
                            $guideUrlData['cta_url'] = $returnData['url'];
                            $guideUrlData['status'] = 'live';
                            $guideUrlData['page_id'] = $data['exampage_id'];
                            $guideUrlData['creationTime'] = date('Y-m-d H:i:s');
                            $guideUrlDatas[] = $guideUrlData;
                        }
                    }
                }
            }

            if ($status == false) {
                $failedIds[] = $groupId;
            }
            if(!empty($guideUrlDatas)){
                $this->exammodel->updateExamSectionGuideurls($guideUrlDatas, $Ids[$groupId]);
                $this->examCache->deleteCache($groupId,ExamContentKey);
            }

        }
        return $failedIds;

    }

    function formatContentAndUpload(&$data,$examObj){
        $showHtml = true;
        $examName = $data['examName'];
        $examYear = $data['year'];
        $groupId = $data['groupId'];
        $pageId = $data['exampage_id'];

        $coverPdfPath = $this->generatePdfCoverPage($examObj,$examYear);

        $contentHtml = $this->CI->load->view("examPages/examSectionGuide",$data,$showHtml);


        $doc = new DOMDocument();
        $doc->loadHTML(mb_convert_encoding($contentHtml, 'HTML-ENTITIES', 'UTF-8'));

        $iframes = $doc->getElementsByTagName('iframe');
        $iframesCount = $iframes->length;

        if($iframesCount > 0){
            for($i=$iframesCount; $i > 0;){
                --$i;
                $iframeNode = $iframes->item($i);
                $url = $iframeNode->getAttribute("data-original");
                if ($url == ""){
                    $url = $iframeNode->getAttribute("src");
                }
                if($url == ""){
                    continue;
                }
                $anchorLink= $doc->createElement('a', 'Click Here for more details');
                $anchorLink->setAttribute('href', $url);
                $anchorLink->setAttribute('style', "color:#008489");
                $anchorLink->setAttribute('target', "_blank");
                $iframeNode->parentNode->replaceChild($anchorLink, $iframeNode);
            }
        }

        $imageTags = $doc->getElementsByTagName('img');
        $imageCount = $imageTags->length;
        while ($imageCount-- >0) {
            $imageNode = $imageTags->item($imageCount);
            $url = $imageNode->getAttribute("data-original");
            if ($url == ""){
                $url = $imageNode->getAttribute("src");
            }
            if($url == ""){
                continue;
            }
            if(strpos($url, "/mediadata") == 0){
                $url = SHIKSHA_HOME.$url;
                $imageNode->setAttribute("src",$url);
            }
        }

        $dfpClass   = "dfp-tags";
        $docFinder  = new DOMXPath($doc);
        $dfpNodes   = $docFinder->query("//*[contains(concat(' ', normalize-space(@class), ' '), '$dfpClass')]");
        $dfpNodesCount = $dfpNodes->length;
        if($dfpNodesCount > 0){
            for ($i=$dfpNodesCount; $i > 0;){
                --$i;
                $dfpNode = $dfpNodes->item($i);
                $dfpNode->parentNode->removeChild($dfpNode);
            }
        }

        $kbClass    = "kb-tags";
        $docFinder  = new DOMXPath($doc);
        $kbNodes    = $docFinder->query("//*[contains(concat(' ', normalize-space(@class), ' '), '$kbClass')]");
        $kbNodesCount   = $kbNodes->length;
        if($kbNodesCount > 0){
            for ($i=$kbNodesCount; $i > 0;){
                --$i;
                $kbNode = $kbNodes->item($i);
                $kbNode->parentNode->removeChild($kbNode);
            }
        }

        $knowledgeBoxDivClass = "knowledgebox-table";
        $docFinder = new DOMXPath($doc);
        $knowledgeBoxNodes = $docFinder->query('//div[@class="'.$knowledgeBoxDivClass.'"]//table//tbody//tr//td//div//strong//a');
        $knowledgeBoxCount = $knowledgeBoxNodes->length;
        if($knowledgeBoxCount > 0){
            for ($i=$knowledgeBoxCount; $i>0;){
                --$i;
                $anchorNode = $knowledgeBoxNodes->item($i);
                $divNodeHrefAttribute = $anchorNode->getAttribute('href');
                $strposForHttp = strpos($divNodeHrefAttribute,'http');
                if (!$strposForHttp || $strposForHttp > 0){
                    $anchorNode->setAttribute('href',SHIKSHA_HOME.$divNodeHrefAttribute);
                }
            }
        }


        $contentHtml =  preg_replace('/^<!DOCTYPE.+?>/', '', str_replace(array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $doc->saveHTML()));

        // Generating HTML file which will be finally converted into the PDF..
        $contentFileServerPath = $this->SHIKSHA_FOLDER."/public/examGuideTemplates/exam_sectionguide.html";
        $contentFileWebPath = SHIKSHA_HOME."/public/examGuideTemplates/exam_sectionguide.html";
        $this->writeInFile($contentFileServerPath, $contentHtml);
        
        // Generating footer html
        $footerServerPath = $this->SHIKSHA_FOLDER."/public/examGuideTemplates/sectionfooter.html";
        $footerData['url'] = $data['examUrl'];
        $footerData['examName'] = $examName;
        $footerData['createdDate'] = date('d-M-Y', time());
        $footer_html_data = $this->CI->load->view("examPages/sectionGuideFooter", $footerData, $showHtml);
        if(!$showHtml) {
            return array();
        }

        $footer_file_template = $this->SHIKSHA_FOLDER."/public/examGuideTemplates/sectionfooter.html";
        $this->writeInFile($footer_file_template, $footer_html_data);
        // Naming the pdf Brochure here..
        $current_date = date('Y-m-d-H-i', time());
        $examGuideName = "examSectionGuide".$groupId."_".str_replace(' ', '', $data['sectionName'])."_".$current_date.".pdf";
        $pdfServerPath = $this->GUIDE_FOLDER.$examGuideName;
        //converting html to brochure
        $command = '/usr/local/bin/wkhtmltopdf';
        if(ENVIRONMENT == 'development') {
            $command = '/usr/bin/wkhtmltopdf';
        }
//        exec($command." --margin-left 0 --margin-right 0 --margin-top 20 --margin-bottom 25 --footer-html $footerServerPath $contentFileServerPath $pdfServerPath ", $shellOutput, $shellReturn);
        exec("$command --margin-left 0 --margin-right 0 --margin-top 20 --margin-bottom 30 --page-size A5 --footer-html $footerServerPath $contentFileServerPath $pdfServerPath", $shellOutput, $shellReturn);

        echo "</br>".$shellReturn."</br>";
        if($shellReturn != 0) {
            error_log("\n".'Some error exists for group  '.$groupId .'with error code '. $shellReturn,3,"/tmp/examAutogenerateGuide.log");
            return array('status' => 'error','msg' => 'Failed in PDF creation for group id: '.$groupId);
        }
        //echo "$command --margin-left 0 --margin-right 0 --margin-top 20 --margin-bottom 30 --footer-html file://$footerServerPath file://$contentFileServerPath $pdfServerPath";

        echo "$command --margin-left 0 --margin-right 0 --margin-top 20 --margin-bottom 30 --footer-html $footerServerPath $contentFileServerPath $pdfServerPath";

        $mergedPdfServerPath = $this->GUIDE_FOLDER.'merged-'.$examGuideName;

        exec("/usr/local/bin/pdftk $coverPdfPath $pdfServerPath cat output $mergedPdfServerPath");
        //exec("/usr/bin/pdfjoin $coverPdfPath $pdfServerPath --outfile $mergedPdfServerPath");

        unlink($pdfServerPath);

        $pdfServerPath = $mergedPdfServerPath;


        //die;
        unlink($contentFileServerPath);
        unlink($footer_file_template);

        $guideUrl = MEDIA_SERVER."/mediadata/examGuides/".$examGuideName;
        
        // fetching content from generated pdf
        $fileContent['FILE_CONTENT'] = base64_encode(gzcompress(file_get_contents($pdfServerPath)));
        $fileContent['BROCHURE_NAME'] = $examGuideName;
        $fileContent['guide_url'] = $guideUrl;
        $fileContent['guide_size'] = filesize($pdfServerPath);
        $fileContent['guide_year'] = $examYear;
        $fileContent['status'] = 'live';
        $fileContent['userId'] = 1;
        $fileContent['creation_date'] = date('Y-m-d H:i:s');

         // User id of 'edy@shiksha.com' to indicate this update has been done by the Guide Auto Generate Script.
        $fileContent['folderName'] = 'examGuides';
        unlink($pdfServerPath);
        
        $success = $this->uploadFileToServer($fileContent);
        //changing guide absolute url to relative URL
        if($success == 1) {
            //update exampage_guide table    
            $fileContent['examId'] = $displayData['examId'];       
            $fileContent['guide_url'] = "/mediadata/examGuides/".$examGuideName;
            $this->exammodel->updateExamGuide($fileContent, $displayData['groupId']);
            $response_array = array('RESPONSE' => 'Guide_FOUND' , 'Guide_URL' => $guideUrl);              
            return array('status' => 'success','url'=>$fileContent['guide_url'],'msg' => 'Created successfully for group id: '.$groupId);
        } else {
            return array('status' => 'error','msg' => 'Unable to upload file on media server for group id: '.$groupId);
        }

    }

    function generatePdfCoverPage($examObj,$examYear) {
        $displayData = array();
        $displayData['name'] = $examObj->getName();
        $displayData['fullName'] = $examObj->getFullName();
        $displayData['year'] = $examYear;
        $condunctedBy = $examObj->getConductedBy();
        if(is_numeric($condunctedBy)) {
            $this->CI->load->builder("nationalInstitute/InstituteBuilder");
            $instituteBuilder = new InstituteBuilder();
            $this->instituteRepo = $instituteBuilder->getInstituteRepository();
            $instituteObj = $this->instituteRepo->find($condunctedBy,array('basic'));
            if(is_object($instituteObj)){
                $displayData['condunctedBy'] = $instituteObj->getName();
            }
        }else{
            $displayData['condunctedBy'] = $condunctedBy;
        }
        $coverPageHtml = $this->CI->load->view('listingCommon/pdfCoverPage', $displayData, true);
        // echo $coverPageHtml; die;
        $coverPagePath = $this->SHIKSHA_FOLDER."/public/examGuideTemplates/coverPage.html";
        $pdfServerPath = $this->GUIDE_FOLDER.'coverPage.pdf';
        $this->writeInFile($coverPagePath, $coverPageHtml);

        /*$footer_html_data = $this->CI->load->view("listingCommon/pdfCoverPageFooter", null, true);
        $footer_file_template = $this->SHIKSHA_FOLDER."/public/listingsEbrochureTemplates/coverPageFooter.html";
        $this->writeInFile($footer_file_template, $footer_html_data);*/
        // echo "/usr/local/bin/wkhtmltopdf --disable-javascript --margin-left 0 --margin-right 0 --margin-top 0 --margin-bottom 0 --page-size A5 $coverPagePath $pdfServerPath"; die;
        exec("/usr/local/bin/wkhtmltopdf --disable-javascript --margin-left 0 --margin-right 0 --margin-top 0 --margin-bottom 0 --page-size A5  $coverPagePath $pdfServerPath", $shellOutput, $shellReturn); 
        return $pdfServerPath;
    }

    function writeInFile($file, $content) {
        if($file == "") {
            return ;
        }
        $fp = fopen($file, 'w');
        fwrite($fp, $content);
        fclose($fp);
    }

    function uploadFileToServer($fileContent) {
        $serverUrl = SITE_PROTOCOL.MEDIA_SERVER_IP."/mediadata/listingsBrochures/writePDFatMediaServer.php";
        return $this->makeCurlCall($fileContent, $serverUrl);       
    }

    function makeCurlCall($post_array, $url)
    {
        if($url == "") {
            return ("NO_VALID_URL_DEFINED");
        }
        
        $c = curl_init();
            curl_setopt($c, CURLOPT_URL,$url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c,CURLOPT_POSTFIELDS,$post_array);
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($c, CURLOPT_TIMEOUT, 60);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
        $output =  curl_exec($c);
        curl_close($c);
        return $output;
    }
}