
const initailState = {};
export function allChildPageData(state = initailState, action)
{

	switch(action.type)
	{
		case 'allChildPageData':
			return Object.assign({},{},action.data);
		default:
			if(typeof action.type == 'string' && action.type != '')
			{
				let key = action.type.split("_");
				if(key[0]== 'except' && key[1] != 'allChildPageData')
					return {};
			}
			return state;
	}
}

export function childPageDataForPreFilledReducer(state = {},action)
{
	switch(action.type)
	{
		case 'loaderData':
			return Object.assign({},{},action.data);
		case 'loaderDataEmpty':
			return {};
		default:
			return state;
	}
}