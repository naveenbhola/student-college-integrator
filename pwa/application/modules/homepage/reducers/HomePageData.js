
const initailState = {
};
export function homepageData(state = initailState, action)
{
	switch(action.type)
	{
		case 'homepageData' :
			return Object.assign({},state,action.data);
		default:
			if(typeof action.type == 'string' && action.type != '')
			{
				let key = action.type.split("_");
				if(key[0]== 'except' && key[1] != 'homepagedata')
					return {};
			}
			return state;
	}
}