import { Injectable }     from '@angular/core';
import { Http, Response } from '@angular/http';
import { Observable }     from 'rxjs/Rx';

@Injectable()

export class UserService {
	userDataUrl = 'nationalCourse/CoursePosting/getUserData';
	entityAccessUserList = [];
	userData;

	constructor(public http: Http) {}

	getUserData(){
		if(this.getCookieData('backendsessiontimeout') && this.userData){
			return Observable.of(this.userData);
		}
		return this.http.get(this.userDataUrl).map(this.extractData).map((data)=>{
			if(data){
				this.userData = data;
				let expireTime = new Date(new Date().getTime() + 3*60*1000);
				document.cookie = 'backendsessiontimeout='+Math.random()+';path=/;expires='+expireTime['toGMTString']();
				return data;
			}
		});
	}

	getCookieData(c_name){
		if (document.cookie.length>0){
		    let c_start=document.cookie.indexOf(c_name + "=");
		    if (c_start!=-1){
		        c_start=c_start + c_name.length+1;
		        let c_end=document.cookie.indexOf(";",c_start);
		        if (c_end==-1) { c_end=document.cookie.length ; }
		        return document.cookie.substring(c_start,c_end);
		    }
		}
		return "";
	}

	extractData(res: Response) {
		let body = res.json();
		return body.data || {};
	}

	handleError(error: any) {
		// In a real world app, we might use a remote logging infrastructure
		// We'd also dig deeper into the error to get a better message
		let errMsg = (error.message) ? error.message :
			error.status ? `${error.status} - ${error.statusText}` : 'Server error';
		console.error(errMsg); // log to console instead
		return Observable.throw(errMsg);
	}
}