import { RouterConfig } from '@angular/router';

import { CategorySeoListComponent } from './category-seo-list.component';
import { CategorySeoAddComponent } from './category-seo-add.component';
import { CategorySeoHomeComponent } from './category-seo-home.component';
import { StreamsGuard, BaseAttributesGuard, PopularCoursesGuard } from '../services/categorySeoGuards';
import { CourseHierarchyTreeGuard } from '../../CoursePosting/services/coursePostingGuards';
import { ListingBaseService } from '../../../services/listingbase.service';

export const CATEGORYSEOROUTES: RouterConfig = [
	{
		path: 'nationalCategoryList/CategoryPageSeoEnterprise',
		component: CategorySeoHomeComponent,
		children: [
			{ path: 'viewList', component: CategorySeoListComponent, },
			{ path: 'create', component: CategorySeoAddComponent, resolve: { hierarchyTree: CourseHierarchyTreeGuard, streamList: StreamsGuard, attributeInfo: BaseAttributesGuard, popularCoursesList: PopularCoursesGuard } },
			// { path: 'create/:id', component: CategorySeoAddComponent, },
		]
	},
];

export const CATEGORYSEOPROVIDERS = [ListingBaseService, StreamsGuard, BaseAttributesGuard, PopularCoursesGuard];
