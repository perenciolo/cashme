import React from 'react';
import { useDispatch } from 'react-redux';
import { Input } from '@rocketseat/unform';
import * as Yup from 'yup';

import logo from '~/assets/img/logo.svg';

import { SignForm } from '~/pages/_layouts/auth/styles';
import BtnPrimary from '~/components/shared/BtnPrimary';
import { signInRequest } from '~/store/modules/auth/actions';

const schema = Yup.object().shape({
  client_id: Yup.string().required('O Client ID é obrigatório'),
  client_secret: Yup.string().required('O Client Secret é obrigatório'),
  login: Yup.string().required('O e-mail é obrigatório'),
  password: Yup.string().required('A senha é obrigatória.'),
});

export default function Signin() {
  const dispatch = useDispatch();

  function handleSubmit({ login: email, password, client_id, client_secret }) {
    dispatch(signInRequest(email, password, client_id, client_secret));
  }

  return (
    <>
      <img src={logo} alt="WORANA" />
      <SignForm schema={schema} onSubmit={handleSubmit}>
        <Input name="client_id" type="string" placeholder="O Client ID" />
        <Input
          name="client_secret"
          type="password"
          placeholder="O Client Secret"
        />
        <Input name="login" type="string" placeholder="Seu username" />
        <Input name="password" type="password" placeholder="Sua senha" />

        <BtnPrimary type="submit">
          <>Acessar</>
        </BtnPrimary>
      </SignForm>
    </>
  );
}
