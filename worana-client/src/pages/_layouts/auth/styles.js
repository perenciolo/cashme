import styled from 'styled-components';
import { Form } from '@rocketseat/unform';

import {
  primaryColorShadow,
  accentColor,
  dangerColor,
} from '~/components/shared/variables/colors';

export const Wrapper = styled.div`
  height: 100%;
`;

export const Content = styled.div`
  height: 100%;
  width: 100%;
  max-width: 30rem;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-content: center;

  img {
    max-width: 80%;
    height: auto;
    margin: 0 auto;
  }
`;

export const SignForm = styled(Form)`
  margin-top: 2rem;
  display: flex;
  flex-direction: column;

  > input {
    flex: 1 0;
    padding: 1rem;
    border: none;
    background: ${primaryColorShadow};
    color: white;
    border-radius: 0.5rem;

    &::placeholder,
    &::-webkit-input-placeholder {
      color: white;
      opacity: 0.8;
    }

    + input {
      margin-top: 1rem;
    }

    + span {
      font-weight: bold;
      display: block;
      color: ${dangerColor};
      padding: 1rem 0 0.5rem;
      text-align: center;
    }
  }

  > button {
    margin-top: 2rem;
  }

  > a {
    font-size: 1.6rem;
    margin: 1rem auto 0;
    display: block;
    color: ${accentColor};
    transition: color 0.1s ease-in-out;

    &:hover {
      font-weight: bold;
      color: white;
      text-align: center;
    }
  }
`;
