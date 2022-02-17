	<?php

class HtmlGenerator{


	public function generateHTML($arrayOfAttributes,$typeOfSelect='single',$pos='separate',$style='verticalList'){

		$html = "<ul class='optionsUl'>";
		foreach ($arrayOfAttributes as $key => $value) {
			$html .= "<li data='".$key."'class='optionsUl-Li'>".$value."</li>";
		}
		$html .= "</ul>";
		return $html;
	}

	public function generateHTMLForBotTextResponse($data){

		$dataType = gettype($data);

		if($dataType == "array"){
			foreach ($data as $key => $value) {
				$html .= "<li class='botResponse'>".$value."</li>";
			}	
		}else if($dataType == "string"){
			$html .= "<li class='botResponse'>".$data."</li>";
		}
		
		return $html;	
	}

}
?>