import styled from 'styled-components';

import { device } from '~/components/shared/variables/devices';

export const Container = styled.div`
  display: flex;
  flex-direction: column;
  max-width: 90%;
  margin: auto;

  @media ${device.laptop} {
    padding: 0 60px;
    max-width: 100%;
  }
`;

export const Thead = styled.div`
  flex: 1;
  display: flex;
  flex-direction: column;
  background: white;
`;

export const Tbody = styled.div`
  flex: 1;
  display: flex;
  flex-direction: column;
  background: white;
`;

export const Row = styled.div`
  font-size: ${props => (props.heading ? '1.8rem' : '1.4rem')};
  font-weight: ${props => (props.bold ? 'bold' : 'normal')};
  padding: 1.25rem 0;
  border: 1px solid ${props => (props.bg ? props.bg : 'transparent')};
  flex: 1;
  display: flex;
  color: ${props => (props.color ? props.color : 'inherit')};
  background: ${props => (props.bg ? props.bg : 'transparent')};
  flex-direction: ${props => (props.inverse ? 'row' : 'column')};
  width: 100%;
`;

export const Column = styled.div`
  font-size: ${props => (props.heading ? '1.8rem' : '1.4rem')};
  font-weight: ${props => (props.bold ? 'bold' : 'normal')};
  color: ${props => (props.color ? props.color : 'inherit')};
  display: flex;
  flex: ${props => (props.flex || props.flex === 0 ? props.flex : '1')};
  align-items: center;
  justify-content: center;
  flex-direction: ${props => (props.inverse ? 'row' : 'column')};
  width: 100%;
`;
