import React from 'react';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux'
import { Link } from 'react-router';
import { OwnersList } from '../components/owner/OwnersList';
import { SearchInput } from '../components/shared/SearchInput';
import { ownersActions, ownersSelectors } from '../store/owners/index';

@connect(
  (state) => {
    return {
      params: ownersSelectors.getParams(state),
      owners: ownersSelectors.getOwners(state),
    };
  }
)
export class OwnersIndex extends React.Component {
  static contextTypes = {
    router: React.PropTypes.object,
    store: React.PropTypes.object,
  };

  constructor(props, context) {
    super(props, context);
    this.deleteOwner= this.deleteOwner.bind(this);
    this.handleSearch = this.handleSearch.bind(this, 'title_like');
  }

  componentDidMount() {
    this.fetchOwners({});
  }

  fetchOwners(params) {

    this.context.store.dispatch(ownersActions.fetchOwners(params));
  }

  deleteOwner(owner) {
    this.context.store.dispatch(ownersActions.deleteOwner(owner));
  }

  handleSearch(field, value) {
    this.fetchOwners({q: value})
  }

  render() {
    const {
      params,
      owners,
    } = this.props;
console.log(owners);
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
            <Link to="/owners/new" className="btn btn-primary">New Owner</Link>
          </div>
        </div>
        {owners.length > 0 &&
        <OwnersList owners={owners} onDelete={this.deleteOwner}/>}
      </div>
    );
  }
}
