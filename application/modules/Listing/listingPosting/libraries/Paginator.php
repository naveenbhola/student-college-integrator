<?php 

class Paginator {
	

	private $totalNoOfResults;
	private $currentPage;
	private $noOfLinksPerSection = 6;
	private $URL ;
	private $noOfResultPerPage;
	private $noOfPages;
	private $noOfPaginationSection;
	private $currentPaginationSection;
	private $enablePagination = false;
	private $defaultSelectMenuOption = 20;
	private $selectMenuOptions = array(20,50,100);
	private $orignalURL;
	static  $CI;
        
        public function setRecordsPerPage($pageSize) {
            $this->noOfResultPerPage = $pageSize;
        }
	
	public function setTotalRowCount($totalNoOfResults)
	{
		
       
		if(!empty($totalNoOfResults) && $totalNoOfResults > 0 && !empty($this->URL))
		{
			$this->enablePagination = true;
			$this->totalNoOfResults = $totalNoOfResults;
			$this->noOfResultPerPage = empty($this->noOfResultPerPage) || !in_array($this->noOfResultPerPage,$this->selectMenuOptions) ? $this->defaultSelectMenuOption : $this->noOfResultPerPage;
			$this->noOfPages = ceil($this->totalNoOfResults/$this->noOfResultPerPage);
			$this->noOfPaginationSection = ceil(($this->noOfPages)/($this->noOfLinksPerSection));
			$this->noOfPaginationSection = $this->noOfPaginationSection == 0 ? 1 : $this->noOfPaginationSection;
			if(empty($this->currentPage) || $this->currentPage <= 0 || $this->currentPage > $this->noOfPages)
			{
			 	Header( "HTTP/1.1 301 Moved Permanently" );
				Header( "Location: $this->orignalURL");
				exit();
			}	
			$this->currentPaginationSection = ceil(($this->currentPage)/($this->noOfLinksPerSection));
			$this->URL = strpos($this->URL,'?') ? $this->URL."&resultPerPage=".$this->noOfResultPerPage : $this->URL."?resultPerPage=".$this->noOfResultPerPage;
		
		}
		
	}

	
	public function __construct($URL,$noOfLinksPerSection = 6,$defaultSelectMenuOption = 20,$selectMenuOptions = array(20,50,100))
	{   $this->CI =& get_instance();
		global $clientIP;
		$this->URL = 'https://'.$clientIP.$URL;
		$this->orignalURL = $URL;
		$this->noOfLinksPerSection = (!empty($noOfLinksPerSection) && $noOfLinksPerSection != 0) ? $noOfLinksPerSection : 1;
		$this->defaultSelectMenuOption = $defaultSelectMenuOption;
		$this->selectMenuOptions = $selectMenuOptions;
		$this->currentPage = $this->CI->input->get('pageNo');
		$this->currentPage = is_numeric($this->currentPage) || !empty($this->currentPage) || $this->currentPage != 0 ? intval($this->currentPage) : 1;
		$this->noOfResultPerPage = is_numeric($this->CI->input->get('resultPerPage'))  || $this->CI->input->get('resultPerPage') !=0 ? intval($this->CI->input->get('resultPerPage')) : $this->defaultSelectMenuOption;
	    $this->noOfResultPerPage = in_array($this->noOfResultPerPage, $this->selectMenuOptions) ? $this->noOfResultPerPage : $this->defaultSelectMenuOption;
	}
	
	
	
	public function getLimitOffset()
	{
	     return ($this->currentPage - 1)*($this->noOfResultPerPage);                         
	}
	public function getLimitRowCount()
	{
		return $this->noOfResultPerPage;
	}
	public function printData()
	{
		var_dump("no of pages".$this->noOfPages);
		echo"<BR>";
		var_dump("current Page".$this->currentPage);
		echo"<BR>";
		var_dump("result per page".$this->noOfResultPerPage);
		echo"<BR>";
		var_dump("Pagination Section ".$this->currentPaginationSection);
		echo "<BR>";
		var_dump("total no pagination section :".$this->noOfPaginationSection);
		echo "<BR>";
		
		var_dump("Limit Offset :".$this->getLimitOffset());
		echo var_dump("Limit row count  :".$this->getLimitRowCount());
	}

	public function generateShowingRecords(){
		if($this->totalNoOfResults > 0){
			$startPage = ($this->currentPage - 1)*$this->noOfResultPerPage + 1;
			$endPage   = $startPage + $this->noOfResultPerPage - 1;
			if($endPage > $this->totalNoOfResults){
				$endPage = $this->totalNoOfResults;
			}
			return '<div style="float:left;margin:6px 12px 0 0;color:#333;">Showing '.$startPage.' to '.$endPage.' of '.$this->totalNoOfResults.' entries</div>';
		}
		return '';
	}
	
	public function isPaginationPossible()
	{
		return $this->enablePagination;
	}
	
	public function updateURLForResultPerPage($resultPerPage)
	{
		return strpos($this->orignalURL,'?') ? $this->orignalURL."&resultPerPage=".$resultPerPage : $this->orignalURL."?resultPerPage=".$resultPerPage;
	} 
	public function generateMenuForResultPerPage(){
      
		foreach($this->selectMenuOptions as $option)
		{
			echo $option == $this->noOfResultPerPage ? "<option value='".$this->updateURLForResultPerPage($option)."' selected >".$option." rows</option>" : "<option value='".$this->updateURLForResultPerPage($option)."'>".$option." rows</option>";
		}
	}
	public function getURL($pageNo) {
		
		return $this->URL."&pageNo=".$pageNo;
		
	}
	
	public function generatePreviousButton() {
		
		echo $this->currentPaginationSection == 1 ? "<li class='prev-arr'><span class='icon-arrow' style='cursor:default'>&#9668;</span> Previous</li>" : "<li class='prev-arr'><a style='text-decoration:none' href='".$this->URL."&pageNo=".(($this->noOfLinksPerSection)*($this->currentPaginationSection-1))."'><span class='icon-arrow-active'>&#9668;</span> Previous</a></li>";

	 }
	
	public function generateNextButton() {
		
		echo $this->currentPaginationSection == $this->noOfPaginationSection ? 	 "<li class='next-arr'> Next<span class='icon-arrow' style='cursor:default'>&#9658;</span></li>" : "<li class='next-arr'><a style='text-decoration:none' href='".$this->URL."&pageNo=".(($this->noOfLinksPerSection)*($this->currentPaginationSection)+1)."'> Next<span class='icon-arrow-active'>&#9658;</span></a></li>";
      }
	
	public function generateURLsForBottomSection(){
		
		$this->generatePreviousButton(); //Generate Previous Button
		//Generate Middle Section
		for($i = ($this->currentPaginationSection-1)*($this->noOfLinksPerSection)+1;($i <= ($this->noOfLinksPerSection)*($this->currentPaginationSection)) && ($i <= $this->noOfPages); $i++){
			
			echo $i==$this->currentPage ? "<li class='active'>".$i."</li>" : "<li><a href='".$this->getURL($i)."'>".$i."</a></li>";
		 }
		 $this->generateNextButton(); // Generate Next Button
		
	}
	
	
}

?>
