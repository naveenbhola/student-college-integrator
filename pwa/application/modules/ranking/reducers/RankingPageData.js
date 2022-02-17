const initailState = {};
export function rankingPageData(state = initailState, action)
{
	switch(action.type)
	{
		case 'rankingPageData' :
			return Object.assign({},state,action.data);
		default:
			if(typeof action.type == 'string' && action.type != '')
			{
				let key = action.type.split("_");
				if(key[0]== 'except' && key[1] != 'rankingPageData')
					return {};
			}
			return state;
	}
}
