import { SIGN_IN_REQUEST, SIGN_IN_SUCCESS, SIGN_FAILURE } from './actionTypes';

export const signInRequest = (email, password, client_id, client_secret) => ({
  type: SIGN_IN_REQUEST,
  payload: { email, password, client_id, client_secret },
});

export const signInSuccess = (access_token, refresh_token) => ({
  type: SIGN_IN_SUCCESS,
  payload: { access_token, refresh_token },
});

export const signFailure = () => ({ type: SIGN_FAILURE });
