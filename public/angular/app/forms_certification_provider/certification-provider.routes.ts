import {RouterConfig} from '@angular/router';

import { CertificationProviderListComponent } from './certification-provider-list.component';
import { CertificationProviderAddComponent } from './certification-provider-add.component';
import { CertificationProviderHomeComponent } from './certification-provider-home.component';
import { UserGuard } from '../Common/services/userGuard';
import { ListingSpecialUsersGuard } from '../Listing/CoursePosting/services/coursePostingGuards';

export const CERTIFICATIONPROVIDERROUTES: RouterConfig = [
	{
		path: 'cmsPosting/certificationProviderPosting',
		resolve: {userData:UserGuard},
		component: CertificationProviderHomeComponent,
		children:[
			{ path: 'viewList', component: CertificationProviderListComponent, },
			{ path: 'create', component: CertificationProviderAddComponent,resolve:{ListingSpecialUsersGuard} },
			{ path: 'create/:id', component: CertificationProviderAddComponent,resolve:{ListingSpecialUsersGuard} },
			{ path: '', component: CertificationProviderListComponent,}
		]
	},
];