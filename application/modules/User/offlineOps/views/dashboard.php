<!DOCTYPE html>
<!--
    A InfoEdge Limited Property
    ---------------------------
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Fresh Responses</title>
        <?php 
            $headerComponents = array(
                'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style', 'offlineResponses', 'registration', 'studyAbroadCommon'),
                'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog','ajax-api','ana_common'),//pragya
                'displayname'=> (isset($displayname)?$displayname:""),
                'tabName'   =>  '',
                'taburl' => site_url('offlineOps/index'),
                'metaKeywords'  =>''
                );
            $this->load->view('enterprise/headerCMS', $headerComponents);
        ?>
    </head>
    <body>
        <div id="content-child-wrap">
            <div id="smart-content">
                <h2>Welcome <strong><?php echo trim($displayname); ?> </strong></h2>
            </div><br>
            <br>
        <?php
            $this->load->view('offlineOps/responsesListView');
        ?>
        <?php
            $this->load->view('enterprise/footer');
        ?>
        </div>
        <script>
            var documentHeight = $j(document).height();
        </script>
        <div style="display: none;"><?php echo 'Memory Used: '.$this->benchmark->memory_usage(); ?></div>
    </body>
</html>
