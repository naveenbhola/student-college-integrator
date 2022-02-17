<?php
class URLRedirections extends MX_Controller {

    function uploadCSV(){
        
        $file_name = "/var/www/html/shiksha/public/urlredirection_csv/redirections.csv";

        $fp1 = fopen($file_name,"r");
        $fileContent = array();
        $increment = 0;
        // load file1 in array
        while (($data = fgetcsv($fp1)) !== FALSE) {
            $increment++;
            // dont store header text
            if($increment == 1)
                continue;

            $arr = array();
            if(!empty($data[0]) && !empty($data[1])){
                $arr['from'] = $this->sanitizeUrl($data[0]);
                $arr['to'] = $this->sanitizeUrl($data[1]);
            }
            $fileContent[] = $arr;

        }

        // _p($fileContent);die;
        $this->checkNonFunctionalLinks($fileContent);
        
        $this->urlredirectmodel = $this->load->model("urlredirectmodel");
        $this->urlredirectmodel->insertUrlRedirections($fileContent);

        _p("Following data inserted into table : ");
        _p($fileContent);
    }

    function checkNonFunctionalLinks($list){

        foreach ($list as $value) {

            $fromStatusCode = $this->getStatusCode($value['from']);
            if(in_array($fromStatusCode, array(301, 302))){
                    _p("Problem : From Url = ".$value['from']." has ".$fromStatusCode." status code.");
                    die("1");
            }

            $toStatusCode = $this->getStatusCode($value['to']);
            if(!in_array($toStatusCode, array(200))){
                    _p("Problem : To Url = ".$value['to']." is not 200 OK and has ".$toStatusCode." status code.");
                    die("2");
            }

            _p("Done");
        }
    }

    private function getStatusCode($url){

        if(ENVIRONMENT != 'production')
            $url = "https://www.shiksha.com".$url;
        else 
            $url = SHIKSHA_HOME.$url;
        $handle = curl_init($url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
        /* Get the HTML or whatever is linked in $url. */
        $response = curl_exec($handle);

        /* Check for 404 (file not found). */
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        
        curl_close($handle);

        return $httpCode;
    }

    function prepareRedirectionRules(){

        $this->urlredirectmodel = $this->load->model("urlredirectmodel");

        $mappings = $this->urlredirectmodel->getRedirectionMapping();

        foreach ($mappings as $key => $value) {
            $key = str_replace("/articles/", "/articles/(amp/)?", $key);
            $key = str_replace("/college/", "/college/(amp/)?", $key);
            $key = str_replace("/course/", "/course/(amp/)?", $key);
            $rule = "rewrite ^".$key."$ ".$value." permanent;";
            _p($rule);
        }
    }

    private function sanitizeUrl($url){

        $url = str_replace("https://www.shiksha.com", "", $url);
        $url = str_replace(SHIKSHA_HOME, "", $url);

        return $url;
    }

} ?>
