export function getParams(state) {
  return state.owners.params;
}

export function getOwner(state, id) {
  return state.owners.byId.byId;
}

export function getOwners(state) {
  return Object.values(state.owners.byId);
}

export function getredirect(state) {

  return state.owners.message;
}
