import { combineReducers } from 'redux';
import { routerReducer } from 'react-router-redux';

import auth from './auth/reducer';
import categories from './categories/reducer';
import bookings from './bookings/reducer';
import owners from './owners/reducer';

export default combineReducers({
  auth,
  categories,
  bookings,
  owners,
  routing: routerReducer,
});
