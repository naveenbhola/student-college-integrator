const initailState = {};
export function importantDatesAmp(state = initailState, action)
{  
	switch(action.type)
	{
		case 'importantDatesAmp':
			return Object.assign({}, initailState, action.data);
		default:
			return state;
	}
}