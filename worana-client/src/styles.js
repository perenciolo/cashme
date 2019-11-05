import { createGlobalStyle } from 'styled-components';

import {
  primaryColor,
  primaryColorShadow,
} from '~/components/shared/variables/colors';

const GlobalStyle = createGlobalStyle`
  @import url('https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap');

  * {
    margin: 0;
    padding: 0;
    outline: 0;
    box-sizing: border-box;
  }

  *:focus {
    outline: 0;
  }

  html,
  body,
  #root {
    height:100%;
    font-size: 10px;
  }

  body {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    background: linear-gradient(90deg, ${primaryColor}, ${primaryColorShadow});
  }

  #root, input, button {
    font-family: 'Roboto', sans-serif;
    font-size: 1.4rem;
  }

  a {
    text-decoration: none;
  }

  ul {
    list-style: none;
  }

  button {
    cursor: pointer;
  }
`;

export default GlobalStyle;
