import { Component } from '@angular/core';
import {InstitutePostingListComponent} from '../../InstitutePosting/Components/institute-posting-list.component';


@Component({
	'selector':'university-list',
	'template': '<institutePostingList [postingListingType]="postingType"></institutePostingList>',
	directives:[InstitutePostingListComponent]

})

export class UniversityPostingListComponent 
{
	postingType : string = 'University';
}