import { keyBy } from 'lodash';
import * as actionTypes from './actionTypes';

export function fetchOwner(payload) {
  return {type: actionTypes.FETCH_ONE, payload};
}

export function fetchOwnerSuccess(payload) {

  const byId = payload
  return {type: actionTypes.FETCH_ONE_SUCCESS, payload: {byId}};
}

export function fetchOwners(payload) {
  return {type: actionTypes.FETCH_COLLECTION, payload};
}

export function fetchOwnersSuccess(owners, params) {
  const byId = owners
  return {type: actionTypes.FETCH_COLLECTION_SUCCESS, payload: {byId, params}};
}

export function createOwner(payload) {
  return {type: actionTypes.CREATE, payload};
}

export function createOwnerSuccess(payload) {
  return {type: actionTypes.CREATE_SUCCESS, payload};
}

export function updateOwner(payload) {
  return {type: actionTypes.UPDATE, payload};
}

export function updateOwnerSuccess(payload) {
  return {type: actionTypes.UPDATE_SUCCESS, payload};
}

export function deleteOwner(payload) {
  return {type: actionTypes.DELETE, payload};
}

export function deleteOwnerSuccess(payload) {
  return {type: actionTypes.DELETE_SUCCESS, payload};
}
