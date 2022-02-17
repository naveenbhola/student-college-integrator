import config from './../../../config/config';
import coursePageCSS from './../../../views/amp/pages/course/asset/coursePageCSS';
import institutePageCSS from './../../../views/amp/pages/institute/asset/institutePageCss';

export default function ScriptList(stateData, fromWhere){
	const domainName = config().SHIKSHA_HOME;
	var string = '';
	if(fromWhere == "coursePage"){
		string = coursePageCSS();
	}else{
		string = institutePageCSS();
	}
	return string;
}
