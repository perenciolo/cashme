import React from 'react';
import PropTypes from 'prop-types';

import { Button } from './styles';

export default function BtnPrimary({ type, children }) {
  return <Button type={type}>{children}</Button>;
}

BtnPrimary.propTypes = {
  type: PropTypes.string,
  children: PropTypes.element.isRequired,
};

BtnPrimary.defaultProps = {
  type: 'button',
};
