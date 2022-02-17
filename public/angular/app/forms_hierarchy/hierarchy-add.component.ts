import { Component, OnInit } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup,FormBuilder, Validators }    from '@angular/forms';
import { HierarchyService }       from '../services/hierarchy.service';
import { ListingBaseClass, ListingBaseService } from '../services/listingbase.service';
import { Router } from '@angular/router';
import { SortArrayPipe } from '../pipes/arraypipes.pipe';


@Component({
	selector: 'hierarchyAdd',
	templateUrl: '/public/angular/app/forms_hierarchy/hierarchy-add.component.html',
	providers: [HierarchyService],
	directives: [REACTIVE_FORM_DIRECTIVES],
	pipes: [SortArrayPipe],
	styles: [`
        .ng-invalid {
            border: 1px solid #a94442;
        }
        .ng-pristine{
        	border: 1px solid #ccc;
        }
    `]
})

export class HierarchyAddComponent extends ListingBaseClass implements OnInit {
	hierarchyForm: any;
	errorMessage: string;
	submitPending = false;
    submitted = false;

	constructor(public fb: FormBuilder, private hierarchyService: HierarchyService, private router: Router, public listingBaseService: ListingBaseService) { super(listingBaseService);}

	ngOnInit() {
		this.getStreamList();
		this.getSubstreamList();
		this.getSpecializationList();
		
		this.hierarchyForm = this.fb.group({
			stream: ["", Validators.required],
			substream: ["0"],
			specialization: ["0"],
			scope: ["national", Validators.required],
			courseType: ["academic", Validators.required]
		});
	}
	
	backToHome(){
		this.router.navigate(['/cmsPosting/hierarchyPosting/viewList']);	
	}

	submitHierarchy() {		

		if (this.hierarchyForm.dirty) {
			if (this.hierarchyForm.valid){
				this.submitPending = true;
				this.hierarchyService.addHierarchy(this.hierarchyForm.value)
					.subscribe(
						data => { alert(data['message']);if(data.status == 'success'){this.submitPending = false;this.backToHome();}else{this.submitPending = false;} },
						error => { alert('Internal Error'); this.backToHome(); }
					);
			}
		}
		else{
			this.backToHome();
		}
	}
}