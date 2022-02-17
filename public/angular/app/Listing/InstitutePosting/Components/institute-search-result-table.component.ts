import { Component,OnInit,Input,EventEmitter,Output,ViewChild } from '@angular/core';
import { InstituteListingService } from '../../../services/institute-listing-search.service';

import {ShikshaSelectComponent} from '../../../Common/Components/select/shiksha-select-component';
import { Subject }     from 'rxjs/Subject';
import { MY_VALIDATORS } from '../../../reusables/Validators';

import { RegisterFormModelDirective } from '../../../reusables/registerForm';
import {REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, NgForm} from "@angular/forms";

import {MODAL_DIRECTIVES, BS_VIEW_PROVIDERS} from 'ng2-bootstrap/ng2-bootstrap';
import {ModalDirective} from '../../../Common/Components/modal/modal.component';
import { InstituteDeleteService } from '../../../services/institute-delete.service';
import { InstitutePostingService } from '../../../services/institute-posting.service';

import { UserService } from '../../../Common/services/userservice';


import {Router,ROUTER_DIRECTIVES} from '@angular/router';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
	selector : "institute-search-result-table",
	inputs: ['model'],
	templateUrl: '/public/angular/app/Listing/InstitutePosting/Views/'+getHtmlVersion('instituteSearchResultTable.component.html'),
	directives:[ShikshaSelectComponent,RegisterFormModelDirective,ROUTER_DIRECTIVES,MODAL_DIRECTIVES,MY_VALIDATORS],
	providers:[BS_VIEW_PROVIDERS,InstituteDeleteService,InstituteListingService,InstitutePostingService]

})

export class instituteSRTable implements OnInit{

	migrateResponseurl:string="enterprise/Enterprise/index/790/";
	disabled_url:any='http://www.shiksha.com/';
	newInstituteId:any;
	submitPending: boolean = false;
	listingTypeId:number;
	listing_name:string;
	data:Array<Object> = [];
	confirmationMessage : any = ''; 
	firstButton : string = '';
	secondButton : string = '';
	listingStatus :string = '';
	instituteObject : any = {};
	callType : string = '';
	bodyMessage : any = '';
	errorMsg1:string;
	errorMsg2:string;
	errorMsg3:string;
	new_listing_name:string = '';
	disbalebodyMessage : any = '';
	courseList:any;
	prevCourseList:any;
	sortedCourseCount:number=0;
	sortCourses: number[]= [];
	newPosition = -1;
	tempArray1:Array<Object>=[];
	tempArray2:Array<Object>=[];
	listingName:string;
	isPaidListingArray : Array<Object> = [];
	isPaidListingSearch : Array<Object> = [];

	@Input() instituteResultsTable : Array<Object> = [];

	@Input() paginationHTML : any ;
	@Input() postingListingType : any = 'Institute';
	public openOptions : any = -1;

	controllerName:string = 'InstitutePosting';
	listingType:string = 'institute';

	userGroup : any;


	constructor(private instituteDelete: InstituteDeleteService,private instituteListingService : InstituteListingService,private router:Router,private institutePosting : InstitutePostingService,public userService: UserService){
			/*if(this.postingListingType == 'University')
			{
				this.controllerName = 'UnviersityPosting';
			}
			else
			{
				this.controllerName = 'InstitutePosting';
			}*/
		}

	ngOnInit()
	{
		this.userService.getUserData().subscribe((data) => {
			this.userGroup = data['userGroup'];
		});
	}

	openOptionsForInstitutions(instituteId : number,status : string,listingName : string)
	{
		this.listingType = this.postingListingType.charAt(0).toLowerCase() + this.postingListingType.slice(1);
		this.listingTypeId=instituteId;
		this.listingName = listingName;
		if(instituteId && this.openOptions != instituteId+'_'+status)
			this.openOptions = instituteId+'_'+status;
		else
			this.openOptions = -1;
	}

    @ViewChild('confirmationLayer') public confirmationLayer: ModalDirective;
 
    public hideDeleteConfirmationModal(){
    	this.fireEvent('delete','');
    }

     public hideDisableConfirmationModal(){
    	this.fireEvent('disable','');
    }	


    public validateListing(){
    		this.submitPending = true;
	    	if(this.newInstituteId == this.listingTypeId){
	    		this.errorMsg1='Entered institute id is same as deleted institue id.';
	    		this.submitPending = false;
	    	}else{
			     if (this.newInstituteId){
			            this.instituteDelete.validateInstituteData(this.newInstituteId,this.listingType).subscribe(
			               data => {this.submitPending = false;
			               			if(data.status == 'fail'){
			               				this.errorMsg1=data['message'];
			               			}else{
			               				this.new_listing_name=data['instName'];
			               				this.fireEvent('deleteConfirm','');
			               			} },
			                error => { this.submitPending = false;
			                		   alert('Internal error'); }
			            );
		         }else{
		         	this.submitPending = false;
		         	this.new_listing_name = '';
		         	this.fireEvent('deleteConfirm','');
		         } 
	        } 

	}

	
	public validateDisableUrl(){
		if(this.disabled_url == ''){
			this.errorMsg3='Please enter redirection url.';
		}
		else
		{   
			this.errorMsg3 = ''; 			    		
   			this.fireEvent('disableConfirm','');
		}
	}	


	public setListingDisableUrl(){	
	    		this.submitPending = true;
	            this.instituteDelete.setListingDisableUrl(this.listingTypeId,this.listingType,this.disabled_url).subscribe(
	               data => { this.submitPending = false;
	               			 if(data.status == 'fail'){
	               			 	alert(data['message']);
	               			 }else{
	               			 	alert(data['message']);
	               			 	this.backToHome();

	               			 }},
	               	error => {this.submitPending = false;
	               			alert('Internal error'); }
	            );   
	}

	public deleteInstituteListing(){
			this.submitPending = true;
			this.instituteDelete.deleteInstituteListing(this.listingTypeId,this.newInstituteId,this.listingType,this.new_listing_name).subscribe(
               	data => {this.submitPending = false;
               			 if(data.status == 'fail'){
               			 	alert(data['message']);
               			 	this.hideDeleteConfirmationModal();
               			 }else{
               			 	alert(data['message']);
               			 	this.backToHome();
               			 } },
                error => { alert('Internal error'); }
            );

	}

	fireEvent(event : any,listingStatus: string)
	{
		 this.institutePosting.isAllowUserAction(this.listingTypeId,this.postingListingType).subscribe(
            data => {
              if(!data['isUserAllowEdit'])
              {
                  alert('Another Person is in Editing Mode');

              }
              else{
              	this.callEvent(event,listingStatus);
              }
            }
         );
	}
	callEvent(event : any,listingStatus: string)
	{
		this.callType = event;
		if(event == 'makeLive')
		{
			this.confirmationMessage = "Are you sure you want to make "+this.listingName+" live?";
			this.firstButton = "Yes";
			this.secondButton = "No";
			this.listingStatus = listingStatus;	
			this.bodyMessage = '';
			this.disbalebodyMessage = '';
			this.confirmationLayer.show();

		}
		else if(event == 'delete')
		{
			this.instituteListingService.checkIsListingPaid(this.listingTypeId,this.postingListingType).subscribe(data => {
					if(data.msg) 
					{
						alert('something went wrong.');
					}
					else
					{
						if(data.isPaid)
						{
							this.deletedPaidAlert.show();
						}
						else
						{
							this.freeListingDelete();
							this.confirmationLayer.show();
						}
				}
				});
		}
		else if(event == 'deleteConfirm'){
			if(this.new_listing_name != ''){
				this.confirmationMessage = 'Are you sure you want to map all Q&A, articles and redirect users of '+this.listingName+' to: '+this.new_listing_name+'?';
			}else{
				this.confirmationMessage = 'Are you sure you want to delete '+this.listingName+'?';
			}
			this.firstButton = "Confirm and Delete";
			this.secondButton = "Go Back";
			this.bodyMessage = '';
			this.disbalebodyMessage = '';
			this.confirmationLayer.show();
		}
		else if(event == 'disable')
		{
			this.confirmationMessage = 'Are you sure you want to disable '+this.listingName+' and all the courses mapped directly to it?';
			this.disbalebodyMessage = 'true';
			this.bodyMessage = '';
			this.secondButton = '';
			this.firstButton = 'Disable '+this.listingType;
			this.confirmationLayer.show();
		}
		else if(event == 'disableConfirm'){
			this.confirmationMessage = 'There are other institute-type entities mapped to '+this.listingName+'. Disabling it will disable all those institutes and their courses. Are you sure you want to disable them all?';
			this.disbalebodyMessage = '';
			this.bodyMessage = '';
			this.secondButton = 'No';
			this.firstButton = 'Yes';
			this.confirmationLayer.show();
		}
	}
	@ViewChild('deletedPaidAlert') public deletedPaidAlert: ModalDirective;

	freeListingDelete()
	{
		this.confirmationMessage = 'Are you sure you want to delete '+this.listingName+' and all the courses mapped directly to it?';
			this.firstButton = "Delete "+this.listingType;
			// this.secondButton = "Migrate Responses";
			this.bodyMessage = 'true';
			this.newInstituteId = '';
		    this.errorMsg1='';
		    this.errorMsg2='';
		    this.disbalebodyMessage = '';
	}
	firstButtonEvent()
	{
		if(this.callType == 'makeLive')
		{
			this.makeLive();
		}
		else if(this.callType == 'delete'){
			this.validateListing();
		}
		else if(this.callType == 'deleteConfirm')
		{
			this.deleteInstituteListing();
		}
		else if(this.callType == 'disable')
		{
			this.validateDisableUrl();
		}
		else if(this.callType == 'disableConfirm')
		{
			this.setListingDisableUrl();
		}
	}
	secondButtonEvent()
	{
		if(this.callType == 'makeLive')
		{
			this.confirmationLayer.hide();
		}
		else if(this.callType == 'delete')
		{
			//this.router.navigate(this.migrateResponseurl);
		}
		else if(this.callType == 'deleteConfirm')
		{
			this.hideDeleteConfirmationModal();
		}
		else if(this.callType == 'disableConfirm')
		{
			this.hideDisableConfirmationModal();
		}
	}
	
	backToHome(){
		if(this.postingListingType == 'University')
		{
			location.href= "/nationalInstitute/UniversityPosting/viewList";	
		}
		else
		{
			location.href= "/nationalInstitute/InstitutePosting/viewList";	
		}
    } 

    makeLive()
    {
    	this.instituteObject['instituteId'] = this.listingTypeId;
		this.instituteObject['listingStatus'] = this.listingStatus;
		this.instituteObject['postingListingType'] = this.postingListingType;
		this.submitPending = true;
		this.instituteListingService.makeListingLive(this.instituteObject).subscribe( data => {alert(data['msg']);this.submitPending = false;this.backToHome();},
			error => {alert('Internal Error');this.submitPending = false;this.backToHome();});
    }
    convertToMakeLive(instituteId : number,is_dummy : any, draftCheckStatus : string)
    {
    	if(draftCheckStatus == 'live')
    	{
    		this.instituteListingService.checkDraftEntryExist(instituteId).subscribe( data => {if(data['showResponse']){alert('A draft state of this '+this.postingListingType+' already exists')}},
    			error => {alert('Internal Error');this.backToHome();});
    	}
    	let navigationExtras = {
        queryParams: { 'is_dummy': is_dummy }
      };
      if(this.postingListingType == 'University')
      {
      	this.router.navigate(['nationalInstitute/UniversityPosting/create',instituteId], navigationExtras);	
      }
      else
      {
      	this.router.navigate(['nationalInstitute/InstitutePosting/create',instituteId], navigationExtras);		
      }
      
    }

    @ViewChild('courseListModal') public courseListModal: ModalDirective;
 
    public showCourseListModal(){
    	this.courseListModal.show();
    }	

    public hideCourseListModal(){
    	this.sortedCourseCount = 0;
    	this.courseListModal.hide();
    }

    public getCoursesOfInstForOrdering(){
    	this.submitPending = true;
    	this.sortedCourseCount = 0;
		this.instituteListingService.getCoursesForOrdering(this.listingTypeId).subscribe(
               	data => {
               			 if(data.status == 'fail'){
               			 	alert(data['message']);
               			 }else{
               			 	this.courseList = data.result;
               			 	this.prevCourseList = this.courseList;
               			 	for(let i=0;i <this.courseList.length;i++){
               			 		if(this.courseList[i].course_order>0){
               			 			this.sortedCourseCount++;
               			 		}
               			 	}
               			 	this.showCourseListModal();
               			 }
						  this.submitPending = false; },
                error => {this.submitPending = false;
                		  alert('Internal error'); }
            );
		
    }

    public createSortRange(count){
      this.sortCourses = [];
	  for(let i = 1; i <=count+1; i++){
	    this.sortCourses.push(i);
	  }
	  return this.sortCourses;
	}

    public setCourseOrdering(){
			this.instituteListingService.updateCourseOrdering(this.listingTypeId,this.listingType,this.courseList,this.prevCourseList).subscribe(
               	data => {
               			 if(data.status == 'fail'){
               			 	alert('something went wrong.');
               			 }else{
               			 	this.backToHome();
               			 } },
                error => { alert('Internal error'); }
            );
	}


    public changeCourseOrder(currentPosition,newPosition,course_id){
        
	    let courseOrderList = [];
	    for(let course of this.courseList){
	        courseOrderList.push(Object.assign({},course));
	    }
	   
	    for(let key in courseOrderList){
	    	if(newPosition>this.sortedCourseCount){
	    		if(courseOrderList[key]['course_id'] == course_id){
		    		if(courseOrderList[key]['course_order'] != 0){
		    			courseOrderList[key]['course_order'] = currentPosition;
		    		}else{
		    			courseOrderList[key]['course_order'] = newPosition;
		    		}
		    	}
	    	}else{
	    		if(parseInt(newPosition) == 0){
	    			if(parseInt(key)>=parseInt(currentPosition) && parseInt(key)<this.sortedCourseCount){
						courseOrderList[key]['course_order']--; 
		          	}
		        }else if(parseInt(currentPosition) == 0){
		        	if(parseInt(key)>=newPosition-1 && parseInt(key)<this.sortedCourseCount){
		        		courseOrderList[key]['course_order']++; 
		        	}
		        }else if(parseInt(newPosition) < parseInt(currentPosition)){
		        	if(parseInt(key)>=newPosition-1 && parseInt(key)<currentPosition-1){
		        		courseOrderList[key]['course_order']++; 
		        	}
		        }else if(parseInt(newPosition)>parseInt(currentPosition)){
		        	if(parseInt(key)>=currentPosition && parseInt(key)<newPosition){
		        		courseOrderList[key]['course_order']--; 
		        	}
		        }

		        if(courseOrderList[key]['course_id'] == course_id){
		          	courseOrderList[key]['course_order'] = newPosition; 		
		         }

	    	}
	    }

	    if(newPosition == 0){
	    	this.sortedCourseCount--;	
	    }else if(currentPosition == 0){
	    	this.sortedCourseCount++;	
	    } 

	    this.tempArray1 = [];
	    this.tempArray2 = [];
	    for(let i in courseOrderList){
	    	if(parseInt(courseOrderList[i]['course_order'])>0){
	    		this.tempArray1.push(courseOrderList[i]);
	    	}else{
	    		this.tempArray2.push(courseOrderList[i]);
	    	}
	    }
	    
	    this.tempArray1.sort((a,b) => {	      
          if(parseInt(a['course_order'])<parseInt(b['course_order'])){
          	return -1}
          if(parseInt(a['course_order'])>parseInt(b['course_order'])){
          	return 1}
          return 0;
        }); 

        this.courseList = this.tempArray1.concat(this.tempArray2);
    }

    isListingAdmin() {
		return (this.userGroup == 'listingAdmin') ? true : false;
	}

	isListingPaid(listingId,listingType)
	{
		this.isPaidListingSearch[listingId] = true;
		this.instituteListingService.checkIsListingPaid(listingId,listingType).subscribe(data => {

			if(data.msg) 
			{
				alert('something went wrong.');
				this.isPaidListingSearch[listingId] = false;
			}
			else
			{
				if(data.isPaid)
					this.isPaidListingArray[listingId] = 'Paid';
				else
					this.isPaidListingArray[listingId] = 'Free';	
			}
			
			
		})
	}

}
