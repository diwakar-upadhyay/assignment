import { keyBy } from 'lodash';
import * as actionTypes from './actionTypes';

export function fetchBooking(payload) {

  return {type: actionTypes.FETCH_ONE, payload};
}

export function fetchBookingSuccess(payload) {
  const byId = payload
  return {type: actionTypes.FETCH_ONE_SUCCESS, payload: {byId}};
}

export function fetchBookings(payload) {
  return {type: actionTypes.FETCH_COLLECTION, payload};
}

export function fetchBookingsSuccess(bookings, params) {
  const byId=bookings;
  return {type: actionTypes.FETCH_COLLECTION_SUCCESS, payload: {byId, params}};
}

export function createBooking(payload) {
  return {type: actionTypes.CREATE, payload};
}

export function createBookingSuccess(payload) {

  return {type: actionTypes.CREATE_SUCCESS, payload};
}

export function updateBooking(payload) {
  return {type: actionTypes.UPDATE, payload};
}

export function updateBookingSuccess(payload) {
  return {type: actionTypes.UPDATE_SUCCESS, payload};
}

export function deleteBooking(payload) {
  return {type: actionTypes.DELETE, payload};
}

export function deleteBookingSuccess(payload) {
  return {type: actionTypes.DELETE_SUCCESS, payload};
}

export function selectCustomers() {
  return {type: actionTypes.SELECT_CUSTOMERS};
}

export function selectCustomersSuccess(payload) {
  // var obj={};
  // obj.cust=payload;
  //const byId = {[payload.id]: payload};
  return {type: actionTypes.SELECT_CUSTOMERS_SUCCESS, payload};
}
