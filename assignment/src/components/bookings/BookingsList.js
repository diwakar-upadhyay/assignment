import React from 'react';
import { BookingsListRow } from './BookingsListRow';

export const BookingsList = ({bookings, onDelete}) => {
  return (
    <table className="table table-hover">
      <thead>
      <tr>
        <th>Id</th>
        <th>Booking name</th>
        <th>Email</th>
        <th>Phone no</th>
        <th>Amount</th>
        <th></th>
      </tr>
      </thead>
      <tbody>
        {bookings.map(booking => BookingsListRow({booking, onDelete}))}
      </tbody>
    </table>
  )
};
