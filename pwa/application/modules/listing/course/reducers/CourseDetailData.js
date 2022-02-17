
const initailState = {
};
export function courseDetailData(state = initailState, action)
{
	switch(action.type)
	{
		case 'courseData' :
			return Object.assign({},state,action.data);
		default:
			if(typeof action.type == 'string' && action.type != '')
			{
				let key = action.type.split("_");
				if(key[0]== 'except' && key[1] != 'coursepagedata')
					return {};
			}
			return state;
	}
}

export function courseDataForPreFilledReducer(state = {},action)
{
	switch(action.type)
	{
		case 'catpageCourse':
			return Object.assign({},{},action.data);
		case 'catpageCourseEmpty':
			return {};
		default:
			return state;
	}
}

export function storeAggregateReviewReducer(state = {},action){
	switch(action.type)
	{
		case 'reviewData':
			return Object.assign({},{},action.data);
		default:
			return state;
	}	
}