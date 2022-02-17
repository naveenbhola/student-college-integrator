import { Component,OnInit,Input,ViewChild } from '@angular/core';
import {Posting} from '../../../Common/Classes/Posting';
import {ShikshaSelectComponent} from '../../../Common/Components/select/shiksha-select-component';
import { Observable }     from 'rxjs/Rx';
import { Subject }     from 'rxjs/Subject';
import { InstitutePostingService } from '../../../services/institute-posting.service';

import { WarningModal } from '../../../reusables/warningModal';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
	selector : "recruitingcompanies-posting",
	templateUrl: '/public/angular/app/Listing/InstitutePosting/Views/'+getHtmlVersion('recruitingcompaniesposting.component.html'),
  directives:[ShikshaSelectComponent,WarningModal],
  providers:[InstitutePostingService]
})

export class RecruitingCompaniesPostingComponent extends Posting implements OnInit{

	@Input() instituteObj;
  @Input() mode;
    addedRecuritingCompanies = [];
    recuritingInitData = [];
    autosuggestorSource;  
    autosuggestorObservable;
    public recruitingAutoSuggestorItems:Array<Object> = [];
    // private recruitingAutoSuggestorSuggestedItem:any = {};
    private _disabledV:string = '0';
    private disabled:boolean = false;
    showAutosuggestor = true;
    lastTyped = '';


    constructor(private institutePosting: InstitutePostingService){
    	super();
    }

  	ngOnInit(){
  	    this.autosuggestorSource = new Subject();
        this.autosuggestorObservable = this.autosuggestorSource.asObservable();
        this.autosuggestorObservable.debounceTime(400).map((data) => { if (typeof data == 'string') { data = data.trim(); } if (data) { return data; } }).subscribe(typedText => { if (typedText) { this.getCompaniesSuggestion(typedText); } });
        for(let i in this.instituteObj.companies){
          this.addedRecuritingCompanies.push(this.instituteObj.companies[i]['company_id'])
        }
        // this.addedRecuritingCompanies = this.instituteObj.companies;
  	}

	  

    public selectedRC(value:any):void {
       this.instituteObj.addCompany({company_id:value.id,company_name:value.text});
       this.addedRecuritingCompanies.push(value.id);
       this.showAutosuggestor = false;
       setTimeout(() => {this.showAutosuggestor = true;},0);
       this.recruitingAutoSuggestorItems = [];
    }


    public typed(value:any):void {
      if(this.lastTyped && this.lastTyped == value){
        return;
      }
      this.lastTyped = value;
      if(!value){
        this.recruitingAutoSuggestorItems = [];
      }else{
          this.autosuggestorSource.next(value);    
      }  
    }

    public removedRC(value:any):void {
      this.recruitingAutoSuggestorItems = [];
    }

    getCompaniesSuggestion(keyword){
      if(!keyword){
        return;
      }
      this.recruitingAutoSuggestorItems  = [];
    	this.institutePosting.getCompaniesAutosuggestor(keyword).subscribe(data => this.fillComapaniesItems(data)      
           ,err => err,
         () => console.log('Authentication Complete'));
    }

     private get disabledV():string {
      return this._disabledV;
    }

    private set disabledV(value:string) {
      this._disabledV = value;
      this.disabled = this._disabledV === '1';    
    }

    fillComapaniesItems(data){
    	this.recruitingAutoSuggestorItems  = [];
        for(let i of data){
          
          if(this.addedRecuritingCompanies.indexOf(i['value']) == -1){
        	  this.recruitingAutoSuggestorItems.push({'text':i['label'],'id':i['value']});               
          }
        }
    }

    changeCompanyPosition(currentPosition,newPosition){
      if(currentPosition < newPosition){
        for(let company of this.instituteObj.companies){
          if(company.position > currentPosition && company.position <= newPosition){
            --company.position;
          }
        }
        this.instituteObj.companies[currentPosition-1]['position'] = newPosition;  
      }
      else{
        for(let company of this.instituteObj.companies){
          if(company.position >= newPosition && company.position < currentPosition){
            ++company.position;
          }
        }
        this.instituteObj.companies[currentPosition-1]['position'] = newPosition;
      }

      this.instituteObj.companies.sort((a,b) => {
        if(a['position']<b['position']){return -1}
        if(a['position']>b['position']){return 1}
        return 0;
      });
    }

    modalContent;
    isModalOpened:boolean = false;
    modalEle = '';
    showRCModal(elementId = ''){
      this.isModalOpened = true;
      if(elementId){
        this.modalContent = 'Are you sure you want to remove this company?';
        this.modalEle = elementId;
      }else{
        this.modalContent = 'Are you sure you want to remove all companies?';
      }
    }

    modalResponse(res){
      if(res == 'yes'){
        this.removeAddedCompany();
      }else{
        this.isModalOpened = false;
        this.modalEle = '';
      }
    }

    
    removeAddedCompany(){
      if(this.modalEle){
        for(let i in this.instituteObj.companies){
          if(this.instituteObj.companies[i]['company_id'] == this.modalEle){
            this.instituteObj.removeCompany(i);
            // delete this.instituteObj.companies[i];
            this.addedRecuritingCompanies.splice(this.addedRecuritingCompanies.indexOf(this.modalEle), 1);
          }
        }    
      }else{
        this.instituteObj.companies = [];
        this.addedRecuritingCompanies = [];  
      }
      this.instituteObj.companies = this.instituteObj.companies.filter(function(){return true;});          

      this.isModalOpened = false;
      this.modalEle = '';
    }
}
