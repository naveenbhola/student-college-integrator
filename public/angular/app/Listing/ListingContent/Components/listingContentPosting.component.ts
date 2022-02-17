import {Component,Input,OnInit} from '@angular/core';
import {listingContentService} from '../Services/listingContent.service';

import { RangeArrayPipe } from '../../../pipes/arraypipes.pipe';

import { SortArrayPipe } from '../../../pipes/arraypipes.pipe'

import { MY_VALIDATORS } from '../../../reusables/Validators';
import { RegisterFormModelDirective } from '../../../reusables/registerForm';
import {REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, NgForm} from "@angular/forms";
import { SidebarService } from '../../../services/sidebar-service';
import { WarningModal } from '../../../reusables/warningModal';
//import { DatePicker } from 'ng2-datepicker/ng2-datepicker';

import { FormBuilder } from '@angular/forms';

@Component({
	selector: 'listingContent',
	templateUrl : '/public/angular/app/Listing/ListingContent/Views/listingContent.component.html',
	directives:[MY_VALIDATORS,REACTIVE_FORM_DIRECTIVES, RegisterFormModelDirective,WarningModal],
	providers: [listingContentService],
	pipes: [RangeArrayPipe,SortArrayPipe]
})

export class listingContentComponent implements OnInit{


	@Input() pageTitle = 'Popular';
	listingId : number;
	articleId : any;
	listingName : string = '';
	errorMsg: string = '';
	numberOfCoursesOption : number = 5;
	courseList : any;
	popularCourse= [];
	errorCourse = [];
	expiryDate: any;
	duration = [{'value' : 1,'label':'1 Month'},{'value' : 3,'label':'3 Month'},{'value' : 6,'label':'6 Month'}];
	cmsDuration : number = 0;
	changeFlag : boolean = true;
	articleDuration:number = 30;

	dataForm: FormGroup;

	submitPending : boolean = false;
	listingType : string = '';
	getListingId : number;
	getArticleId : number;
	redirectUrl = '/nationalInstitute/InstitutePosting/cmsPopularCourses';
	date : any;
	articleDurationShow:boolean = false;

	articleErrorMsg : string  = '';
	articleName : string = '';
	private _sidebarflag = false;
	showLoader : boolean = false;
	clearOption : boolean = false;
	isModalOpened : boolean  = false;


	constructor(private listingContentService : listingContentService,private sidebarService:SidebarService,private formBuilder: FormBuilder)
	{
		this.sidebarService.showLinks([]);
		setTimeout(() => {this.sidebarService.updateLinks({'activeLink':'listingContent','subLink':'popularCourse'}); },100);
		// this.dataForm = this.formBuilder.group({
	 //  			date: ''
		// });
		this.dataForm = new FormGroup({'date':new FormControl('')});console.log(this.dataForm);
	}

	/*set sidebarflag(val:any){
      this._sidebarflag = val;
      console.log('val');
      console.log(val);
      //this.sidebarService.showLinks([]);
      setTimeout(() => {this.sidebarService.updateLinks({'activeLink':'listingContent'}); },100);
    }

    get sidebarflag(){
      return this._sidebarflag;
    }*/


	ngOnInit()
	{
		if(this.pageTitle == 'Featured')
		{
			this.numberOfCoursesOption = 5;
			this.redirectUrl = '/nationalInstitute/InstitutePosting/cmsFeaturedCourses';
		}
		if(this.pageTitle == 'Article')
		{
			this.redirectUrl = '/nationalInstitute/InstitutePosting/cmsFeaturedArticle';
		}
		setTimeout(() => {this.sidebarService.updateLinks({'activeLink':'listingContent'}); },100);
		
	}
	getInstituteInfo()
	{
		this.expiryDate = '';
		this.articleErrorMsg = '';
		this.errorMsg = '';
		this.changeDetected();
		this.popularCourse= [0,0,0,0,0,0];
		this.errorCourse = [];
		this.clearOption = false;

		this.cmsDuration = 0;
		
		this.listingName = '';
		this.getListingId = this.listingId;
		this.listingType = '';
		this.showLoader = true;
		this.listingContentService.getListingInfo(this.getListingId,this.pageTitle).subscribe(data => this.fillData(data)
           ,err => err,
         () => console.log('Authentication Complete'));
	}
	fillData(data)
	{
		this.showLoader = false;
		if(data['listingName'] == 'undefined' || data['listingName'] == '')
		{
			this.errorMsg = 'No Listing Exist Based on this Id';
			this.listingName = '';
			this.articleName  = '';
		}
		else
		{	
			this.listingName = data['listingName'];
			this.listingType = data['listingType'];
			if(data['courses'] != null){
				this.errorMsg = '';
			}else if(this.pageTitle != 'Article'){
				this.errorMsg = 'No Courses available.';
			}		
			if(this.pageTitle == 'Article')
			{
				this.articleId = data['articleId'];
				if(this.articleId != '' && this.articleId != null)
					this.getArticleId = data['articleId'];
				this.articleName = data['articleName'];

				if(this.articleName != '')
				{
					this.articleDurationShow = true;
					this.clearOption = true;
				}
				else
					this.articleDurationShow = false;
			}
			
		
		}
		for(let i in data['courseOrder'])
		{
			this.popularCourse[i] = data['courseOrder'][i];
			this.clearOption = true;
		}
		this.expiryDate = data['expiryDate'];
		this.courseList = data['courses'];
		

	}
	checkOtherCourseOrder(event,order)
	{
		let found = false;
		let j = 0;
		for(let i in this.popularCourse)
		{
			if(event == this.popularCourse[i] && this.popularCourse[i] != 0)
			{	
				j++;
				if(j > 1)
					break;
			}	
		}
		if(j > 1)
			this.errorCourse[order] = 'Already selected this course';
		else
			this.errorCourse[order] = '';
	}
	popularCourseOrder(event,order)
	{
		let found = false;
		for(let i in this.popularCourse)
		{
			if(event == this.popularCourse[i] && this.popularCourse[i] != 0)
			{	
				found = true;
			}	
		}
		if(found)
		{
			this.errorCourse[order] = 'Already selected this course';
		}
		else
		{
			this.errorCourse[order] = '';	
		}
		this.popularCourse[order] = event;
		for(let i in this.popularCourse)
		{
			this.checkOtherCourseOrder(this.popularCourse[i],i);
		}
	}
	saveListingContent()
	{
		let flag = true;
		let flag1 = false;
		for(let i in this.errorCourse)
		{
			if(this.errorCourse[i] != '')
				flag = false;
		}
		for(let j in this.popularCourse)
		{
			if(!flag1 && this.popularCourse[j] != 0)
				flag1 = true;
		}
		if((flag && flag1 )|| this.pageTitle == 'Article')
		{
			this.submitPending = true;
			this.listingContentService.saveListingContent(this.getListingId,this.listingType,this.popularCourse,this.cmsDuration,this.pageTitle,this.getArticleId).subscribe(data =>
			{
				alert(data['msg']);
				location.href = this.redirectUrl;

			}
	           ,err => err,
	         () => console.log('Authentication Complete'));
		}
	}
	changeDetected()
	{
		this.changeFlag = false;
		setTimeout(() => {this.changeFlag = true},0);
	}
	getArticleInfo()
	{
		this.getArticleId = this.articleId;
		this.expiryDate = '';
		this.listingContentService.getArticleInfo(this.getArticleId,this.getListingId,this.listingType).subscribe(data => {
			if(data['status'])
			{
				this.articleErrorMsg = '';
				this.articleName = data['articleName'];
				this.articleDurationShow = true;
			}
			else
			{
				this.articleDurationShow = false;
				this.articleName = data['articleName'];
				this.articleErrorMsg = data['msg'];
			}
		},err => err,() => console.log('Authentication Complete') );
	}
	/*toggle(number)
	{
		console.log(number);
		let el = document.getElementById(number).style.display  == 'block' ? 'hi ' : 'bhye';
		console.log(el);
	}*/
	resetOptions()
	{
		this.isModalOpened = true;
	}
	resetSticky(response)
	{
		if(response == 'yes')
		{
				this.listingContentService.resetOptions(this.getListingId,this.listingType,this.pageTitle).subscribe(data => {
				if(data['status'])
				{
					this.popularCourse= [0,0,0,0,0,0];
					this.expiryDate = '';
					this.clearOption = false;
					this.articleId = '';
					this.articleName = '';
					this.articleDurationShow = false;
				}
				alert(data['msg']);		
			});
		}
		else{
			this.isModalOpened = false;
		}
		
	}
}