import React from 'react';
import { Link } from 'react-router';

export const OwnersListRow = ({owner, onDelete}) => {
  return (
    <tr key={owner.po_id}>
      <td>{owner.po_id}</td>
      <td>{owner.po_name}</td>
      <td>{owner.po_email}</td>
        <td>{owner.po_booking_limit}</td>
      <td>
        <div className="btn-toolbar pull-right">
          <Link to={`/owners/${owner.po_id}`} className="btn btn-primary">Edit</Link>
          <a onClick={onDelete.bind(this, owner)} className="btn btn-danger">Delete</a>
        </div>
      </td>
    </tr>
  )
};
