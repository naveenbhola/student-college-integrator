import {RouterConfig} from '@angular/router';

import { InstitutePostingListComponent } from '../Components/institute-posting-list.component';
import { InstitutePostingCreateComponent } from '../Components/institutePostingCreate.component';

export const INSTITUTEPOSTINGROUTES: RouterConfig = [
	{
		path: 'nationalInstitute/InstitutePosting',
		children: [
			{ path: 'viewList', component: InstitutePostingListComponent, },
			{ path: 'create', component: InstitutePostingCreateComponent, },
			{ path: 'create/:id', component: InstitutePostingCreateComponent, },
			{ path: '', pathMatch: 'full', redirectTo: 'viewList' }
		]
	},
];
