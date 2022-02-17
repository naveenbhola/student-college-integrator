const initailState = {};
export function alumniData(state = initailState, action)
{  
	switch(action.type)
	{
		case 'alumniData':
			return Object.assign({}, initailState, action.data);
		default:
			return state;
	}
}