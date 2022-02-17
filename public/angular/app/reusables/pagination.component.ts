import { Component,OnInit, ViewChild,Input ,ElementRef,DynamicComponentLoader,Output,EventEmitter} from '@angular/core';

import { SortArrayPipe } from '../pipes/arraypipes.pipe';

import { RangeArrayPipe } from '../pipes/arraypipes.pipe'
@Component({
	selector : "pagination-html",
	templateUrl: '/public/angular/app/reusables/pagination.component.html',
	//template: `<div>{{paginationHTML}}</div>`
	pipes:[RangeArrayPipe,SortArrayPipe]
	
})

export class pagintionHTML {

	@Input() totalPages : number ;
	public totalResults : any ;

	@Input() pageNumber : number; //current page number
	@Input() startPage : number; // starting page number in pagination
	@Input() endPage : number; // end page number in pagination

	@Input() paginationNumbers : number ; //number of pagination numbers to show 

	//this line is used for sending event information to parent component
	@Output() notify: EventEmitter<number> = new EventEmitter<number>();

	paginationRequest(pagination: number)
	{
		if(pagination != this.pageNumber)
		{
			this.notify.emit(pagination);
		}
	}
}