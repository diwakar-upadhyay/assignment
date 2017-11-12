export function getParams(state) {
  return state.bookings.params;
}

export function getBooking(state, id) {
  return state.bookings.byId.byId;
}

export function getCustomer(state) {
  return state.bookings.customer;
}

export function getBookings(state) {
  return Object.values(state.bookings.byId);
}

export function getredirect(state) {
  return state.owners.message;
}
