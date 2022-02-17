<?php
class GeneralSorter {
	
    public $key;
	public $sortOrder = 'asc';
	
	function __construct($key, $sortOrder = 'asc') {
        $this->key = $key;
		$this->sortOrder = $sortOrder;
    }
    
    function compareMarks($a, $b) {
        $key = $this->key;
		$examsA = $a->getExams();
		$examsB = $b->getExams();
		$examValueA = 0;
		$examValueB = 0;
		foreach($examsA as $exam){
			if($exam['id'] == $this->key){
				$examValueA = $exam['marks'];
			}
		}
		foreach($examsB as $exam){
			if($exam['id'] == $this->key){
				$examValueB = $exam['marks'];
			}
		}
		if((int)$examValueA == (int)$examValueB) {
			return (int)$a->getRank() - (int)$b->getRank();
		} else {
			if($this->sortOrder == "asc"){
				if($examValueA != "" && $examValueB != ""){
					return (int)$examValueA - (int)$examValueB;
				} else if($examValueA == "" && $examValueB != "") {
					return 1;
				} else if($examValueA != "" && $examValueB == ""){
					return -1;
				}
			} else if($this->sortOrder == "desc"){
				return (((int)$examValueB > (int)$examValueA) ? 1 : -1);
			}
		}
	}
}
