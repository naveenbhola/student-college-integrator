const initailState = {};
export function userDetails(state = initailState, action)
{  
	switch(action.type)
	{
		case 'shikshaUser':
			return Object.assign({}, initailState, action.data);
		default:
			return state;
	}
}