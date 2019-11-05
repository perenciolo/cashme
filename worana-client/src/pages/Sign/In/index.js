import React from 'react';
import { useDispatch } from 'react-redux';
import { Link } from 'react-router-dom';
import { Input } from '@rocketseat/unform';
import * as Yup from 'yup';

import logo from '~/assets/img/logo.svg';

import { SignForm } from '~/pages/_layouts/auth/styles';
import BtnPrimary from '~/components/shared/BtnPrimary';
import { signInRequest } from '~/store/modules/auth/actions';

const schema = Yup.object().shape({
  login: Yup.string().required('O e-mail é obrigatório'),
  password: Yup.string().required('A senha é obrigatória.'),
});

export default function Signin() {
  const dispatch = useDispatch();

  function handleSubmit({ login: email, password }) {
    dispatch(signInRequest(email, password));
  }

  return (
    <>
      <img src={logo} alt="WORANA" />
      <SignForm schema={schema} onSubmit={handleSubmit}>
        <Input name="login" type="string" placeholder="Seu e-mail" />
        <Input name="password" type="password" placeholder="Sua senha" />

        <BtnPrimary type="submit">
          <>Acessar</>
        </BtnPrimary>
        <Link to="/register">Criar conta gratuita</Link>
      </SignForm>
    </>
  );
}
