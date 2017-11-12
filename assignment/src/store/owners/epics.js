import { keyBy } from 'lodash';
import axios from 'axios';
import querystring from 'querystring';
import { Observable } from 'rxjs/Observable';
import { push } from 'react-router-redux';

import * as actionTypes from './actionTypes';
import * as ownersActions from './actionCreators';

export function fetchOwner(action$) {
  return action$.ofType(actionTypes.FETCH_ONE)
    .map(action => action.payload)
    .switchMap(id => {
      return Observable.fromPromise(
        axios.get(`http://local.gorb.in/assignment-api/getownerbyid/${id}`)
      ).map(
            res => ownersActions.fetchOwnerSuccess(res.data.result.data)

        );
    });
}

export function fetchOwners(action$) {

  return action$.ofType(actionTypes.FETCH_COLLECTION)
    .map(action => action.payload)
    .switchMap(params => {
      return Observable.fromPromise(
        axios.get(`http://local.gorb.in/assignment-api/getAllPropertyOwner?${querystring.stringify(params)}`)
      ).map(res => ownersActions.fetchOwnersSuccess(res.data, params));
    });
}

export function updateOwner(action$) {
  var config = {
    headers: {'Content-Type': 'application/x-www-form-urlencoded','accept': 'application/json'}
  };
  return action$.ofType(actionTypes.UPDATE)
    .map(action => action.payload)
    .switchMap(owner => {
      return Observable.merge(
        Observable.fromPromise(
          axios.post(`http://local.gorb.in/assignment-api/updateowner`,owner,config)
        ).map(res => ownersActions.updateOwnerSuccess(res.data)),
         //Observable.of(push('/owners'))
      );
    });
}

export function createOwner(action$) {
  var config = {
    headers: {'Content-Type': 'application/x-www-form-urlencoded'}
  };
      //http://localhost:8081/posts
  return action$.ofType(actionTypes.CREATE)
    .map(action => action.payload)
    .switchMap(owner => {
      return Observable.merge(
        Observable.fromPromise(
          axios.post(`http://local.gorb.in/assignment-api/ownerupdate`,owner,config)
        ).map(res => ownersActions.createOwnerSuccess(res.data)),
        //Observable.of(push('/owners'))
      );
    });
}

export function deleteOwner(action$) {
  return action$.ofType(actionTypes.DELETE)
    .map(action => action.payload)
    .switchMap(owner => {
      return Observable.merge(
        Observable.fromPromise(
        axios.get(`http://local.gorb.in/assignment-api/deleteowner/${owner.po_id}`)
      ).map(res => ownersActions.deleteOwnerSuccess(owner)),
       Observable.of(push('/owners'))
    );
    });
}
