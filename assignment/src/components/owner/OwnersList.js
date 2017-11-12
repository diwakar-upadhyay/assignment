import React from 'react';
import { OwnersListRow } from './OwnersListRow';

export const OwnersList = ({owners, onDelete}) => {
  return (
    <table className="table table-hover">
      <thead>
      <tr>
      <th>Id</th>
      <th>Title</th>
      <th>Email</th>
      <th>Amount</th>
      <th></th>
      </tr>
      </thead>
      <tbody>
      {owners.map(owner => OwnersListRow({owner, onDelete}))}
      </tbody>
    </table>
  )
};
