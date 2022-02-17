import { Injectable } from '@angular/core';
import { Subject }    from 'rxjs/Subject';

@Injectable()
export class InstituteLocationService{
	private mainLocationSource = new Subject();
	private locationSource = new Subject();
	private hierarchySource = new Subject();
	private eventSource = new Subject();
	private validateLocation = new Subject();

	handleMainLocationChanges$ = this.mainLocationSource.asObservable();
	handleLocationChanges$ = this.locationSource.asObservable();
	handleHierarchyChange$ = this.hierarchySource.asObservable();
	handleEventChange$ = this.eventSource.asObservable();
	handleMediaLocationSelection$ = this.validateLocation.asObservable();

	pushMainLocationChange(obj:any={}){
		this.mainLocationSource.next(obj);
	}

	pushLocationChange(obj:any={}){
		this.locationSource.next(obj);
	}

	pushHierarchyChange(obj:any={}){
		this.hierarchySource.next(obj);	
	}

	pushEventChange(obj:any={}){
		this.eventSource.next(obj);	
	}
	pushValidateLocationMedia(obj:any={})
	{
		this.validateLocation.next(obj);
	}
}