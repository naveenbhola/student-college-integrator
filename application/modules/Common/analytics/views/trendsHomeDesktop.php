<?php
$headerComponents = array (
        'js' => array (
                'multipleapply',
                'user' 
        ),
        'css' => array('bootstrapv2','analytics'),
        'jsFooter' => array (
                'common',
                'processForm',
                'analytics'
        ),
        'title'           =>  $seoTitle,
        'metaDescription' => $seoDesc,
        'product' => 'shiksha_analytics'
);
$this->load->view ( 'common/header', $headerComponents );
?>
<div class="analytics-container">
    <?php $this->load->view("analytics/homeWidgets/topWidget"); ?>
    <!--Row with two equal columns-->
    <div class="container">
                    <div class="row">
                    <?php 
                        $this->load->view("analytics/homeWidgets/popularUniversities");
                        $this->load->view("analytics/homeWidgets/popularInstitutes");
                    ?>
                    </div>
                    <?php 
                        $this->load->view("analytics/homeWidgets/popularStream");
                        $this->load->view("analytics/homeWidgets/popularCourses");
                    ?>
                    <div class="row">
                        <?php 
                            $this->load->view("analytics/homeWidgets/popularExams");
                            $this->load->view("analytics/homeWidgets/popularSpecialization");
                        ?>
                    </div>
                    <?php
                        $this->load->view("analytics/homeWidgets/trendingQuestions");
                        $this->load->view("analytics/homeWidgets/articles");
                    ?>
                    <p class="disclaimer"><strong>Disclaimer:</strong> Shiksha Trends are calculated using students' behaviour in the last 12 months on various pages of Shiksha website. Scoring is done on a relative scale of 0 to 100, where a value of 100 signifies the most visited topic within a group of topics (eg. Delhi University in All Universities) on Shiksha and a value of 50 signifies a topic half as visited. </p>
    </div>
</div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?php
    $this->load->view ( 'common/footerNew', array (
            'loadJQUERY' => 'YES',
            'loadUpgradedJQUERY' => 'YES'
         
    ) );
?>
    <script type="text/javascript">
        closeSearchLayer();
    </script>