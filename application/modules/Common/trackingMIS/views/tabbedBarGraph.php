<div class="col-md-12 col-sm-12 col-xs-12 cropper-hidden">
    <div class="x_panel" id="trafficSplitBarGraph">
        <div class="x_title">
            <h2><i class="fa fa-bars"></i> 
            <?php  if($title){
                echo $title;
                }else{
                        echo 'Traffic Sources';
                    }   ?>

            </h2>
            <?php  if(!$title){?>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
            </ul>
            <?php }?>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <div class="clear"></div>
            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                <ul id="sourceTabList" class="nav nav-tabs bar_tabs" role="tablist"></ul>
                <div id="myTabContent" class="tab-content">
                    <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">

                    </div>
                </div>
                <div class="loader_small_overlay cropper-hidden"><img src="<?php echo SHIKSHA_HOME; ?>/public/images/trackingMIS/mis-loading-small.gif"></div>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var sourceTabActive = '<?php echo $sourceTabActive;?>';
    $('#' + sourceTabActive).parent().attr('class', 'active');
    $('#sourceTabList li').on('click', function () {
        updateBarGraph($(this).find('a').attr('id'));
    });
</script>