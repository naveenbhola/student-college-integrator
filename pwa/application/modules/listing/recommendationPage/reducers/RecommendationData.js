const initailState = {
};
export function recommendationData(state = initailState, action)
{
    switch(action.type)
    {
        case 'recommendationData' :
            return Object.assign({},{}, action.data);
        default:
            if(typeof action.type == 'string' && action.type != '')
            {
                let key = action.type.split("_");
                if(key[0] === 'except' && key[1] !== 'recommendationPageData')
                    return {};
            }
            return state;
    }
}