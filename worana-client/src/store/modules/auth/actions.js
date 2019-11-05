import { SIGN_IN_REQUEST, SIGN_IN_SUCCESS, SIGN_FAILURE } from './actionTypes';

export const signInRequest = (email, password) => ({
  type: SIGN_IN_REQUEST,
  payload: { email, password },
});

export const signInSuccess = (token, refresh_token, user) => ({
  type: SIGN_IN_SUCCESS,
  payload: { token, refresh_token, user },
});

export const signFailure = () => ({ type: SIGN_FAILURE });
