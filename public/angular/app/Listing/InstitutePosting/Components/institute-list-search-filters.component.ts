import { Component,OnInit, ViewChild,Input ,DynamicComponentLoader} from '@angular/core';
import { InstituteListingService } from '../../../services/institute-listing-search.service';

import {ShikshaSelectComponent} from '../../../Common/Components/select/shiksha-select-component';
import { Subject }     from 'rxjs/Subject';

import { RegisterFormModelDirective } from '../../../reusables/registerForm';
import {REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, NgForm} from "@angular/forms";

import { instituteSRTable } from './institute-search-result-table.component';

import {pagintionHTML} from '../../../reusables/pagination.component';

import {paginationService } from '../../../reusables/pagination.service';

import { Router } from '@angular/router';

import {ROUTER_DIRECTIVES} from '@angular/router';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';


@Component({
	selector : "institute-search-filters",

	templateUrl: '/public/angular/app/Listing/InstitutePosting/Views/'+getHtmlVersion('instituteListSearchFilters.component.html'),
	directives:[ShikshaSelectComponent,RegisterFormModelDirective, instituteSRTable,pagintionHTML,ROUTER_DIRECTIVES],
	providers: [InstituteListingService,paginationService]
})

export class InstituteListingSearchFilters implements OnInit {
 	opened: Boolean = true;
 	instituteTypeList = [];
 	instituteStatusList = ["Live", "Draft", "Dummy", "Disabled"];

  public instituteId : any;
  public universityId : any;

  public instituteFiltersObj: any = { };

  public totalResultCount : number = 0;
  public paginationNum : number ; //Input

  //number of results to show per every pagination
  public paginationResultsShow : number = 25;

 	//using for autosuggestor
 	autosuggestorSource;
 	autosuggestorObservable;

   public checkedFlag : boolean = true;

 	public instituteAutoSuggestorItems:Array<Object> = [];
  public instituteResultsTable:Array<Object> = [];
  private instituteAutoSuggestorSuggestedItem:any = {};
  public  paginationHTML : any ;

  
  public totalPages:number;
  public paginationLimit : number = 5; //number of pagination 
  public startPage : number = 1;//startPage number in pagination
  public endPage : number ;//endPage number in pagination
  public paginationArray : Array<Object> = [];

  @Input() postingListingType;

 	constructor(public instituteListingService: InstituteListingService,private router: Router,private paginationService:paginationService){}
 	ngOnInit()
 	{
     if(this.postingListingType == 'Institute')
     {
        this.instituteListingService.getInstituteTypesInShiksha().subscribe(
            instituteTypeList => { this.instituteTypeList = instituteTypeList;console.log(instituteTypeList);},
             error => {alert('Failed to get institute types data');}
        );   
     }
 		

 	  this.autosuggestorSource = new Subject();
      this.autosuggestorObservable = this.autosuggestorSource.asObservable();
      this.autosuggestorObservable.debounceTime(400).map((data) => { if (typeof data == 'string') { data = data.trim(); } if (data) { return data; } }).subscribe(typedText => { if (typedText) { this.getInstituteParentSuggestion(typedText); } });
 		this.populateInstitutesBasedOnFilters();
 	}

 	 getInstituteParentSuggestion(typedText){     
        let suggestionType : any;
          if(this.postingListingType == 'University') 
          {
              suggestionType = this.postingListingType;
          }
          else{
            suggestionType = '';
          }
         this.instituteListingService.getInstituteAutosuggestor(typedText,suggestionType).subscribe(data => this.fillInstituteItems(data)      
           ,err => err,
         () => console.log('Authentication Complete'));
    }

    fillInstituteItems(data){
    this.instituteAutoSuggestorItems  = [];
      for(let i in data){
        for(let y in data[i]){
            this.instituteAutoSuggestorItems.push({'text':data[i][y],'id':i+"_"+y});
        }          
      }
    }

      public selected(value:any):void {
        if(typeof value == "string")
        {
            this.instituteFiltersObj['openSearch'] = value;
            this.instituteFiltersObj['instituteId'] = '';
            this.instituteFiltersObj['universityId'] = '';
            this.universityId = '';
            this.instituteId = '';
        }
        else
        {
            let instituteString = value.id.split("_");      
            if(instituteString.length > 0)
                this.instituteFiltersObj[instituteString[0]] = instituteString[1];

            if(instituteString[0] == 'institute')
            {
              this.instituteFiltersObj['university'] = '';
            }
            else{
               this.instituteFiltersObj['institute'] = ''; 
            }

            this.instituteFiltersObj['openSearch'] = '';
            this.instituteFiltersObj['instituteId'] = '';
            this.instituteFiltersObj['universityId'] = '';
            this.universityId = '';
            this.instituteId = '';
        }

        this.populateInstitutesBasedOnFilters();
      }

    public removed(value:any):void {
      console.log('Removed value is: ', value);
      /*let instituteString = value.id.split("_");      
              if(instituteString.length > 0)
                  this.instituteFiltersObj[instituteString[0]] = '';*/
      this.instituteFiltersObj['university'] = '';
      this.instituteFiltersObj['institute'] = ''; 
      this.instituteAutoSuggestorItems = [];
    }

    public typed(value:any):void {
      if(!value){
        setTimeout(() => {this.instituteAutoSuggestorItems = [];},0);
      }else{
          this.autosuggestorSource.next(value);    
      }  
    }

    public refreshValue(value:any):void {
      this.instituteAutoSuggestorSuggestedItem = value;
    }
    
    instituteListFilters(Id:any , typeOfValue : string)
    {
      if(typeOfValue == 'universityId')
      {
        this.instituteFiltersObj[typeOfValue] = this.universityId;  
        this.instituteFiltersObj['instituteId'] = '';  
        this.instituteFiltersObj['openSearch'] = '';
        this.instituteId = '';
      }
      else if(typeOfValue == 'instituteId')
      {
        this.instituteFiltersObj[typeOfValue] = this.instituteId;  
        this.instituteFiltersObj['universityId'] = ''; 
        this.instituteFiltersObj['openSearch'] = '';
        this.universityId = '';
      }
      else
    	  this.instituteFiltersObj[typeOfValue] = Id;
      
        this.populateInstitutesBasedOnFilters();
    }

    //below function is used for getting institutes information based on search and filters
    populateInstitutesBasedOnFilters(pageNumber:number = 1){
        this.instituteFiltersObj['pageNumber'] = pageNumber;
        this.instituteFiltersObj['fetchListType'] = this.postingListingType;
        this.instituteListingService.populateInstitutesBasedOnFilters(this.instituteFiltersObj).subscribe(data => this.fillResultTableResults(data)      
           ,err => err,
         () => console.log('Authentication Complete'));      
    }

    //below function is used for displaying institutes information in table
     fillResultTableResults(data){
      this.instituteResultsTable  = [];
      this.totalResultCount = data['totalCount'] ? data['totalCount'] : 0;
      this.paginationNum = +data['paginationNum'];
        for(let i in data['data']){
            data['data'][i]['showStatus'] = (data['data'][i]['is_dummy'] == 1 ? 'dummy' : data['data'][i]['status']);
            data['data'][i]['showStatus'] = (data['data'][i]['disabled_url'] ? 'disabled' : data['data'][i]['showStatus']);
              this.instituteResultsTable.push(data['data'][i]);
        }
        this.paginationArray = this.paginationService.paginationLogic(this.totalResultCount,this.paginationResultsShow,this.paginationNum,this.paginationLimit);
        this.totalPages = this.paginationArray['totalPages'];
        this.startPage = this.paginationArray['startPage'];
        this.endPage = this.paginationArray['endPage'];
        this.changeDetected();
    }
    //below function will get information based on pagination request
    paginationRequest(pageNumber :number)
    {
        if(pageNumber)
          this.populateInstitutesBasedOnFilters(pageNumber);
    }
    //below function is used for refresh content of pagination html on view page
    changeDetected()
    {
      this.checkedFlag = false;
      setTimeout(() => {this.checkedFlag = true;},20);
    }
    openDummyInstitute(is_dummy : string)
    {
      let navigationExtras = {
        queryParams: { 'is_dummy': is_dummy }
      };
      if(this.postingListingType == 'University')
      {
        this.router.navigate(['nationalInstitute/UniversityPosting/create'], navigationExtras);
      }
      else
      {
        this.router.navigate(['nationalInstitute/InstitutePosting/create'], navigationExtras);
      }

    }
 }
