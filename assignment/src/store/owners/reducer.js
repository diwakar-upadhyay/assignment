import Immutable from 'seamless-immutable';
import * as actionTypes from './actionTypes';

const initialState = Immutable({
  byId: {},
  params: {}
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
        byId: action.payload.byId.result.data || {},
        message:0
      });
    case actionTypes.CREATE_SUCCESS:
    if(action.payload.error_code==0){
      var message='';
      return state.merge({message:1});
    }else{
      return state.merge({
        'message': 0,
      });

    }
    case actionTypes.UPDATE_SUCCESS:

     if(action.payload.error_code==0){
       var message='';
       return state.merge({message:1});
     }else{
       return state.merge({
         'message': 0,
       });

     }
    case actionTypes.DELETE_SUCCESS:
       //return state.set('byId', action.payload);
    default:
      return state;
  }
};
