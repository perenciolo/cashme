import React from 'react';
import PropTypes from 'prop-types';
import { Route, Redirect } from 'react-router-dom';

import DefaultLayout from '~/pages/_layouts/default';
import AuthLayout from '~/pages/_layouts/auth';

import { store } from '~/store';

export default function RouteWrapper({
  component: Component,
  isPrivate,
  ...rest
}) {
  const { signed } = store.getState().auth;

  // If not signed redirect to sign in / sign up route.
  if (!signed && isPrivate) return <Redirect to="/" />;
  // If signed and hit sign in or sign up route redirect to dashboard.
  if (signed && !isPrivate) return <Redirect to="/dashboard" />;

  const Layout = signed ? DefaultLayout : AuthLayout;

  // If signed in redirect to desired route.
  return (
    <Route
      {...rest}
      render={props => (
        <Layout>
          <Component {...props} />
        </Layout>
      )}
    />
  );
}

RouteWrapper.propTypes = {
  isPrivate: PropTypes.bool,
  component: PropTypes.oneOfType([PropTypes.element, PropTypes.func])
    .isRequired,
};

RouteWrapper.defaultProps = {
  isPrivate: false,
};
