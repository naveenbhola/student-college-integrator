<?php
                $headerComponents = array(
                                                                'css'   =>      array('headerCms','raised_all','header','mainStyle','footer'),
                                                                'js'    =>      array('blog','common','md5','user','prototype','payment','utils','tooltip'),
                                                                'title' =>      'E-Shop',
								'taburl' =>  site_url(''),
                                                                'metaKeywords'  =>'Some Meta Keywords',
                                                                'category_id'   => (isset($CategoryId)?$CategoryId:1),
                                                                'country_id'    => (isset($country_id)?$country_id:2),
                                                                'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
                                                                'callShiksha'=>1
                                                        );
                //$this->load->view('common/header', $headerComponents);

        //$this->load->view('payment/paymentHeader');
        $this->load->view('enterprise/headerCMS',$headerComponents);
        //print_r ($productDataList);
        if(isset($validateuser[0]['displayname'])){
            $this->load->view('enterprise/cmsTabs');
        }

        $this->load->view('payment/paymentTabs');
        $this->load->view('payment/productFirst',array("productDataList"=>$productDataList,"logged"=>$logged));
        echo '<div id="Page2Container"></div>';
        echo '<div id="Page3Container"></div>';
        echo '<div id="Page4Container"></div>';
        echo '<div id="Page5Container"></div>';
        echo '</div>
            <!--End_Mid_Panel-->
            ';
            //$this->load->view('common/footer');

        //echo '
        //<a href="#" onclick="change(1)">1</a>
        //<a href="#" onclick="change(2)">2</a>
        //<a href="#" onclick="change(3)">3</a>
        //<a href="#" onclick="change(4)">4</a>';

        //$logged = "No";
        error_log_shiksha("LOGGGGGGEEEEEEEEEEEDDDDDDDDDDDDDDDDDD = ".$logged);

        echo '<script>

        //Code for if the person is logged in...';
        if($logged == "Yes") {
            echo '
            document.getElementById("loginTab").style.display = "none";
            document.getElementById("span4").innerHTML = "3";';
        }


        echo '
        var prev_id = 1;
            function activate(id) {
                if(id == 1) {
                var toadyObj = document.getElementById("tab"+id+"Center");
                toadyObj.className = toadyObj.className.replace("ptab_bggray", "ptab_bgwhite");
                    document.getElementById("tab1Leftimg").src="/public/images/ptab_left.gif";
                    document.getElementById("tab12img").src="/public/images/ptab_leftwg.gif";
                    prev_id = 1;
                }
                if(id == 2) {
                var toadyObj = document.getElementById("tab"+id+"Center");
                toadyObj.className = toadyObj.className.replace("ptab_bggray", "ptab_bgwhite");
                    document.getElementById("tab12img").src="/public/images/ptab_leftgwn.gif";';
                    if($logged == "Yes") {
                        echo 'document.getElementById("tab34img").src="/public/images/ptab_leftwg.gif";';
                    }else {
                        echo 'document.getElementById("tab23img").src="/public/images/ptab_leftwg.gif";';
                    }
                    echo '
                    prev_id = 2;
                }
                if(id == 3) {
                var toadyObj = document.getElementById("tab"+id+"Center");
                toadyObj.className = toadyObj.className.replace("ptab_bggray", "ptab_bgwhite");
                    document.getElementById("tab23img").src="/public/images/ptab_leftgwn.gif";
                    document.getElementById("tab34img").src="/public/images/ptab_leftwg.gif";
                    prev_id = 3;
                }
                if(id == 4) {
                var toadyObj = document.getElementById("tab"+id+"Center");
                toadyObj.className = toadyObj.className.replace("ptab_bggray", "ptab_bgwhite");
                    document.getElementById("tab34img").src="/public/images/ptab_leftgwn.gif";
                    document.getElementById("tab4Rightimg").src="/public/images/ptab_right.gif";
                    prev_id = 4;
                }
                if(id == 5) {
                var toadyObj = document.getElementById("tab"+(id-1)+"Center");
                toadyObj.className = toadyObj.className.replace("ptab_bggray", "ptab_bgwhite");
                    document.getElementById("tab34img").src="/public/images/ptab_leftgwn.gif";
                    document.getElementById("tab4Rightimg").src="/public/images/ptab_right.gif";
                    prev_id = 5;
                }

            }

            </script>';

?>
<script>
	var SITE_URL = '<?php echo base_url() ."/";?>';
</script>
<?php $this->load->view('enterprise/footer');?>