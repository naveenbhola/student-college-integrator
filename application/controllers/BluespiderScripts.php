<?php
class BluespiderScripts extends MX_Controller {

    function uploadBucketMappingData($listingType){
        switch ($listingType) {
            case 'domesticinstituteuniversity':
                $this->uploadBucketMappingDataUtility(1,2,1950,array('institute','university'));
                break;
            case 'sauniversity':
                $this->uploadBucketMappingDataUtility(0,2,1636,array('sa-university'));
                break;
            default:
                # code...
                break;
        }
    }

    private function uploadBucketMappingDataUtility($sheet,$lineStart,$lineEnd,$listingTypes){
        
        ini_set('memory_limit',-1);
        $file_name = "/tmp/SBS_allocation2.xlsx";
        $this->load->library('common/PHPExcel');
        $objReader= PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        
        $objPHPExcel=$objReader->load($file_name);
        $objWorksheet=$objPHPExcel->setActiveSheetIndex($sheet);
        
        $data=[];
        $instituteIds=array();
        for ($i=$lineStart;$i<=$lineEnd;$i++) {
            $instituteId = utf8_encode($objWorksheet->getCellByColumnAndRow(0,$i)->getValue());
            $userid = utf8_encode($objWorksheet->getCellByColumnAndRow(3,$i)->getValue());
            
            // $institute_name = utf8_encode($objWorksheet->getCellByColumnAndRow(1,$i)->getValue());
            // $user_name = utf8_encode($objWorksheet->getCellByColumnAndRow(2,$i)->getValue());
            
            if(empty($instituteId) || empty($userid)){
                _p('Data error'.':'.$i.":".$instituteId.":".$userid);
            }
            else{
                if(!array_key_exists($instituteId, $instituteIds)){
                    $data[]=array('instituteId'=>$instituteId,'userId'=>$userid);
                    $instituteIds[$instituteId]=$userid;
                }
                elseif($instituteIds[$instituteId]!=$userid){
                    $data[]=array('instituteId'=>$instituteId,'userId'=>$userid);
                    $instituteIds[$instituteId]=$userid;
                }
                else{
                    _p('Reapeated institutes'.':'.$i.":".$instituteId.":".$userid.':'.$instituteIds[$instituteId]);
                }
            }
        }
        $bluespiderModel = $this->load->model('bluespiderscriptsmodel');
        $criteriaBucketId = $bluespiderModel->getBucketsByListings(array_keys($instituteIds),$listingTypes);
        
        $bucketMapping=array();
        foreach ($data as $mapping) {
            if(array_key_exists($mapping['instituteId'], $criteriaBucketId)){
                $bucketMapping[]=array('bucketId'=>$criteriaBucketId[$mapping['instituteId']],'userId'=>$mapping['userId']);
            }
            else{
                _p('ListingId not in Buckets:'.$mapping['instituteId']);
            }
        }

        $flag = $bluespiderModel->insertBucketMapping($bucketMapping);
        _p($flag);
    }

} ?>
