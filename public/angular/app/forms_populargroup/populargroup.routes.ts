import {RouterConfig} from '@angular/router';

import { PopulargroupListComponent } from './populargroup-list.component';
import { PopulargroupAddComponent } from './populargroup-add.component';
import { PopulargroupHomeComponent } from './populargroup-home.component';
import { UserGuard } from '../Common/services/userGuard';
import { ListingSpecialUsersGuard } from '../Listing/CoursePosting/services/coursePostingGuards';

export const POPULARGROUPROUTES: RouterConfig = [
	{
		path: 'cmsPosting/populargroupPosting',
		resolve: {userData:UserGuard},
		component: PopulargroupHomeComponent,
		children:[
			{ path: 'viewList', component: PopulargroupListComponent, },
			{ path: 'create', component: PopulargroupAddComponent,resolve:{ListingSpecialUsersGuard} },
			{ path: 'create/:id', component: PopulargroupAddComponent,resolve:{ListingSpecialUsersGuard} },
			{ path: '', component: PopulargroupListComponent,}
		]
	},
];