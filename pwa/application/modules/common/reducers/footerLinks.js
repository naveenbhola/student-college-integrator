const initailState = {
};
export function footerData(state = initailState, action)
{
	switch(action.type)
	{
		case 'footerLinks' :
			return Object.assign({},{},action.data);
		default:
			/*if(typeof action.type == 'string' && action.type != '')
			{
				let key = action.type.split("_");
				if(key[0]== 'except' && key[1] != 'footerlinks')
					return {};
			}*/
			return state;
	}
}
