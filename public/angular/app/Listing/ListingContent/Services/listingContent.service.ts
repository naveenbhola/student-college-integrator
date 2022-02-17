import {Injectable} from '@angular/core';
import { Http, Response,Headers, RequestOptions } from '@angular/http';
import { Observable }     from 'rxjs/Observable';

import { ListingBaseService } from '../../../services/listingbase.service';

@Injectable()

export class listingContentService extends ListingBaseService{

	constructor(http: Http) { super(http); }

	fetchCourseUrl  = '/nationalInstitute/InstitutePosting/getListingCourses';
	saveListingContentUrl = '/nationalInstitute/InstitutePosting/saveListingContent';
	fetchArticleInfo = '/nationalInstitute/InstitutePosting/getArticleInfo';
	resetOptionUrl = '/nationalInstitute/InstitutePosting/listingContentReset';

	getListingInfo(listingId,listingContentType)
	{
		let body = 'listingId='+listingId+'&listingContentType='+listingContentType;

		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.fetchCourseUrl,body,options).map(this.extractData).catch(this.handleError);
	}	
	saveListingContent(listingId,listingType,popularCourse,duration,listingContentType,articleId)
	{
		let body = 'listingId='+listingId+'&listingType='+listingType+'&popularCourse='+popularCourse+'&duration='+duration+'&listingContentType='+listingContentType+'&articleId='+articleId;
		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.saveListingContentUrl,body,options).map(this.extractData).catch(this.handleError);
	}	
	getArticleInfo(articleId,listingId,listingType)
	{
		let body = "articleId="+articleId+'&listingId='+listingId+'&listingType='+listingType;

		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded'});
		let options = new RequestOptions({ headers: headers });
		return this.http.post(this.fetchArticleInfo,body,options).map(this.extractData).catch(this.handleError);
	}
	resetOptions(listingId,listingType,pageTitle)
	{
		let body = "listingId="+listingId+'&listingType='+listingType+'&pageTitle='+pageTitle;
		let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded'});
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.resetOptionUrl,body,options).map(this.extractData).catch(this.handleError);

	}

}