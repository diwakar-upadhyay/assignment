import React from 'react';
import Textarea from 'react-textarea-autosize';
import { ownersActions, ownersSelectors } from '../store/owners/index';
import { connect } from 'react-redux';
import { isEqual } from 'lodash';

@connect(
  (state, props) => {
    return {
       owner: ownersSelectors.getOwner(state, props.params.ownerId),
       message: ownersSelectors.getredirect(state),
    };
  }
)
export class OwnersEdit extends React.Component {
  static contextTypes = {
    router: React.PropTypes.object,
    store: React.PropTypes.object
  };

  static propTypes = {
    params: React.PropTypes.object,
    owner: React.PropTypes.object,
    message: React.PropTypes.object,
  };

  constructor(props, context) {
    super(props, context);
    this.state = {
      ...this.state,
      ownerId: this.props.params.ownerId,
      owner: {po_name: '', po_email:'',po_phone:'',po_booking_limit:'',po_description:''},
    };
    this.styles = {
            backgroundColor: 'white',
            color: 'red',
        };
  }


  componentWillReceiveProps(nextProps) {
       if(nextProps.message==1){
          this.props.history.push('/owners')
       }

    if (!isEqual(nextProps.owner, this.state.owner)) {
      this.setState({...this.state, owner: nextProps.owner, message: nextProps.message });

    }
  }


  componentDidMount() {
  //  this.setState({...this.state, message: ''  });
    if (this.state.ownerId) {
       this.context.store.dispatch(ownersActions.fetchOwner(this.props.params.ownerId));
    }
  }

  handleChange(field, e) {
    const owner = Object.assign({}, this.state.owner, {[field]: e.target.value});
    this.setState(Object.assign({}, this.state, {owner}));
  }

  handleSubmit() {
    if (this.state.ownerId) {
      this.context.store.dispatch(ownersActions.updateOwner(this.state.owner));
    } else {
      this.context.store.dispatch(ownersActions.createOwner(this.state.owner));
    }
  }

  render() {
      const {
        owners,
        message
      } = this.props;
      var showmessage='';
      if(message==0){   showmessage='maximum limit is 1000';}

    return (
      <form onSubmit={this.handleSubmit.bind(this)} noValidate>
        <div className="form-group">
          <label className="label-control">Owner Name</label>
          <input
            type="text"
            className="form-control"
            value={this.state.owner.po_name}
            onChange={this.handleChange.bind(this, 'po_name')} />
        </div>
        <div className="form-group">
          <label className="label-control">Owner Email</label>
          <input
            type="text"
            className="form-control"
            value={this.state.owner.po_email}
            onChange={this.handleChange.bind(this, 'po_email')} />
        </div>
        <div className="form-group">
          <label className="label-control">Owner Phone</label>
          <input
            type="text"
            className="form-control"
            value={this.state.owner.po_phone}
            onChange={this.handleChange.bind(this, 'po_phone')} />
        </div>

        <div className="form-group">
          <label className="label-control">Owner Booking Limit</label>
          <input
            type="text"
            className="form-control"
            value={this.state.owner.po_booking_limit}
            onChange={this.handleChange.bind(this, 'po_booking_limit')} />
            <div className="form-group" style={this.styles}> {showmessage}</div>
        </div>

        <div className="form-group">
          <label className="label-control">Owner description</label>
          <Textarea
            className="form-control"
            value={this.state.owner.po_description}
            onChange={this.handleChange.bind(this, 'po_description')} />
        </div>

        <button type="submit" className="btn btn-default">
          {this.state.ownerId ? 'Update' : 'Create' } Owner
        </button>

      </form>
    );
  }
}
