<?php 

/**
 * Class: mmp_template_uploader
 * @author pankaj meena pankaj.meena@shiksha.com
 * Handles all stuff related to create/delete zip files, moving of mmp folders within different directory.
 */

class mmp_template_uploader {
	private $_ci;
	private $MMP_LIVE_BACKUP_DIR;
	private $MMP_LIVE_DIR;
	private $MMP_SANDBOX_DIR;
	private $SHOW_DEBUG_MESSAGES = false;
	
	function __construct() {
		$this->_ci = & get_instance();
		$this->_ci->load->library('xmlrpc');
		
		//MMP SANDBOX DIRECTORY PATH
		$this->MMP_SANDBOX_DIR = FCPATH . 'public/mmp/sandbox/';
		$this->MMP_SANDBOX_DIR_ACTUAL = '/data/mmp/sandbox/';
		
		//MMP LIVE DIRECTORY PATH
		$this->MMP_LIVE_DIR = FCPATH . 'public/mmp/';
		$this->MMP_LIVE_DIR_ACTUAL = '/data/mmp/';
		
		//MMP LIVE BACKUP DIRECTORY PATH
		$this->MMP_LIVE_BACKUP_DIR = FCPATH. 'public/mmp/backup/';
		$this->MMP_LIVE_BACKUP_DIR_ACTIAL = '/data/mmp/backup/';
		
		//MMP Extracted folder permissions
		$this->MMP_EXTRACTED_FOLDER_FROM_ZIP_PERMISSION = 0777;
	}
	
	private function _setMode($mode = 'read') {
		$server_url = CUSTOMIZE_MMP_READ_SERVER;
		$server_port = CUSTOMIZE_MMP_READ_SERVER_PORT;
		if($mode == 'write') {
			$server_url = CUSTOMIZE_MMP_WRITE_SERVER;
			$server_port = CUSTOMIZE_MMP_WRITE_SERVER_PORT;
		}
		$this->_ci->xmlrpc->set_debug(0);
		$this->_ci->xmlrpc->server($server_url,$server_port);
	}
	
	public function isMMPZipValid($mmpZipPath = NULL, $mmpName, $validDirectoryName = array("images", "js", "css"), $validIndexFileName = "index.html") {
		$returnData = array();
		if($this->zipfunctionExists()) {
			$mmp_name = $this->getFileNameFromFilePath($mmpZipPath);
			if($mmp_name != $mmpName) {
				$returnData['zip_file_name_error'] = "Uploaded template filename not matched with mmp id";
			} else {
				$zipFilesList = array();
				$zip = zip_open($mmpZipPath);
				$files = array();
				do {
					$entry = zip_read($zip);
					$file = zip_entry_name($entry);
					if(!empty($file)){
						array_push($files, $file);	
					}
				} while($entry);
				zip_close($zip);
				$fileStructureDetails = array();
				
				foreach($files as $file_name) {
					$explodedData = explode("/", $file_name);
					$explodedDataLength = count($explodedData);
					if($explodedDataLength == 2 && empty($explodedData[$explodedDataLength-1])){
						$fileStructureDetails['parent_folder'] = $explodedData[0];
					} else if($explodedDataLength == 3 && empty($explodedData[$explodedDataLength-1])){
						$fileStructureDetails['folder'][] = $explodedData[$explodedDataLength-2];
					} else if($explodedDataLength == 2 && !empty($explodedData[$explodedDataLength-1])){
						$fileStructureDetails['index_files'][] = $explodedData[$explodedDataLength-1];
					} else if($explodedDataLength == 3 && !empty($explodedData[$explodedDataLength-1])){
						$folderName = $explodedData[$explodedDataLength-2];
						$fileStructureDetails['folder_files'][$folderName][] = $explodedData[$explodedDataLength-1];
					}
				}
				//check for parent folder
				if(array_key_exists('parent_folder', $fileStructureDetails)) {
					if($fileStructureDetails['parent_folder'] != $mmpName){
						$returnData['parent_folder_name_error'] = "The name of the root directory doesn't match with mmp id";
					}
				} else {
					$returnData['parent_folder_name_error'] = "The name of the root directory doesn't match with mmpid";
				}
				
				/*
				// check for other folders
				if(array_key_exists("folder", $fileStructureDetails)){
					$folderDetails = $fileStructureDetails['folder'];
					foreach($validDirectoryName as $dirName){
						if(!in_array($dirName, $folderDetails)){
							$returnData[$dirName."_not_exist_error"] = $dirName." directory is not present in uploaded template";
						}	
					}					
				} else {
					$returnData["subfolders_not_exist"] = "sub directories are not present in uploaded template";
				}
				//check for index files
				if(array_key_exists("index_files", $fileStructureDetails)){
					if(count($fileStructureDetails['index_files']) == 1){
						if(!in_array($validIndexFileName, $fileStructureDetails['index_files'])){
							$returnData["index_file_exist_error"] = "index file is not present in uploaded template";
						}	
					} else {
						$returnData["index_file_exist_error"] = "only index.html file is allowed in root directory";
					}
				} else {
					$returnData["index_file_exist_error"] = "index file is not present in uploaded template";
				}
				// check for subfiles in folders
				if(array_key_exists("folder_files", $fileStructureDetails)){
					$folder_files = $fileStructureDetails['folder_files'];
					foreach($validDirectoryName as $dirName){
						if(!array_key_exists($dirName, $folder_files)){
							$returnData[$dirName."_files_not_exist_error"] = $dirName." directory is empty";
						}
					}
				} else {
					$returnData["index_file_exist_error"] = "sub directories are empty in uploaded template";
				}
				*/
			}
		} else {
			$returnData['zip_function_exist_error'] = "zip functions are not available";
		}
		if(empty($returnData)){
			$returnData['success'] = true;
		} else {
			$returnData['error'] = true;
		}
		return $returnData;
	}
	
	public function zipfunctionExists(){
		if(function_exists('zip_open') && function_exists('zip_read') && function_exists('zip_close') && function_exists('zip_entry_name')) {
			return true;
		} else {
			return false;
		}
		
	}

	/**
	 * @method array checkMMPZipFormat : This function extracted the content of zip file and scans the directory and checks
	 *	whether all the required folders and index.html is present in the zip file
	 *
	 * @param string $zipname : name of the zip, here it should be page id of the mmp
	 * @param string $mmpZipPath : path where the zip file is situated, here its the uploaded path where the zip file has been uploaded.
	 * i.e mmp sandbox directory
	 * @param string $mmpZipExtractedPath : path where the content of the zip file should be extracted, here its the mmp sandbox folder.
	 *
	 * @return array : If the array has the key success than it means the operation was completed successfully else the error key will be set
	 * and all the error messages with different keys can be found
	 *
	 * @example  returnarray:
	 * Array (
			[error] => true,
            [error_text] => Array (
								[some_error_key] => some_error_text_message	
							),
			[some_other_error_key] => some_other_error_text_message
        )
        or
		Array (
			[success] => true
        )
	*/
	public function checkMMPZipFormat($zipName, $mmpZipPath = NULL, $mmpZipExtractedPath = NULL) {
		$return = array();
		$zipFormatFine = false;
		$indexFileCompletePath = NULL;
		if($mmpZipExtractedPath != NULL && $mmpZipPath != NULL) {
			$zipValidResult = $this->isMMPZipValid($mmpZipPath, $zipName);
			if(!empty($zipValidResult['success']) && $zipValidResult['success']){
				$zipExtractedResult = $this->extractZipFile($mmpZipPath, $mmpZipExtractedPath);
				$extractedMMPFolderPath = $mmpZipExtractedPath . $zipName;
				$indexFileCompletePath = $extractedMMPFolderPath . "/index.html";
				$return = $zipExtractedResult;
			} else {
				$return = $zipValidResult;
			}
		}
		if(!isset($return['success']) && !isset($return['error'])){
			$return['error'] = true;
			$return['server_side'] = "server side error occurred while extracting template zip";
		}
		if(isset($return['success']) && $return['success']){
			$return['index_file_path'] = $indexFileCompletePath;
		}
		return $return;
	}
	
	public function checkMMPZipFormatOLD($zipName, $mmpZipPath = NULL, $mmpZipExtractedPath = NULL) {
		$return = array();
		$zipFormatFine = false;
		if($mmpZipExtractedPath != NULL && $mmpZipPath != NULL) {
			$zipExtractedResult = $this->extractZipFile($mmpZipPath, $mmpZipExtractedPath);
			if(isset($zipExtractedResult['success']) && $zipExtractedResult['success']) {
				$extractedMMPFolderPath = $mmpZipExtractedPath . $zipName;
				$returnInfo = $this->scanDirectory($extractedMMPFolderPath, true);
				$return = $returnInfo;	
			} else {
				$return = $zipExtractedResult;
			}
		}
		if(!isset($return['success']) && !isset($return['error'])){
			$return['error'] = true;
			$return['server_side'] = "server side error occurred while extracting template zip";
		}
		return $return;
	}
	
	/**
	 * @method array scanDirectory : This function scans the directory and check whether the mentioned folder names and files are present in the directory or not.
	 * Here we checks for the 'images', 'css' and 'js' folder and 'index.html' file in the directory.
	 *
	 * @param string $path : path of the directory which we want to scan for the above mentioned folders and files.
	 * @param boolean $checkForEmptyDir : whether we want to check whether the folders in the directory are empty or not. if the param value is true
	 * than the error will be raised if the folder is empty. Else if the param value is set as false than only folder existence will be checked
	 * and not whether the folder is empty or not.
	 * @param array $validDirectoryName : all the folder names that needs to be present in the $path directory.
	 * @param string $validIndexFileName : the default filename that needs to be present in the root of the $path directory.
	 
	 * @return array : If the array has the key success than it means the operation was completed successfully else the error key will be set
	 * and all the error messages with different keys can be found
	 *
	 * @example  returnarray:
	 * Array (
			[error] => true,
            [error_text] => Array (
								[some_error_key] => some_error_text_message	
							),
			[some_other_error_key] => some_other_error_text_message
        )
        or
		Array (
			[success] => true
        )
	*/
	public function scanDirectory($path = NULL, $checkForEmptyDir = false, $validDirectoryName = array("images", "js", "css"), $validIndexFileName = "index.html") {
		$returnArray = array();
		$completeIndexFileName = "";
		$indexFileFound = false;
		if($path != NULL){
			if(is_dir($path)){
				$validDirectoriesEncountered = 0;
				$directoryStructure = scandir($path);
				foreach($directoryStructure as $fileOrDir){
					if($fileOrDir == "." || $fileOrDir == ".."){ // skips if the default . and .. directory encountered
						continue;
					} else {
						$completePathOfFile = $path . "/" . $fileOrDir;
						if(is_dir($completePathOfFile)) {
							if(in_array($fileOrDir, $validDirectoryName)){
								$files = scandir($completePathOfFile);
								if($checkForEmptyDir){
									if(count($files) <= 2){
										$returnArray[$fileOrDir] = $fileOrDir . " directory is empty";
									}
								}
								$validDirectoriesEncountered++; // how many valid directoy we have encountered
							} else {
								//$returnArray[$fileOrDir] = $fileOrDir . " not a valid directory name";
							}
						} else if(is_file($completePathOfFile)) {
							$completeIndexFileName = $path . "/" . $validIndexFileName;
							if($completePathOfFile == $completeIndexFileName){
								$indexFileFound = true;
							}
						}
					}
				}	
			}
		} else {
			$returnArray['path'] = "some problem with directory path";
		}
		if($validDirectoriesEncountered < count($validDirectoryName)){
			$returnArray['valid_dir'] = "not all required directories/files are present";
		}
		if(!empty($returnArray) || !$indexFileFound){
			$returnArray['error'] = true;
		} else {
			$returnArray['success'] = true;
			$returnArray['index_file_path'] = $completeIndexFileName;
		}
		return $returnArray;
	}
	
	/**
	 * @method array removeExistingDirectoryOrFile : This function deleted the file/folder mentioned in the $dirPath. If the $dirPath is folder
	 * than it removes each file inside folder individualy and then remove the folder itself. Before deleting any file it first checks
	 * whether the file/folder is in the valid directory i.e in our case is mmp folder. If the file/folder is outside the mmp directory path than it
	 * will return error.
	 *
	 * @param string $dirPath : path of the directory/file that needs to be deleted.
	 * 
	 * @return array : If the array has the key success than it means the operation was completed successfully else the error key will be set
	 * and all the error messages with different keys can be found
	 *
	 * @example  returnarray:
	 * Array (
			[error] => true,
            [error_text] => Array (
								[some_error_key] => some_error_text_message	
							),
			[some_other_error_key] => some_other_error_text_message
        )
        or
		Array (
			[success] => true
        )
        
        ::::::::::::::::UPDATE NOTE::::::::::::::::::
        After discussions on whether or not to remove the existing file/dir on server. To avoid unwanted scenarios
        we are not deleting any dir/file, we are just renaming them to "archive" with date.
        
	*/
	public function removeExistingDirectoryOrFile($dirPath = NULL) {
		$returnData = array();
		$returnFlag = false;
		// Extra check to make sure that the $dirPath should be inside of mmp folder and not as same as sandbox, backup or live mmp folder
		if($this->checkIfValidDirectoryForDeletion($dirPath)) { // checks whether the $dirPath is a valid path that could be deleted
			if(is_file($dirPath)){
				$ext = pathinfo($dirPath, PATHINFO_EXTENSION);
				if($ext == 'zip'){
					$newDirPath = $dirPath.'-archive-'.date("d.m.y:H.m.s");	
				} else {
					$newDirPath = $dirPath;
				}
				rename($dirPath, $newDirPath);
			} else if(is_dir($dirPath)){
				$directoryStructure = scandir(rtrim($dirPath,"/"));
				$filePaths = array();
				foreach($directoryStructure as $file){
					if($file != "." && $file != ".."){
						$filePaths[] = rtrim($dirPath,"/")."/".$file;
					}
				}
				foreach($filePaths as $index=>$path){
					$this->removeExistingDirectoryOrFile($path);
				}
				$newDirPath = $dirPath.'-archive-'.date("d.m.y:H.m.s");
				rename($dirPath, $newDirPath);
			}
		} else {
			$returnData['error_text'][] = $dirPath . " cannot be deleted, it seems to be outside MMP directory. Please check your operation again.";
		}
		if(empty($returnData)){
			$returnData['success'] = true;
		} else {
			$returnData['error'] = true;
		}
		return $returnData;
	}
	
	/**
	 * @method boolean checkIfValidDirectoryForDeletion : This function checks whether the dirpath/filepath is valid for deletion process
	 * Cases considerd before deleting any file:
	 * TO AVOID ANY KIND OF ACCIDENTS
	 * 1. The file/dir should be in the mmp folder. ex should be in /var/www/html/shiksha/public/mmp/
	 * 2. The file/dir should not be live mmp directory. i.e /var/www/html/shiksha/public/mmp/
	 * 3. The file/dir should not be sandbox mmp directory. i.e /var/www/html/shiksha/public/mmp/sandbox/
	 * 4. The file/dir should not be backup mmp directory. i.e /var/www/html/shiksha/public/mmp/backup/
	 * 
	 * @param string $dir : path of the file/dir that needs to be deleted
	 *
	 * @return boolean : true if the path is valid else false.
	 *
	*/
	public function checkIfValidDirectoryForDeletion($dir) {
		$shikshaMMPDir = $this->MMP_LIVE_DIR;
		$shikshaMMPSandboxDir = $this->MMP_SANDBOX_DIR;
		$shikshaMMPBackupDir = $this->MMP_LIVE_BACKUP_DIR;
		
		$shikshaMMPDir = trim($shikshaMMPDir, "/");
		$shikshaMMPDir = "/".$shikshaMMPDir."/";
		$shikshaMMPSandboxDir = trim($shikshaMMPSandboxDir, "/");
		$shikshaMMPSandboxDir = "/".$shikshaMMPSandboxDir."/";
		$shikshaMMPBackupDir = trim($shikshaMMPBackupDir, "/");
		$shikshaMMPBackupDir = "/".$shikshaMMPBackupDir."/";

		$shikshaMMPDirActual = $this->MMP_LIVE_DIR_ACTUAL;
		$shikshaMMPSandboxDirActual = $this->MMP_SANDBOX_DIR_ACTUAL;
		$shikshaMMPBackupDirActual = $this->MMP_LIVE_BACKUP_DIR_ACTUAL;

		$shikshaMMPDirActual = trim($shikshaMMPDirActual, "/");
		$shikshaMMPDirActual = "/".$shikshaMMPDirActual."/";
		$shikshaMMPSandboxDirActual = trim($shikshaMMPSandboxDirActual, "/");
		$shikshaMMPSandboxDirActual = "/".$shikshaMMPSandboxDirActual."/";
		$shikshaMMPBackupDirActual = trim($shikshaMMPBackupDirActual, "/");
		$shikshaMMPBackupDirActual = "/".$shikshaMMPBackupDirActual."/";	
		
		$dirUnderCheck = $dir;
		if(is_dir($dirUnderCheck)){
			$dirUnderCheck = trim($dirUnderCheck, "/");
			$dirUnderCheck = "/".$dirUnderCheck."/";
		} else if(is_file($dirUnderCheck)){
			$dirUnderCheck = trim($dirUnderCheck, "/");
			$dirUnderCheck = "/".$dirUnderCheck;
		}
		$returnData = array();
		$dirUnderCheckInPath = strstr($dirUnderCheck, $shikshaMMPDir);
		if($dirUnderCheckInPath != false && $dirUnderCheck != $shikshaMMPDir && $dirUnderCheck != $shikshaMMPBackupDir && $dirUnderCheck != $shikshaMMPSandboxDir)                {
			return true;
		} 
		
		$dirUnderCheckInPath = strstr($dirUnderCheck, $shikshaMMPDirActual);
                if($dirUnderCheckInPath != false && $dirUnderCheck != $shikshaMMPDirActual && $dirUnderCheck != $shikshaMMPBackupDirActual && $dirUnderCheck != $shikshaMMPSandboxDirActual)                {
                        return true;
                } 

		return false;
	}
	
	/**
	 * @method array extractZipFile : This function extracts the zip($zipFilePath) content in $destinationPath directory. This function
	 * internally checks whether the php ZIP extension is available or not.If extension is enabled than it uses the zip extension otherwise pclzip lib to extract.
	 * The function first deletes the folder if exists with the same name as the zip content.
	 *
	 * @param string $zipFilePath : path of the zip file that needs to be extracted. Here in this case the zipfile is present in mmp sandbox folder.
	 * @param string $destinationPath : path of the directory where we need to extract the content of zip file. Here in this case it is mmp sandbox folder.
	 *
	 * @return array : If the array has the key success than it means the operation was completed successfully else the error key will be set
	 * and all the error messages with different keys can be found
	 *
	 * @example  returnarray:
	 * Array (
			[error] => true,
            [error_text] => Array (
								[some_error_key] => some_error_text_message	
							),
			[some_other_error_key] => some_other_error_text_message
        )
        or
		Array (
			[success] => true
        ) 
	*/
	public function extractZipFile($zipFilePath, $destinationPath) {
		$filename = $this->getFileNameFromFilePath($zipFilePath); // get the filename from path. Here in this case it should be the page id of mmp
		$mmpFolderPath = $destinationPath . $filename;
		$checkIfDirIsValid = $this->removeExistingDirectoryOrFile($mmpFolderPath); // Try to delete the folder if already present with the same name as the zip content.
		if(!empty($checkIfDirIsValid['success']) && $checkIfDirIsValid['success'] == true){ // successfully deleted
			$filesSet = $this->extractZipWrapper($zipFilePath, $destinationPath); // extractZipWrapper will choose which method to use to extract the zip content. php zip extension or pclzip lib
			// $fileset has complete directory structure of the extracted zip content
			$mainFolderCheck = false;
			$folderExtractedFromZip = "";
			if(is_array($filesSet) && count($filesSet) > 0) {
				foreach($filesSet as $file){
					if($file['index'] == 0){
						$folderExtractedFromZip = $file['filename'];
						if($file['stored_filename'] == $filename."/"){ // if the name of folder extracted from zip is same as mmp page id
							$mainFolderCheck = true;
						}
					}
					chmod($file['filename'], $this->MMP_EXTRACTED_FOLDER_FROM_ZIP_PERMISSION); // sets permission
				}
			}
			if(!$mainFolderCheck){
				$returnInfo['folder_inside_zip'] = "Folder name inside zip is not matched with zip name";
				$returnInfo['folder_to_be_deleted'][] = $folderExtractedFromZip;
			}
		}
		$returnInfo = array();
		if(!empty($checkIfDirIsValid['error']) && $checkIfDirIsValid['error'] == true){
			$returnInfo['error_text'] = $checkIfDirIsValid['error_text'];
		}
		if(!empty($returnInfo)){
			$returnInfo["error"] = true;
		} else {
			$returnInfo["success"] = true;
		}
		return $returnInfo;
	}
	
	/**
	 * @method array moveMMPFromDevToLive : This function moves the mmp folder from sandbox to live, while moving folder from sandbox to live
	 * we follow some steps in this order
	 * 1. If mmp folder with same name is already present in the live mmp directory than we first make a backup zip of the current live
	 * folder and put it in backup live folder.
	 * 2. Copy the mmp folder from sandbox and paste it into live mmp folder with different name. Here in this case we copied it as "mmp_name-new" ex "112-new".
	 * 3. Once the step 2 is successfully executed we delete the current live mmp folder from live directory.
	 * 4. Once the step 3 is successfully executed we rename the "mmp_name-new" folder to the "mmp_name" folder. example from "112-new" to "112"
	 * 5. The new MMP folder is live now.
	 *
	 * At any time if the step from 3 to 4 fails than we follow these steps
	 * 1. delete the new mmp folder from the live directory example delete the "112-new" from the live mmp directory.
	 * 
	 * @param string $mmpName : Name of the mmp that needs to be moved from sandbox to live. Here in our case the page id of mmp
	 * 
	 * @return array : If the array has the key success than it means the operation was completed successfully else the error key will be set
	 * and all the error messages with different keys can be found
	 *
	 * @example  returnarray:
	 * Array (
			[error] => true,
            [error_text] => Array (
								[some_error_key] => some_error_text_message	
							),
			[some_other_error_key] => some_other_error_text_message
        )
        or
		Array (
			[success] => true
        ) 
	*/
	public function moveMMPFromDevToLive($mmpName) {
		$returnFlag = false;
		$mmpLiveBackupDir = $this->MMP_LIVE_BACKUP_DIR;
		$destination = $this->MMP_LIVE_DIR;
		$source =  $this->MMP_SANDBOX_DIR.$mmpName;
		$liveMMPFolder = $destination.$mmpName;
		$returnData = array();
		if(is_dir($source)) {
			$copyStatus = true;
			if(is_dir($liveMMPFolder)){
				// create backup zip file for live mmp folder in backup folder
				$backupDirName = $mmpName.'-backup-'.date("d.m.y:H.m.s");
				$zipFullPath = $this->MMP_LIVE_BACKUP_DIR . $backupDirName . ".zip";
				$sourceFolder = $source."/";
				$copyStatus = $this->createZipWrapper($zipFullPath, $sourceFolder, $mmpName);
			}
			$devToLiveCopyStatus = false;
			$moveLiveTempToLive = false;
			if($copyStatus){
				// copy the sandbox mmp folder to live mmp folder as new
				$copySandboxToLiveTemp = $this->copyDirectoryRecursive($source, $destination.$mmpName.'-new');
				if($copySandboxToLiveTemp){
					// COPY MMP FOLDER FROM SANDBOX TO LIVE AS NEW IS SUCCESSFUL
					// delete the current live mmp folder
					$checkForValidDir = $this->removeExistingDirectoryOrFile($liveMMPFolder);
					if(!empty($checkForValidDir['success']) && $checkForValidDir['success'] == true){
						// deletion of live mmp folder is successful, rename the new mmp folder as live mmp
						$moveLiveTempToLive = rename($destination.$mmpName.'-new', $destination.$mmpName);
					} else {
						// deletion of live mmp folder failed, delete the new mmp folder
						$this->removeExistingDirectoryOrFile($destination.$mmpName.'-new');
						$returnData['error_text']['error_delete_file'] = $checkForValidDir['error_text'];
					}
				} else {
					$returnData['error_text']['sandbox_to_live'] = "Unable to copy mmp folder from sandbox to live directory";
				}
			} else {
				$returnData['error_text']['live_mmp_zip_operation'] = "Unable to create zip file for live MMP";
			}
		} else {
			$returnData['error_text']['sandbox_mmp_folder'] = "MMP folder for this page id doesn't exist in sandbox directory!";
		}
		if(!empty($returnData)){
			$returnData['error'] = true;
		} else {
			$returnData['success'] = true;
		}
		return $returnData;
	}
	
	/**
	 * @method array disableLiveMMP : This function takes the backup of current live mmp folder, deletes the live mmp folder from live mmp directory and creates the error empty
	 * directory in place of live mmp folder in live mmp directory. While doing these actions fuction follows certain steps
	 * in this order:
	 * 1. If live mmp folder is present in the live mmp directory than first creates the zip backup of the mmp and copy it into
	 * the live backup directory.
	 * 2. If step 1st is successful than delete the live mmp folder.
	 * 3. If step 2nd is successful than create a folder with same mmp name in the live mmp directory and copy the error.html in it
	 * that redirect the user to shiksha error page.
	 *
	 * If At any time any of the above mentioned steps failed than the error will be thrown
	 * 
	 * @param string $mmpName : Name of the mmp that needs to be disables.
	 * 
	 * @return array : If the array has the key success than it means the operation was completed successfully else the error key will be set
	 * and all the error messages with different keys can be found
	 *
	 * @example  returnarray:
	 * Array (
			[error] => true,
            [error_text] => Array (
								[some_error_key] => some_error_text_message	
							),
			[some_other_error_key] => some_other_error_text_message
        )
        or
		Array (
			[success] => true
        ) 
	*/
	public function disableLiveMMP($mmpName){
		$returnFlag = false;
		$destination = $this->MMP_LIVE_BACKUP_DIR;
		$source =  $this->MMP_LIVE_DIR.$mmpName;
		$returnData = array();
		if(is_dir($source)) { // the mmp folder is present in the live mmp directory
			$copyStatus = true;
			if(is_dir($source)){ 
				$backupDirName = $mmpName.'-backup-'.date("d.m.y:H.m.s");
				$zipFullPath = $this->MMP_LIVE_BACKUP_DIR . $backupDirName.".zip";
				$sourceFolder = $source."/";
				$copyStatus = $this->createZipWrapper($zipFullPath, $sourceFolder, $mmpName); // create backup of the live mmp folder
			}
			if($copyStatus) {
				// backup successfully created, now remove the mmp folder from live mmp directory
				
				$checkIfDirValidForDelete = $this->removeExistingDirectoryOrFile(rtrim($source,"/"));
				if(!empty($checkIfDirValidForDelete['success']) && $checkIfDirValidForDelete['success'] == true){
					// mmp folder deleted successfully now create the empty 404 directory in place of mmp in live mmp directory
					$returnRes = $this->createEmpty404Directory($this->MMP_LIVE_DIR, $mmpName);
					if($returnRes['error'] == true){
						$returnData['error_text'][] = $returnRes['error_text'];
					}
				} else {
					$returnData['error_text'] = $checkIfDirValidForDelete['error_text'];
				}
			} else {
				$returnData['error_text']['live_mmp_zip_operation'] = "Unable to create zip file for live MMP";
			}
		} else {
			$returnData['error_text']['sandbox_mmp_folder'] = "MMP folder for this page id doesn't exist in mmp directory!";
		}
		if(!empty($returnData)){
			$returnData['error'] = true;
		} else {
			$returnData['success'] = true;
		}
		return $returnData;
	}
	
	/**
	 * @method string getFileNameFromFilePath : This function extracts the file name from filepath, basicly extracts the last portion of the file path
	 *
	 * @param string $filePath : path of the zip file from which the name need to be extracted
	 *
	 * @return string : name of the file
	 *
	 * @example  string:
	 * $filePath : /var/www/html/shiksha/public/mmp/sandbox/112.zip
	 * $filename will be - 112 
	*/
	public function getFileNameFromFilePath($filePath = NULL) {
		$returnFlag = false;
		if($filePath != NULL){
			$filePathPortions = explode("/", $filePath);
			if(count($filePathPortions) > 0){
				$fileName = trim($filePathPortions[count($filePathPortions) - 1]);
				if($fileName != ""){
					$file = explode(".", $fileName);
					if($file[0] != ""){
						$returnFlag = $file[0];	
					}
				}
			}
		}
		return $returnFlag;
	}
	
	/**
	 * @method string replacePlaceHolderByMMPForm : This function reads the file($filePath) and replace the placeholder text(@placeholderText) with the
	 * actual form html with js and all the css needs to make it work.
	 *
	 * @param string $filePath : path where the file is located in the filesystem into which the replacement of placeholder and form html
	 * need to take place.
	 * @param string $formHTML : the complete form html with css and js that will replace the placeholder in index.html
	 * @param string $placeholderText : the place holder text that needs to be replaced by form html
	 *
	 * @return boolean : whether the replacement of placeholder with form is successful or not
	*/
	public function replacePlaceHolderByMMPForm($filePath = NULL, $formHTML = "", $placeholderText = "@MMPFORM"){
		$returnFlag = false;
		if(file_exists($filePath)){
			if(is_readable($filePath) && is_writable($filePath)){
				$fileContents = file_get_contents($filePath);
				if($fileContents != false){
					$updatedFileContent = str_replace($placeholderText, $formHTML, $fileContents);
					$returnValue = file_put_contents($filePath, $updatedFileContent, LOCK_EX); // lock the file while replacing the placeholdertext with form html
					if($returnValue != false){
						$returnFlag = true;
					}
				}
			}
		}
		return $returnFlag;
	}
	
	/**
	 * @method boolean copyDirectoryRecursive : This function recursively copies the source folder to destination path
	 *
	 * @param string $path : path is the source path i.e which folder we need to copy 
	 * @param string $dest : path where we need to copy the source folder
	 *
	 * @return boolean : whether the copy from source to destination is successful or not
	*/
	public function copyDirectoryRecursive($path, $dest) {
		if(is_dir($path)) {
            @mkdir($dest);
			$objects = scandir($path);
            if( sizeof($objects) > 0 ) {
                foreach( $objects as $file ) {
                    if( $file == "." || $file == ".." ) {
						continue;
					}
                    if(is_dir($path."/".$file)) {
                        $this->copyDirectoryRecursive($path."/".$file, $dest."/".$file);
                    } else {
                        copy($path."/".$file, $dest."/".$file);
                        chmod($dest."/".$file, 0755);
                    }
                }
            }
            return true;
        }
        elseif(is_file($path)) {
			$returnCopyVal = copy($path, $dest);
			return $returnCopyVal;
        }
        else {
			return false;
        }
	}
	
	/**
	 * @method boolean checkTemplateName : This function checks whether the name of the zip file is completely numeric and doesn't contain any
	 * characters in it. We need the folder name should be same as page id
	 *
	 * @param string $fileName : Name of the file that needs to be checked for all digit name
	 *
	 * @return boolean : Whether the name is all numeric or not
	*/
	public function checkTemplateName($fileName = NULL){
		$returnFlag = false;
		$fileName = (string)$fileName;
		if(ctype_digit($fileName)){
			$returnFlag = true;
		}
		return $returnFlag;
	}
	
	/**
	 * @method array createEmpty404Directory : This function creates the empty404 directory for the pageid($page_id) in the path($path).
	 * The error404 directory has one error file that redirects the user to shiksha error page
	 *
	 * @param string $path : Path where the new empty404directory need to be created.
	 * @param string $page_id: Name that should be given to empty404directory.
	 * 
	 * @return array : If the array has the key success than it means the operation was completed successfully else the error key will be set
	 * and all the error messages with different keys can be found
	 *
	 * @example  returnarray:
	 * Array (
			[error] => true,
            [error_text] => Array (
								[some_error_key] => some_error_text_message	
							),
			[some_other_error_key] => some_other_error_text_message
        )
        or
		Array (
			[success] => true
        ) 
	*/
	public function createEmpty404Directory($path = NULL, $page_id = NULL){
		$returnData = array();
		$returnData['error_text'] = array();
		$direcotryPath = $path;
		$error404FilePath = $this->MMP_SANDBOX_DIR."error.html";
		$fullPath = $direcotryPath . $page_id."/";
		// only create if the directory is already not present
		if(!is_dir($fullPath)){
			$directoryCreatedStatus = mkdir($fullPath, $this->MMP_EXTRACTED_FOLDER_FROM_ZIP_PERMISSION);
			chmod($fullPath, $this->MMP_EXTRACTED_FOLDER_FROM_ZIP_PERMISSION);
			$error404FileCopied = false;
			if($directoryCreatedStatus) {
				$error404FileCopied = $this->copyDirectoryRecursive($error404FilePath, $fullPath."index.html");
				if(!$error404FileCopied){
					$returnData['error_text']['dir_creation_problem'] = "Couldn't copy 404 error file from sandbox: ".$error404FilePath. " to directory: " . $fullPath;
				}
			} else {
				$returnData['error_text']['dir_creation_problem'] = "Failed to create directory named ".$page_id. " in path: " . $path;
			}
		} else {
			$returnData['error_text']['dir_already_present'] = "Directory with mentioned name already present in ". $path;
		}
		if(!empty($returnData['error_text'])){
			$returnData['error'] = true;
		} else {
			$returnData['success'] = true;
		}
		return $returnData;
	}
	
	/**
	 * @method array extractZipWrapper : The wrapper function that decides which method needs to be used to extract zip content from zip file
	 * Methods available:
	 * 1. php zip extension : might be available might not on different server, comes default with php 5.3 but not woth php 5.2
	 * 2. pclzip 3rd party library : works with php 5.2 versions
	 * 
	 * @param string $zipFilePath : Path of the zip file that needs to be extracted.
	 * @param string $destinationFolder: path of the destination directory where we need to extract the content
	 * 
	 * @return array : If the array has the key success than it means the operation was completed successfully else the error key will be set
	 * and all the error messages with different keys can be found
	 *
	 * @example  returnarray:
	 * Array (
			[error] => true,
            [error_text] => Array (
								[some_error_key] => some_error_text_message	
							),
			[some_other_error_key] => some_other_error_text_message
        )
        or
		Array (
			[success] => true
        ) 
	*/
	public function extractZipWrapper($zipFilePath, $destinationFolder) {
		$zipFilesList = array();
		try {
			// if php zip extension is available
			$zipFilesList = $this->extractZipContent($zipFilePath, $destinationFolder);
		} catch(Exception $e){
			// if php extension is not available
			$zipFilesList = $this->extractZipContentByPCLZip($zipFilePath, $destinationFolder);
		}
		return $zipFilesList;
	}
	
	/**
	 * @method array extractZipContent : This function extracts zip content from zip file, for extraction process uses php zip extension
	 * 
	 * @param string $zipFilePath : Path of the zip file that needs to be extracted.
	 * @param string $destinationFolder: path of the destination directory where we need to extract the content
	 * 
	 * @return array : If the array has the key success than it means the operation was completed successfully else the error key will be set
	 * and all the error messages with different keys can be found
	 *
	 * @example  returnarray:
	 * non empty array on success
	 *  Array (
			[0] => Array
			(
				[filename] => /var/www/html/shiksha/public/mmp/sandbox/113/
				[stored_filename] => 113/
				[size] => 0
				[compressed_size] => 0
				[mtime] => 1325659422
				[comment] => 
				[folder] => 1
				[index] => 0
				[status] => ok
				[crc] => 0
			)
			......
			......
			......
		)
		or empty array on failure
		Array (
			
		)
	*/
	private function extractZipContent($zipFilePath, $destinationFolder){
		$zipFilesList = array();
		$zip = new ZipArchive();
		if($zip->open($zipFilePath) === TRUE) {
			$zipExtractedSuccessfully = $zip->extractTo($destinationFolder);
			if($zipExtractedSuccessfully){
				for ($i=0; $i<$zip->numFiles;$i++) {
					$tempFileArray = $zip->statIndex($i);
					$zipFilesList[$i] = $tempFileArray;
					$zipFilesList[$i]['filename'] = $destinationFolder . $tempFileArray['name'];
					$zipFilesList[$i]['stored_filename'] = $tempFileArray['name'];
				}
			}
			$zip->close();
		}
		return $zipFilesList;
	}
	
	/**
	 * SAME AS ABOVE FUNCTION : extractZipContent();
	 * The method of extraction is different
	 */
	private function extractZipContentByPCLZip($zipFilePath, $destinationFolder){
		$this->_ci->load->library('PclZip', array('zipfilepath' => $zipFilePath));
		$filesSet = $this->_ci->pclzip->extract($destinationFolder);
		if(is_array($filesSet) && !empty($filesSet)) {
			return $filesSet;
		} else {
			return array();
		}
	}

	/**
	 * @method boolean createZipWrapper : The wrapper function that decides which method needs to be used to make the zip file
	 * Methods available:
	 * 1. php zip extension : might be available might not on different server, comes default with php 5.3 but not woth php 5.2
	 * 2. pclzip 3rd party library : works with php 5.2 versions
	 * 
	 * @param string $zipFilePath : Path of the zip file that needs to be extracted.
	 * @param string $sourceFolder: path of the source folder than needs to be zipped
	 * @param string $mmp_name: name of the mmp here in this case pageid of mmp
	 * 
	 * @return boolean : true if success, false on failure
	*/
	public function createZipWrapper($zipFilePath, $sourceFolder, $mmp_name) {
		$returnFlag = false;
		try {
			// using php zip extension for zip process
			$returnFlag = $this->createZip($zipFilePath, $sourceFolder, $mmp_name);
		} catch(Exception $e){
			// using pclzip method for zip process
			$returnFlag = $this->createZipByPCLZip($zipFilePath, $sourceFolder, $mmp_name);
		}
		return $returnFlag;
	}
	
	/**
	 * @method boolean createZip : php zip extension used to create zip file of source folder
	 *
	 * @param string $zipFilePath : Path of the zip file that needs to be extracted.
	 * @param string $sourceFolder: path of the source folder than needs to be zipped
	 * @param string $mmp_name: name of the mmp here in this case pageid of mmp
	 * 
	 * @return boolean : true if success, false on failure
	*/
	private function createZip($zipFilePath, $sourceFolder, $mmp_name){
		$zip = new ZipArchive();
		if ($zip->open($zipFilePath, ZIPARCHIVE::CREATE) == TRUE) {
			$this->addFolderToZip($sourceFolder, $zip, $mmp_name."/");
			$zip->close();
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * @method boolean createZip : pclzip library used to create zip file of source folder
	 *
	 * @param string $zipFilePath : Path of the zip file that needs to be extracted.
	 * @param string $sourceFolder: path of the source folder than needs to be zipped
	 * @param string $mmp_name: name of the mmp here in this case pageid of mmp
	 * 
	 * @return boolean : true if success, false on failure
	*/
	private function createZipBYPCLZIP($zipFilePath, $sourceFolder){
		$this->_ci->load->library('PclZip', array('zipfilepath' => $zipFilePath));
		if(($filesSet = $this->_ci->pclzip->create($sourceFolder, PCLZIP_OPT_REMOVE_PATH, $sourceFolder)) == 0) {
			die("Error : ".$this->_ci->pclzip->errorInfo(true));
		}
		return true;
	}

	/**
	 * Internal function used by createZip function to add folders to zip file
	 */
	private function addFolderToZip($dir, $zipArchive, $zipdir = ''){
		if(is_dir($dir)) {
			if ($dh = opendir($dir)) {
				if(!empty($zipdir)) {
					$zipArchive->addEmptyDir($zipdir);
				}
				while (($file = readdir($dh)) !== false) { 
					if(!is_file($dir . $file)){ 
						if( ($file !== ".") && ($file !== "..")){ 
							$this->addFolderToZip($dir . $file . "/", $zipArchive, $zipdir . $file . "/"); 
						} 
					} else { 
						$zipArchive->addFile($dir . $file, $zipdir . $file);
					} 
				} 
			} 
		} 
	}

	/**
	 * @method array readFileContent : This function reads the file content of the file mentioned in the $path param
	 *
	 * @param string $path : Path of the file that need to be read.
	 * 
	 * @return array : If the array has the key success than it means the operation was completed successfully else the error key will be set
	 * and all the error messages with different keys can be found
	 *
	 * @example  returnarray:
	 * Array (
			[error] => true,
            [error_text] => Array (
								[some_error_key] => some_error_text_message	
							),
			[some_other_error_key] => some_other_error_text_message
        )
        or
		Array (
			[success] => true
        ) 
	*/
	public function readFileContent($path){
		$returnData = array();
		$returnData['error_text'] = array();
		if(is_file($path)){
			if(is_readable($path) && is_writable($path)){
				$fileContent = file_get_contents($path);
				$returnData['success'] = true;
				$returnData['file_content'] = $fileContent;
			} else {
				$returnData['error_text']['file_permissions'] = "Index file is not readable/writable.";
			}
		} else {
			$returnData['error_text']['invalid_file'] = "Index file path is not valid.";
		}
		return $returnData;
	}
	
	/**
	 * @method array writeFileContent : This function writes content to file mentioned in the param $path
	 *
	 * @param string $path : Path of the file that need to be write.
	 * @param string $fileContent : Content that need to be write in file
	 * 
	 * @return array : If the array has the key success than it means the operation was completed successfully else the error key will be set
	 * and all the error messages with different keys can be found
	 *
	 * @example  returnarray:
	 * Array (
			[error] => true,
            [error_text] => Array (
								[some_error_key] => some_error_text_message	
							),
			[some_other_error_key] => some_other_error_text_message
        )
        or
		Array (
			[success] => true
        ) 
	*/
	public function writeFileContent($path, $fileContent = NULL){
		$returnData = array();
		$returnData['error_text'] = array();
		if($fileContent != NULL){
			if(is_file($path)){
				if(is_writable($path)) {
					$handle = fopen($path, 'w');
					if(!$handle) {
						$returnData['error_text']['file_open'] = "Index file couldn't open for write operations";
					} else {
						if (fwrite($handle, $fileContent) === FALSE) {
							$returnData['error_text']['file_write'] = "Failed write operation while editing index file";
						} else {
							$returnData['success'] = true;
						}
					}
					fclose($handle);
				} else {
					$returnData['error_text']['file_permissions'] = "Index file is not writable.";
				}
			} else {
				$returnData['error_text']['invalid_file'] = "Index file path is not valid.";
			}	
		} else {
			$returnData['error_text']['invalid_file_content'] = "Index file content is null";
		}
		return $returnData;
	}
	
	public function getMMPFilesList($page_id = NULL, $type = 'sandbox') {
		$returnArray = array();
		$completeIndexFileName = "";
		$path = $this->MMP_SANDBOX_DIR . $page_id;
		if($type == "live"){
			$path = $this->MMP_LIVE_DIR . $page_id;
		}
		$filesArray = array();
		if($path != NULL) {
			if(is_dir($path)){
				$directoryStructure = scandir($path);
				foreach($directoryStructure as $fileOrDir){
					if($fileOrDir == "." || $fileOrDir == ".."){ // skips if the default . and .. directory encountered
						continue;
					} else {
						$completePathOfFile = $path . "/" . $fileOrDir;
						if(is_dir($completePathOfFile)) {
							$files = scandir($completePathOfFile);
							foreach($files as $f){
								if($f == "." || $f == ".."){
									continue;
								}
								$filesArray[$fileOrDir][$f] = $completePathOfFile."/".$f;
							}
						} else if(is_file($completePathOfFile)) {
							$filesArray[$fileOrDir] = $completePathOfFile;
						}
					}
				}	
			}
		} else {
			$returnArray['path'] = "some problem with directory path";
		}
		if(!empty($returnArray)){
			$returnArray['error'] = true;
			return $returnArray;
		} else {
			$returnArray = $filesArray;
			return $returnArray;
		}
	}
	
	public function p($data){
		if($this->SHOW_DEBUG_MESSAGES){
			echo "<pre>";
			print_r($data);
			echo "</pre>";	
		}
	}
}
?>
