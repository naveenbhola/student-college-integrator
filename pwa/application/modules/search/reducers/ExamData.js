const initailState = {
};
export function examData(state = initailState, action)
{
    switch(action.type)
    {
        case 'examData' :
            return Object.assign({},{}, action.data);
        default:
            if(typeof action.type == 'string' && action.type != '')
            {
                let key = action.type.split("_");
                if(key[0] === 'except' && key[1] !== 'examSRPData')
                    return {};
            }
            return state;
    }
}