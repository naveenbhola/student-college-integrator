    <script src="/public/js/common.js" type="text/javascript"></script>
    <script src="/public/js/events.js" type="text/javascript"></script>
<script language="javascript" src="/public/js/user.js"></script>	
    <script>
			var SITE_URL = '<?php echo base_url() ."/";?>';
	</script>
	<script>var categoryTreeMain = eval(<?php echo $category_tree; ?>);	</script>
    <?php
        $this->load->view('events/eventSearchPanel');  
        $this->load->view('events/leftSearchPanel');  
        $this->load->view('events/eventRightSearchPanel', $recentEvents); 
        $this->load->view('events/eventAdd');
$url = array(
				'successurl'=> '',
				'successfunction'=>'showAddEvents',
				'id'=>'add',
					
			    );		
$this->load->view('user/userlogin',$url);	
        
        $keyword = isset($keyword) ? $keyword : '';
        $location = isset($location) ? $location : '';
        
		$keyword = ($keyword == "Keywords like MBA, GMAT") ? "" : $keyword;
		$location = ($location == "Location eg. Delhi") ? "" : $location;
        $searchHeading = '';
        if($location !== '' && $keyword != '') {
        	$searchHeading .= $keyword ." - ". $location ." - ";
        }
        if($location !== '' && $keyword === '') {
        	$searchHeading .= $location ." - ";
        }
        if($location === '' && $keyword !== '') {
        	$searchHeading .= $keyword ." - ";
        }
        $searchCriteria = $keyword ."+". $location;
        $numfound = isset($eventsList['numOfRecords']) ? $eventsList['numOfRecords'] : 0;
        $numRecords = ($numfound>0) ? $numfound : 'No ';
        $start = isset($start) ? $start : 0;
        $rows = isset($rows)? $rows : 15;
        $url = site_url('events/Events/searchEventsForPage/1/') ."/" .$searchCriteria .'/';
        $urlWithoutStart = $url . $rows ."/";

    ?>
        <!--Start_Mid_Panel-->
        <div style="margin-left:174px; margin-right:174px;">

		<div id="blogTabContainer">
            <div style="margin-bottom:11px;"><span class="blogheading"><a href="/events/Events/index">Event Calender</a> &gt; </span></div>

                <div>

                    <div class="searchShowing">
                        View: 	<select id="pageViews" name="pageViews" class="selectTxt" onChange="location.replace('<?php echo $url ;?>' + this.value +'<?php echo '/'.$start; ?>')">
                        			<option value="15">15 per page</option>
                        			<option value="20">20 per page</option>
                        			<option value="25">25 per page</option>
                        			<option value="30">30 per page</option>
                        		</select>
                    </div>

                    <div class="searchResultHeading highlightColor"><strong><?php echo $searchHeading; ?>  <?php echo $numRecords; ?> results</strong></div>

                    <div class="lineSpace_5">&nbsp;</div>

                    <div class="grayLine"></div>

                    <div class="lineSpace_11">&nbsp;</div>      

                </div>
				<?php
					if(isset($eventsList['numOfRecords']) && $eventsList['numOfRecords'] >0) {
				?>
                <div class="searchResultHeading">

                    <div id="resultPg1">        

                        <ul>
                           <?php echo $this->miscelleneous->paginateTab($urlWithoutStart,$numfound,$start,$rows); ?>            
                        </ul>

                    </div>

                    <div class="lineSpace_11">&nbsp;</div>

                    <div class="grayLine"></div>        

                </div>

                <div class="lineSpace_11">&nbsp;</div>
                <?php
                	if(isset($eventsList['results']) && is_array($eventsList['results'])) {
	                	foreach($eventsList['results'] as $event) {
	                		$eventId 			= isset($event['Id']) ? $event['Id'] : '';
	                		$eventTitle 		= isset($event['title']) ? $event['title'] : '';
	                		$eventDate 			= isset($event['date']) ? $event['date'] : '';
	                		$eventVenue 		= isset($event['venue']) ? $event['venue'] : '';
	                		$eventCity 			= isset($event['city']) ? $event['city'] : '';
	                		$eventDescription 	= isset($event['shortContent']) ? $event['shortContent'] : '';
                ?>
<div>
                <div class="searchTxt">

                        <div class="searchShowing1"><a href="eventDetail/1/<?php echo $eventId;?>"><?php echo $eventTitle; ?></a></div>

                </div>

                <div class="resDescription">
                    <strong>Date:</strong> <?php echo $eventDate; ?><br />

                    <strong>Venue:</strong> <?php echo $eventVenue; ?><br />

                    <strong>City:</strong> <?php echo $eventCity; ?><br />
                    
					<?php echo $eventDescription; ?> [<a href="eventDetail/1/<?php echo $eventId; ?>">more</a>]
                </div>

                <div class="lineSpace_11">&nbsp;</div>

                <div class="grayLine"></div>

                <div class="lineSpace_11">&nbsp;</div>      
</div>
				<?php
						}
					} else {
				?>
				<div class="searchTxt">
                    <div class="searchShowing1">No Results Found</div>
                </div>
				<?php
					}
				?>
                <div class="searchResultHeading">

                    <div id="resultPg1">        

                        <ul>

                            <?php echo $this->miscelleneous->paginateTab($urlWithoutStart,$numfound,$start,$rows, $searchCriteria); ?>

                        </ul>

                    </div>

                    <div class="lineSpace_11">&nbsp;</div>      

                </div>
			<?php
				}
			?>
            </div>

        </div>

        <!--End_Mid_Panel-->
        <script src="/public/js/common.js" type="text/javascript"></script>     
        <script src="/public/js/prototype.js" type="text/javascript"></script>
        <script>
        	selectComboBox(document.getElementById('pageViews'), <?php echo $rows; ?>);
        </script>
        <?php
            $this->load->view('common/footer'); 
        ?> 
