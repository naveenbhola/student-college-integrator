const initialState = {};

export function examPageData(state = initialState, action)
{
	switch(action.type)
	{
		case 'FETCH_EXAM_DATA' :
			return Object.assign({},state,action.data);
		default:
			if(typeof action.type == 'string' && action.type != '')
			{
				let key = action.type.split("_");
				if(key[0]== 'except' && key[1] != 'examPageData')
					return {};
			}
			return state;
	}
}

export function contentLoaderData(state = initialState, action)
{
	switch(action.type)
	{
		case 'examPagePrefilled' :
			return Object.assign({},state,action.data);
		default:
			if(typeof action.type == 'string' && action.type != '')
			{
				let key = action.type.split("_");
				if(key[0]== 'except' && key[1] != 'examPagePrefilled')
					return {};
			}
			return state;
	}
}
