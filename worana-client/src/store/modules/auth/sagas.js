import { all, call, put, takeLatest } from 'redux-saga/effects';

import api from '~/services/api';
import history from '~/services/history';

import { SIGN_IN_REQUEST } from './actionTypes';
import { signInSuccess, signFailure } from './actions';

export function* singIn({
  payload: { email, password, client_id, client_secret },
}) {
  try {
    const response = yield call(
      api.post,
      'oauth/token',
      `grant_type=password&client_id=${client_id}&client_secret=${client_secret}&username=${email}&password=${password}`
    );

    const { access_token, refresh_token } = response.data;

    yield put(signInSuccess(access_token, refresh_token));

    history.push('/dashboard');
  } catch (error) {
    console.tron.log(error);
    yield put(signFailure());
  }
}

export default all([takeLatest(SIGN_IN_REQUEST, singIn)]);
