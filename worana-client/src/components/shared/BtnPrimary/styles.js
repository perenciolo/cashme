import styled from 'styled-components';

import {
  accentColor,
  primaryColorShadow,
} from '~/components/shared/variables/colors';

export const Button = styled.button`
  background: ${accentColor};
  padding: 1rem;
  border-radius: 0.5rem;
  border: none;
  font-size: 1.6rem;
  font-weight: bold;
  color: ${primaryColorShadow};
  transition: all 0.3s ease-in-out;

  &:hover {
    background: ${primaryColorShadow};
    color: ${accentColor};
  }
`;
