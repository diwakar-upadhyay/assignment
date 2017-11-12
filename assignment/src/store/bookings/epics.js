import { keyBy } from 'lodash';
import axios from 'axios';
import querystring from 'querystring';
import { Observable } from 'rxjs/Observable';
import { push } from 'react-router-redux';

import * as actionTypes from './actionTypes';
import * as bookingsActions from './actionCreators';

export function fetchBooking(action$) {
  return action$.ofType(actionTypes.FETCH_ONE)
    .map(action => action.payload)
    .switchMap(id => {
      return Observable.fromPromise(
        axios.get(`http://local.gorb.in/assignment-api/getbookingbyid/${id}`)
      ).map(res => bookingsActions.fetchBookingSuccess(res.data.result.data));
    });
}


export function fetchBookings(action$) {
  return action$.ofType(actionTypes.FETCH_COLLECTION)
    .map(action => action.payload)
    .switchMap(params => {
      return Observable.fromPromise(
        axios.get(`http://local.gorb.in/assignment-api/getAllBookings?${querystring.stringify(params)}`)
      ).map(res => bookingsActions.fetchBookingsSuccess(res.data, params));
    });
}

export function updateBooking(action$) {
  var config = {
    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
  };
  return action$.ofType(actionTypes.UPDATE)
    .map(action => action.payload)
    .switchMap(booking => {
      return Observable.merge(
        Observable.fromPromise(
          axios.post(`http://local.gorb.in/assignment-api/updatebooking`, booking,config)
        ).map(res => bookingsActions.updateBookingSuccess(res.data)),
        //Observable.of(push('/bookings'))
      );
    });
}

export function createBooking(action$) {
  var config = {
    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
  };
  return action$.ofType(actionTypes.CREATE)
    .map(action => action.payload)
    .switchMap(booking => {
      return Observable.merge(
        Observable.fromPromise(
          axios.post(`http://local.gorb.in/assignment-api/addUBooking`,booking,config)
        ).map(res => bookingsActions.createBookingSuccess(res.data)),
        Observable.of(push('/bookings'))
      );
    });
}

export function deleteBooking(action$) {
  var config = {
    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
  };
  return action$.ofType(actionTypes.DELETE)
    .map(action => action.payload)
    .switchMap(booking => {
      return Observable.merge(
        Observable.fromPromise(
        axios.get(`http://local.gorb.in/assignment-api/deletebooking/${booking.bo_id}`)
      ).map(res => bookingsActions.deleteBookingSuccess(booking)),
        Observable.of(push('/bookings'))
      );
    });
}

export function fetchCustomers(action$) {
  return action$.ofType(actionTypes.SELECT_CUSTOMERS)
    .map(action => action.payload)
    .switchMap(id => {
      return Observable.fromPromise(
        axios.get(`http://local.gorb.in/assignment-api/getAllOwners`)
      ).map(res => bookingsActions.selectCustomersSuccess(res.data));
    });
}
