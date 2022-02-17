import { Injectable } from '@angular/core';
import { Http, Response } from '@angular/http';
import { Headers, RequestOptions } from '@angular/http';
import { Observable }     from 'rxjs/Observable';
import { NgForm}    from '@angular/forms';


@Injectable()
export class paginationService {

	public paginationHTML : any ;
    public totalPages : number;  
    public startPage : number;
    public endPage : number;
    public paginationArray : Array<Object> = [];

    /**
     @param totalResultCount -> Cumulative Number Of total result
     @param paginationNum -> paginationNumber
     @param paginationResultsShow-> Number of results displayed on Page(i.e. limit)
     @param paginationLimit -> Number of Pagination numbers to be shown
     */
    paginationLogic(totalResultCount,paginationResultsShow,paginationNum,paginationLimit)
    {
        this.initilaizeToZeroValues();
        this.totalPages = totalResultCount/paginationResultsShow;
        this.totalPages = Math.ceil(this.totalPages);
        if(this.totalPages > 0)
        {
          this.endPage= this.totalPages < paginationLimit ? this.totalPages : paginationLimit;

          if(this.totalPages > paginationLimit){
                this.startPage = paginationNum - Math.floor((paginationLimit/2));
                this.startPage = this.startPage < 1 ? 1 : this.startPage;

                this.endPage = paginationNum + Math.ceil((paginationLimit/2));
                this.endPage = this.endPage > this.totalPages ? this.totalPages : this.endPage;
          }
            
         }
         this.paginationArray['totalPages'] = this.totalPages;
         this.paginationArray['startPage'] = this.startPage;
         this.paginationArray['endPage'] = this.endPage;
         return this.paginationArray;
    }
    initilaizeToZeroValues()
    {
        this.totalPages = 0;
        this.startPage = 1;
        this.endPage = 0;
    }
} 