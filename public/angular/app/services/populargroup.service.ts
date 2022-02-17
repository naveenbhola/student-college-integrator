// Observable Version
import { Injectable }     from '@angular/core';
import { Http, Response } from '@angular/http';
import { Headers, RequestOptions } from '@angular/http';

import { Observable }     from 'rxjs/Observable';
import { ListingBaseService } from './listingbase.service';

@Injectable()
export class PopulargroupService extends ListingBaseService {
	constructor(http: Http) { super(http); }
	private populargroupUrl = 'listingBase/PopulargroupAdmin/submit'; 
	private populargroupGetUrl = 'listingBase/PopulargroupAdmin/getAllPopularGroups';  // URL to web API
	private populargroupSingleGetUrl = 'listingBase/PopulargroupAdmin/getPopularGroupById/';  // URL to web API
	populargroupList = [];

	addPopulargroup(result: any, mode: string, populargroupId: number) {
		result['mode'] = mode;
		result['populargroupId'] = populargroupId;
		let body = JSON.stringify({ result });
		let headers = new Headers({ 'Content-Type': 'application/json' });
		let options = new RequestOptions({ headers: headers });

		return this.http.post(this.populargroupUrl, body, options)
			.map(super.extractData)
			.catch(super.handleError);
	}

	getPopulargroupList(){
		return this.http.get(this.populargroupGetUrl).map(this.extractData).catch(this.handleError);
	}

	getPopulargroup(id){
		return this.http.get(this.populargroupSingleGetUrl+id).map(this.extractData).catch(this.handleError);
	}
}