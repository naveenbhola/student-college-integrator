<div id="details-col" style="position:relative;">
		<?php
		if($institutes)
		{
				//disabling coach marks.
				if(false && $_COOKIE['coach-marks'] != "1" && $request->getCityId() != 1 && $request->getCountryId() == 2){
						$this->load->view('categoryList/RNR/coach-marks');
				}
				$this->load->view('categoryList/RNR/page_result_header_bar'); 
				$this->load->view('categoryList/RNR/tuple_list');
				$this->load->view('categoryList/RNR/pagination');
		}
		?>
</div>
