import { combineEpics } from 'redux-observable';
import { values } from 'lodash';

import * as bookingsEpics from './bookings/epics';
import * as ownersEpics from './owners/epics';

export default combineEpics(
  ...values(bookingsEpics),
  ...values(ownersEpics)
);
