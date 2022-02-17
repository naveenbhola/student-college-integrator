const initailState = {};
export function ampHamburger(state = initailState, action)
{  
	switch(action.type)
	{
		case 'ampHamburger':
			return Object.assign({}, initailState, action.data);
		default:
			return state;
	}
}