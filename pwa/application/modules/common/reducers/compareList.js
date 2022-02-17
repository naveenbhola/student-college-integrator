const initailState = {};
export function compareList(state = initailState, action)
{  
	switch(action.type)
	{
		case 'compareList' :
			return Object.assign({}, initailState, action.data);
		default:
			return state;
	}
}