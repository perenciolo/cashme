import produce from 'immer';
import { SIGN_IN_SUCCESS } from './actionTypes';

const INITIAL_STATE = {
  access_token: null,
  refresh_token: null,
  signed: false,
  loading: false,
};

export default function auth(state = INITIAL_STATE, action) {
  switch (action.type) {
    case SIGN_IN_SUCCESS:
      return produce(state, draft => {
        draft.access_token = action.payload.access_token;
        draft.refresh_token = action.payload.refresh_token;
        draft.signed = true;
      });
    default:
      return state;
  }
}
