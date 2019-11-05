import { all, call, put, takeLatest } from 'redux-saga/effects';

import api from '~/services/api';
import history from '~/services/history';

import { SIGN_IN_REQUEST } from './actionTypes';
import { signInSuccess, signFailure } from './actions';

export function* singIn({ payload: { email, password } }) {
  try {
    const response = yield call(
      api.post,
      'oauth/token',
      `grant_type=password&client_id=ef359a3d-4cb0-4253-83c8-20b0a5a520de&client_secret=${password}&username=${email}&password=${password}`
    );

    const { token, refresh_token, user } = response.data;

    yield put(signInSuccess(token, refresh_token, user));

    history.push('/dashboard');
  } catch (error) {
    yield put(signFailure());
  }
}

export default all([takeLatest(SIGN_IN_REQUEST, singIn)]);
