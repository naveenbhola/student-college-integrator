export function getHtmlVersion(filename){	
	let incrementalVersion = '';
	let HTMLVERSION = window['HTMLVERSION'];
	if(window['HTMLREVISIONS'][filename]){
		incrementalVersion = window['HTMLREVISIONS'][filename];
	}
	let str = HTMLVERSION+incrementalVersion;
	if(str){
		return filename+'?q='+str;
	}
	return filename;
}