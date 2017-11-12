import Immutable from 'seamless-immutable';
import * as actionTypes from './actionTypes';


const initialState = Immutable({
  byId: {},
  params: {},
  customer:{}

});

export default (state = initialState, action) => {
  switch (action.type) {
    case actionTypes.FETCH_ONE_SUCCESS:
    return state.merge({
      byId: action.payload || {}
    });
    case actionTypes.FETCH_COLLECTION_SUCCESS:
      return state.merge({
        params: action.payload.params || {},
        byId: action.payload.byId.result.data || {}
      });
    case actionTypes.CREATE_SUCCESS:
    case actionTypes.UPDATE_SUCCESS:
            if(action.payload.error_code==0){
              var message='';
              return state.merge({message:1});
            }else{
              var message='';
              return state.merge({
                message : 0,
              });

            }
      //return state.setIn(['byId', action.payload.id], action.payload);
    case actionTypes.DELETE_SUCCESS:
      return state.set('byId', action.payload.bo_id);
    case actionTypes.SELECT_CUSTOMERS:
    case actionTypes.SELECT_CUSTOMERS_SUCCESS:
    return state.merge({'customer': action.payload || {}});
    default:
      return state;
  }
};
