import React from 'react';
import { Link } from 'react-router-dom';
import { Input } from '@rocketseat/unform';

import Yup from '~/services/validations';

import logo from '~/assets/img/logo.svg';

import { SignForm } from '~/pages/_layouts/auth/styles';
import BtnPrimary from '~/components/shared/BtnPrimary';

const schema = Yup.object().shape({
  login: Yup.string().required('O e-mail é obrigatório'),
  password: Yup.string()
    .min(6, 'A senha deve ter pelo menos 6 caracteres')
    .required('A senha é obrigatória.'),
  passwordConfirm: Yup.string()
    .equalTo(Yup.ref('password'), 'As senhas não correspondem.')
    .required('A confirmação de senha é obrigatória.'),
});

export default function Signup() {
  function handleSubmit(data) {
    console.tron.log(data);
  }

  return (
    <>
      <img src={logo} alt="WORANA" />
      <SignForm schema={schema} onSubmit={handleSubmit}>
        <Input name="login" type="string" placeholder="Seu e-mail" />
        <Input name="password" type="password" placeholder="Sua senha" />
        <Input
          name="passwordConfirm"
          type="password"
          placeholder="Confirme sua senha"
        />

        <BtnPrimary type="submit">
          <>Criar conta</>
        </BtnPrimary>
        <Link to="/">Já possuo uma conta autenticar</Link>
      </SignForm>
    </>
  );
}
