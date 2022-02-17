const initailState = {
};
export function gnbData(state = initailState, action)
{
	switch(action.type)
	{
		case 'gnbLinks' :
			return Object.assign({},{},action.data);
		default:
			/*if(typeof action.type == 'string' && action.type != '')
			{
				let key = action.type.split("_");
				if(key[0]== 'except' && key[1] != 'gnb')
					return {};
			}*/
			return state;
	}
}
