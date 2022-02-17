// Observable Version
import { Injectable } from '@angular/core';
import { Http, Response } from '@angular/http';
import { Headers, RequestOptions } from '@angular/http';

import { Observable } from 'rxjs/Observable';
import { ListingBaseService } from './listingbase.service';

@Injectable()
export class CategorySeoService extends ListingBaseService {
	constructor(http: Http) { super(http); }
	private categoryDetailsCheckUrl = 'nationalCategoryList/CategoryPageSeoEnterprise/getSEODetails';
	private categoryDetailsSubmitUrl = 'nationalCategoryList/CategoryPageSeoEnterprise/submitSEODetails';
	private orderingURL = 'nationalCategoryList/CategoryPageSeoEnterprise/submitOrderedURLs';
	private categoryURLs = 'nationalCategoryList/CategoryPageSeoEnterprise/getCategoryURLs';
	private pingTestURL = 'nationalCategoryList/CategoryPageSeoEnterprise/pingTest';

	private static options = new RequestOptions({
		headers:
		new Headers({ 'Content-Type': 'application/json' })
	});

	public getBaseURL() { // Must be needed by multiple methods / modules. Hence this needs to be moved to listingbaseservice
		return 'http://www.shiksha.com/';
	}

	public getSeoDetails(inputCombination: any) {
		let body = JSON.stringify({ inputCombination });
		return this.http.post(this.categoryDetailsCheckUrl, body, CategorySeoService.options)
			.map(super.extractData)
			.catch(super.handleError);
	}

	public getCategoryURLs(inputCombination: any) {

		if (inputCombination != '') {
			let body = JSON.stringify({ inputCombination });
			return this.http.post(this.categoryURLs, body, CategorySeoService.options).map(this.extractData).catch(super.handleError);
		}
		return this.http.get(this.categoryURLs).map(this.extractData).catch(super.handleError);
	}

	public submitURL(categoryData: any, mode: string) {

		let body = JSON.stringify({ categoryData });

		return this.http.post(this.categoryDetailsSubmitUrl + "/" + mode, body, CategorySeoService.options).map(super.extractData).catch(super.handleError);

	}

	public rearrangeURL(orderedURLs: any) {
		let body = JSON.stringify({ orderedURLs });

		return this.http.post(this.orderingURL, body, CategorySeoService.options).map(super.extractData).catch(super.handleError);
	}

	public deleteURL(categoryData: any) {
		let body = JSON.stringify({ categoryData });

		return this.http.post(this.categoryDetailsSubmitUrl, body, CategorySeoService.options).map(super.extractData).catch(super.handleError);
	}

	public ping(url: string) {
		return this.http.post(this.pingTestURL, { url: url }, CategorySeoService.options).map(super.extractData).catch(super.handleError);
	}

}