import React from 'react';
import { Link } from 'react-router';

export const BookingsListRow = ({booking, onDelete}) => {

  return (
    <tr key={booking.bo_id}>
      <td>{booking.bo_id}</td>
      <td>{booking.bo_name}</td>
      <td>{booking.bo_email}</td>
      <td>{booking.bo_phone}</td>
      <td>{booking.bo_amount}</td>
      <td>
        <div className="btn-toolbar pull-right">
          <Link to={`/bookings/${booking.bo_id}`} className="btn btn-primary">Edit</Link>
            <a onClick={onDelete.bind(this, booking)} className="btn btn-danger">Delete</a>
        </div>
      </td>
    </tr>
  )
};
