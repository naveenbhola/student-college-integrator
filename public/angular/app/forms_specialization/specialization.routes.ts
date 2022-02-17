import {RouterConfig} from '@angular/router';

import { SpecializationListComponent } from './specialization-list.component';
import { SpecializationAddComponent } from './specialization-add.component';
import { SpecializationHomeComponent } from './specialization-home.component';
import { ListingSpecialUsersGuard } from '../Listing/CoursePosting/services/coursePostingGuards';
import { UserGuard } from '../Common/services/userGuard';

export const SPECIALIZATIONROUTES: RouterConfig = [
	{
		path: 'cmsPosting/specializationPosting',
		resolve: {userData:UserGuard},
		component: SpecializationHomeComponent,
		children: [
			{ path: 'viewList', component: SpecializationListComponent, },
			{ path: 'create', component: SpecializationAddComponent,resolve:{ListingSpecialUsersGuard} },
			{ path: 'create/:id', component: SpecializationAddComponent,resolve:{ListingSpecialUsersGuard} },
			{ path: '', component: SpecializationListComponent, },
		]
	},
];