	<li>
		    <div class="details">
			<p class="criteria-icn-box"><i class="sprite-bg degree-icon"></i></p>
			<p class="title-txt2">Exam Required</p>
			<p class="label-sep">-</p>
			<div class="criteria-content exam-req">
			<?php 
			 $count = 1;
			 $marksFormat = array(
			 	'percentile' => "(%tile)",
			 	'percentage' => "(%tage)",
			 	'total_marks' => "(marks)",
			 	'rank'        =>"(rank)"
			 );
			 $exampage = new ExamPageRequest;
			 foreach($courseExamDetails as $examDetail) {
			 	$exampage->setExamName($examDetail->getAcronym());
				$url 		= $exampage->getUrl();
				if(!in_array($exampage->getExamName(), $liveExamPages)){
					$url = "";
				}
				$exampage->reset();
				$examAcronym = $examDetail->getAcronym();
				if(!empty($url['url'])){
					$examAcronym = "<a href='".$url['url']."'>".$examAcronym."</a>";
				}
				if($abroadExamData!=false && is_array($abroadExamData) && array_key_exists($examDetail->getAcronym(), $abroadExamData)){
					$examAcronym = "<a target='blank' href='".$abroadExamData[$examDetail->getAcronym()]['contentURL']."'>".$examDetail->getAcronym()."</a>";
				}

				$examMarks 	= $examDetail->getMarks() ? $examDetail->getMarks()." </big>" : "";
				$examMarks 	= $examMarks.($examMarks ? $marksFormat[strtolower($examDetail->getMarksType())] : "");
			    $examDisplayString = "<big>".$examAcronym." ".($examMarks ? $examMarks : "  </big>").(count($courseExamDetails)!=$count ? " <span class='sep-color'>|</span> " : "");
			    echo $examDisplayString;
			    $count++;	
			 }
			 ?>
			</div>
		    </div>
	</li>
