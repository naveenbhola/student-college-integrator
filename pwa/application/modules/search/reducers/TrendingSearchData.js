const initailState = {
};
export function TrendingSearchData(state = initailState, action)
{
    switch(action.type)
    {
        case 'trendingData' :
            return Object.assign({},state,action.data);
        default:
            if(typeof action.type == 'string' && action.type != '')
            {
                let key = action.type.split("_");
                if(key[0] == 'except' && key[1] != 'trendingData')
                    return state;
            }
            return state;
    }
}