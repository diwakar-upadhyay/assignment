import React from 'react';
import { Navbar, Nav, NavItem } from 'react-bootstrap';
import { IndexLinkContainer, LinkContainer } from 'react-router-bootstrap';
import { Route, IndexRoute, Router, hashHistory } from 'react-router';
import { syncHistoryWithStore } from 'react-router-redux';
import { Provider } from 'react-redux';
import store from './store';
import {
  Dashboard,
  BookingsIndex,
  BookingsEdit,
  OwnersIndex,
  OwnersEdit,

} from './containers/index';

require('./app.scss');

const history = syncHistoryWithStore(hashHistory, store);

let App = ({children}) => {
  return (
    <div>
      <Navbar>
        <Nav>
          <IndexLinkContainer to="/">
            <NavItem>Dashboard</NavItem>
          </IndexLinkContainer>
          <LinkContainer to="/bookings">
            <NavItem>Booking</NavItem>
          </LinkContainer>
          <LinkContainer to="/owners">
              <NavItem>Property Owner</NavItem>
            </LinkContainer>
        </Nav>
      </Navbar>
      <div className="container">
        {children}
      </div>
    </div>
  );
}



export default () => {
  return (
    <Provider store={store}>
      <Router history={history}>

        <Route path="/" component={App}>
          <IndexRoute component={Dashboard} />
          <Route path="/bookings" component={BookingsIndex} />
          <Route path="/bookings/new" component={BookingsEdit} />
          <Route path="/bookings/:bookingId" component={BookingsEdit} />
          <Route path="/owners" component={OwnersIndex} />
          <Route path="/owners/new" component={OwnersEdit} />
          <Route path="/owners/:ownerId" component={OwnersEdit} />
        </Route>
      </Router>
    </Provider>
  )
}
