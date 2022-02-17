const initailState = {};
export function campusRepData(state = initailState, action)
{  
	switch(action.type)
	{
		case 'campusRepData':
			return Object.assign({}, initailState, action.data);
		default:
			return state;
	}
}