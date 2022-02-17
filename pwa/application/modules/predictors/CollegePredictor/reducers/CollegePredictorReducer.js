const initialState = {};

export function collegePredictorData(state = initialState, action)
{
	switch(action.type)
	{
		case 'FETCH_COLLEGE_PREDICTOR_DATA' :
			return Object.assign({},state,action.data);
		default:
			if(typeof action.type == 'string' && action.type != '')
			{
				let key = action.type.split("_");
				if(key[0]== 'except' && key[1] != 'collegePredictorData')
					return {};
			}
			return state;
	}
}

export function collegePredictorResults(state = initialState, action)
{
	switch(action.type)
	{
		case 'FETCH_COLLEGE_PREDICTOR_RESULTS_DATA' :
			return Object.assign({},state,action.data);
		default:
			if(typeof action.type == 'string' && action.type != '')
			{
				let key = action.type.split("_");
				if(key[0]== 'except' && key[1] != 'collegePredictorResults')
					return {};
			}
			return state;
	}
}

export function collegePredictorSaveList(state = initialState, action)
{
	switch(action.type)
	{
		case 'CPSaveList' :
			return Object.assign({},state,action.data);
		default:
			if(typeof action.type == 'string' && action.type != '')
			{
				let key = action.type.split("_");
				if(key[0]== 'except' && key[1] != 'collegePredictorSaveList')
					return {};
			}
			return state;
	}
}

export function collegePredictorFilterData(state = initialState, action)
{
	switch(action.type)
	{
		case 'CP_FILTER_DATA' :
			return Object.assign({},state,action.data);
		default:
			if(typeof action.type == 'string' && action.type != '')
			{
				let key = action.type.split("_");
				if(key[0]== 'except' && key[1] != 'collegePredictorFilterData')
					return {};
			}
			return state;
	}
}
