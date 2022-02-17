import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ArraySearchPipe, LimitArrayPipe, SortArrayPipe } from '../pipes/arraypipes.pipe';
import { ListingBaseClass, ListingBaseService } from '../services/listingbase.service';
import { Observable }     from 'rxjs/Rx';

@Component({
  selector: 'substream',
  templateUrl: '/public/angular/app/forms_substream/substream-list.component.html',
  pipes: [ArraySearchPipe, LimitArrayPipe, SortArrayPipe]
})

export class SubstreamListComponent extends ListingBaseClass implements OnInit {
	errorMessage: string;
	constructor(private router: Router, public listingBaseService: ListingBaseService) { super(listingBaseService); }
	ngOnInit() {
		let o1 = this.listingBaseService.getSubstreams();
		let o2 = this.listingBaseService.getStreams();
		Observable.forkJoin([o1,o2]).subscribe(
			data => {
				this.streamList = Object.values(data[1]);
				let substreams = Object.values(data[0]);
				for(let i in substreams){
					if(typeof data[1][substreams[i]['primary_stream_id']] != 'undefined'){
						substreams[i]['primary_stream_name'] = data[1][substreams[i]['primary_stream_id']]['name'];	
					}else{
						substreams[i]['primary_stream_name'] = 'Empty';	
					}					
				}
				this.substreamList = substreams;
			},
			error => {alert('Failed to get data');}
		);
	}

	newSubstream() {
		this.router.navigate(['/cmsPosting/substreamPosting/create']);		
	}
	
	navigateToEdit(id){
		this.router.navigate(['/cmsPosting/substreamPosting/create/' + id]);
	}
}

