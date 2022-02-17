import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ArraySearchPipe, LimitArrayPipe, SortArrayPipe } from '../pipes/arraypipes.pipe';
import { ListingBaseClass, ListingBaseService } from '../services/listingbase.service';
import { Observable }     from 'rxjs/Rx';

@Component({
	selector: 'specialization',
	templateUrl: '/public/angular/app/forms_specialization/specialization-list.component.html',
	pipes: [ArraySearchPipe, LimitArrayPipe, SortArrayPipe]
})

export class SpecializationListComponent extends ListingBaseClass implements OnInit {
	errorMessage: string;
	constructor(private router: Router, public listingBaseService: ListingBaseService) { super(listingBaseService); }
	ngOnInit() {
		let o1 = this.listingBaseService.getSubstreams();
		let o2 = this.listingBaseService.getStreams();
		let o3 = this.listingBaseService.getSpecializations();
		Observable.forkJoin([o1,o2,o3]).subscribe(
			data => {
				this.streamList = Object.values(data[1]);
				this.substreamList = Object.values(data[0]);
				let specializations = data[2];
				for(let i in specializations){
					if(typeof data[1][specializations[i]['primary_stream_id']] != 'undefined'){
						specializations[i]['primary_stream_name'] = data[1][specializations[i]['primary_stream_id']]['name'];
					}else{
						specializations[i]['primary_stream_name'] = '';
					}
					if(specializations[i]['primary_substream_id'] && typeof data[0][specializations[i]['primary_substream_id']] != 'undefined'){
						specializations[i]['primary_substream_name'] = data[0][specializations[i]['primary_substream_id']]['name'];
					}
					else{
						specializations[i]['primary_substream_name'] = '';
					}
				}
				this.specializationList = Object.values(specializations);
			},
			error => {alert('Failed to get data');}
		);
	}

	newSpecialization() {
		this.router.navigate(['/cmsPosting/specializationPosting/create']);
	}

	navigateToEdit(id){
		this.router.navigate(['/cmsPosting/specializationPosting/create/' + id]);
	}
}

