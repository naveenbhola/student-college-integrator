<div class="right_col clearfix" role="main" style="<?php echo ($skipNavigationSASales?'margin-left:0px;':''); ?>">          
    <!-- top tiles -->
    <div class="row x_title">
        <div class="col-md-3 pull-left form-group">
          <input type="text" id="universityId" class="form-control" placeholder="Enter University ID">
        </div>
        <div class="col-md-2 pull-left form-group"> 
            <select class="form-control" name="courseType" id="courseType"> 
                <option value="all">All Courses</option> 
                <option value="paid">Paid Courses</option> 
                <option value="free">Free Courses</option> 
            </select> 
        </div>            
        <div class="col-md-2 pull-right">
            <button type="button" id="submit" class="btn btn-primary">Apply Filters</button>
        </div>
        
        <div class="col-md-4 pull-right">
            <div class="row">
                <button id="reportrange" class="btn btn-default col-md-11 col-sm-11 col-xs-11 white_space_normal_overwrite"
                        style="background: #fff;"><b class="caret margin-right-2"></b>
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                    <span></span>
                </button>
            </div>
        </div>
    </div>
    <div id="univErrorMsg" class="alert alert-danger" style="display: none;"></div>
    <div id="univerDetails" class="col-md-12 row" style="display: none;">
        <div style="float: left;" class="pull-left">
            <img id="univLogo" src="">
        </div>
        <div style="float: left;" class="col-md-7 pull-left">
            <div><h2 id="univName" style="float: none !important;"></h2></div>
            <div><label class="">Location: </label><span id="univLocation"></span></div>
            <div><label class="">Subscription: </label><span id="subscriptionDate"></span></div>
            <div><label class="">Activation: </label><span id="activationDate"></span></div>
        </div>
    </div>
<?php $this->load->view('trackingMIS/saSales/widgets/trafficLineChart'); ?>
<?php $this->load->view('trackingMIS/saSales/widgets/responseLineChart'); ?>
<?php $this->load->view('trackingMIS/saSales/widgets/popularCourses'); ?>
<?php $this->load->view('trackingMIS/saSales/widgets/comparedUniversities'); ?>
<?php $this->load->view('trackingMIS/saSales/widgets/topCitiesMap'); ?>
<?php $this->load->view('trackingMIS/saSales/widgets/rmcWithExamStudents'); ?>
<?php $this->load->view('trackingMIS/saSales/widgets/shortlistToApplicationBar'); ?>
<?php $this->load->view('trackingMIS/saSales/widgets/applicationProcess'); ?>
<?php $this->load->view('trackingMIS/saSales/widgets/appliedStudent'); ?>

</div>
