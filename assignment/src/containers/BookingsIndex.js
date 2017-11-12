import React from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux'
import { Link } from 'react-router';
import { BookingsList } from '../components/bookings/BookingsList';
import { SearchInput } from '../components/shared/SearchInput';
import { bookingsActions, bookingsSelectors } from '../store/bookings/index';

@connect(
  (state) => {
    return {
      params: bookingsSelectors.getParams(state),
      bookings: bookingsSelectors.getBookings(state),
    };
  }
)
export class BookingsIndex extends React.Component {
  static contextTypes = {
    router: React.PropTypes.object,
    store: React.PropTypes.object,
  };

  constructor(props, context) {
    super(props, context);
    this.deleteBooking = this.deleteBooking.bind(this);
    this.handleSearch = this.handleSearch.bind(this, 'title_like');
  }

  componentDidMount() {
    this.fetchBookings({});
  }

  fetchBookings(params) {
     this.context.store.dispatch(bookingsActions.fetchBookings(params));
     
  }

  deleteBooking(booking) {
    this.context.store.dispatch(bookingsActions.deleteBooking(booking));
  }

  handleSearch(field, value) {
    this.fetchBookings({q: value})
  }

  render() {
    const {
      params,
      bookings,
    } = this.props;


    return (
      <div>
        <div className="row">
          <div className="col-md-6">
            <SearchInput
              value={params.q}
              onSearch={this.handleSearch}
              placeholder="Title search ..."
            />
          </div>
          <div className="col-md-6 text-right">
            <Link to="/bookings/new" className="btn btn-primary">New Booking</Link>
          </div>
        </div>
        {bookings.length > 0 &&
        <BookingsList bookings={bookings} onDelete={this.deleteBooking}/>}
      </div>
    );
  }
}
