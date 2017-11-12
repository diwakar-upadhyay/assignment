import React from 'react';
import Textarea from 'react-textarea-autosize';
import { BookingsOwners } from './BookingsOwners';
import { bookingsActions, bookingsSelectors } from '../store/bookings/index';
import { connect } from 'react-redux';
import { isEqual } from 'lodash';


@connect(
  (state, props) => {

    return {
      booking: bookingsSelectors.getBooking(state, props.params.bookingId),
      customer: bookingsSelectors.getCustomer(state),
      message: bookingsSelectors.getredirect(state),
    };
  }
)
export class BookingsEdit extends React.Component {
  static contextTypes = {
    router: React.PropTypes.object,
    store: React.PropTypes.object
  };

  static propTypes = {
    params: React.PropTypes.object,
    booking: React.PropTypes.object,
    message: React.PropTypes.object,
  };

  constructor(props, context) {
    super(props, context);
    this.state = {
      ...this.state,
      bookingId: this.props.params.bookingId,
      booking: {bo_name: '', bo_email: '',bo_phone:'', bo_amount:'',bo_description:''},
      //selected: 0
    };
    this.styles = {
            backgroundColor: 'white',
            color: 'red',
        };

  }

  componentWillReceiveProps(nextProps) {
    if (!isEqual(nextProps.booking, this.state.booking)) {
      this.setState({...this.state, booking: nextProps.booking,customer: nextProps.customer,message: nextProps.message});
    }
  }

  componentDidMount() {
    this.context.store.dispatch(bookingsActions.selectCustomers());
    if (this.state.bookingId) {

      this.context.store.dispatch(bookingsActions.fetchBooking(this.props.params.bookingId));
    }

  }


  componentWillUpdate (nextProps) {
  }

  handleChange(field, e) {
    this.setState({ selected: e.target.value});
    const booking = Object.assign({}, this.state.booking, {[field]: e.target.value});
    this.setState(Object.assign({}, this.state, {booking}));
  }

  handleSubmit() {
    if (this.state.bookingId) {
      this.context.store.dispatch(bookingsActions.updateBooking(this.state.booking));
    } else {
      this.context.store.dispatch(bookingsActions.createBooking(this.state.booking));
    }
  }


  render() {
    const {
      customer,message,booking
    } = this.props;
    var showmessage='';
    if(message==0){   showmessage='Given amount is excedded from owner account';}
    var mycust=this.props.customer;
    let obj={};
    var cutomerOptions ='';
    var sal='';
    var custOptions=[];
    var name='';
        Object.keys(mycust).forEach(function(key) {
                Object.keys(mycust[key]).forEach(function(key2, value) {
                    custOptions.push(<option   value={mycust[key][key2].po_id}>{mycust[key][key2].po_name}</option>)
                 })
            });

    return (
      <form onSubmit={this.handleSubmit.bind(this)} noValidate>
      <div className="form-group">
        <label className="label-control">Property Owner</label>
        <select
                className="input form-control"
                onChange={this.handleChange.bind(this, 'po_id')}
                value={this.state.selected || ''}>
                {custOptions}
            </select>

      </div>

      <div className="form-group">
        <label className="label-control">Booking name</label>
      <input
          type="text"
          className="form-control"
            value={this.state.booking.bo_name}
           onChange={this.handleChange.bind(this, 'bo_name')}
         />
      </div>

        <div className="form-group">
          <label className="label-control">Booking email</label>
        <input
            type="text"
            className="form-control"
            value={this.state.booking.bo_email}
            onChange={this.handleChange.bind(this, 'bo_email')} />
        </div>

        <div className="form-group">
          <label className="label-control">Booking phone</label>
        <input
            type="text"
            className="form-control"
             value={this.state.booking.bo_phone}
            onChange={this.handleChange.bind(this, 'bo_phone')} />
        </div>


        <div className="form-group">
          <label className="label-control">Booking detail</label>
          <Textarea
            className="form-control"
            value={this.state.booking.bo_description}
            onChange={this.handleChange.bind(this, 'bo_description')} />
        </div>

        <div className="form-group">
          <label className="label-control">Booking Amount</label>
          <input
              type="text"
              className="form-control"
                value={this.state.booking.bo_amount}
              onChange={this.handleChange.bind(this, 'bo_amount')} />
                <div className="form-group" style={this.styles}> {showmessage}</div>
        </div>

        <button type="submit" className="btn btn-default">
          {this.state.bookingId ? 'Update' : 'Create' } Booking
        </button>
      </form>
    );
  }
}
