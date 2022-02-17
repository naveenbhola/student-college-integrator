import { Injectable } from '@angular/core';
import { Subject }    from 'rxjs/Subject';

@Injectable()
export class SidebarService{
	private updateLinksFor = new Subject();
	public subLinks = [];

	updateLinksObservable$ = this.updateLinksFor.asObservable();

	updateLinks(section){
		this.updateLinksFor.next(section);
	}

	showLinks(subLinks:any){
		this.subLinks = subLinks;
	}
}