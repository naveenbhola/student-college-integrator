
const initailState = {
};
export function instituteDetailData(state = initailState, action)
{
	switch(action.type)
	{
		case 'instituteData' :
			return Object.assign({},state,action.data);
		default:
			if(typeof action.type == 'string' && action.type != '')
			{
				let key = action.type.split("_");
				if(key[0]== 'except' && key[1] != 'institutepagedata')
					return {};
			}
			return state;
	}
}

export function instituteDataForPreFilledReducer(state = {},action)
{
	switch(action.type)
	{
		case 'catpageInstitute':
			return Object.assign({},{},action.data);
		case 'catpageInstituteEmpty':
			return {};
		default:
			return state;
	}
}