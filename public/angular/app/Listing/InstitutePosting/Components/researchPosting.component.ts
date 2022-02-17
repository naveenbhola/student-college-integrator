import { Component,OnInit,Input } from '@angular/core';
import {Posting} from '../../../Common/Classes/Posting';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
	selector : "research-posting",
	templateUrl: '/public/angular/app/Listing/InstitutePosting/Views/'+getHtmlVersion('researchposting.component.html')
})

export class ResearchPostingComponent extends Posting implements OnInit{

	@Input() instituteObj;
	@Input() mode;
	activateResearch = true;

	ngOnInit(){
        if(this.mode == 'add'){
        	this.instituteObj.addResearchProjects();
        }
	}
}
