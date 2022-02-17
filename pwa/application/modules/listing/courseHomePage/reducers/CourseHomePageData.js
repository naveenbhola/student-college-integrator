const initailState = {};
export function courseHomePageData(state = initailState, action)
{
	switch(action.type)
	{
		case 'courseHomePageData' :
			return Object.assign({},state,action.data);
		default:
			if(typeof action.type == 'string' && action.type != '')
			{
				let key = action.type.split("_");
				if(key[0]== 'except' && key[1] != 'courseHomePageData')
					return {};
			}
			return state;
	}
}

export function contentLoaderData(state = initailState, action)
{
	switch(action.type)
	{
		case 'courseHomePagePrefilled' :
			return Object.assign({},state,action.data);
		default:
			if(typeof action.type == 'string' && action.type != '')
			{
				let key = action.type.split("_");
				if(key[0]== 'except' && key[1] != 'courseHomePagePrefilled')
					return {};
			}
			return state;
	}
}