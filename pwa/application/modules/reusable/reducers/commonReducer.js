export function dfpParams(state = {},action)
{
	switch(action.type)
	{
		case 'dfpParams':
			return Object.assign({},{},action.data);
		case 'emptydfpParams':
			return {};
		default:
			return state;
	}
}