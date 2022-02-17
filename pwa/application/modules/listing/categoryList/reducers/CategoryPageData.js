const initailState = {
};
export function categoryPageData(state = initailState, action)
{
	switch(action.type)
	{
		case 'categoryData' :
			return Object.assign({},state,action.data);
		default:
			if(typeof action.type == 'string' && action.type != '')
			{
				let key = action.type.split("_");
				if(key[0]== 'except' && key[1] != 'categorypagedata')
					return {};
			}
			return state;
	}
}