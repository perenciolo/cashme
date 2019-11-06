import React from 'react';
import { Switch } from 'react-router-dom';

import Route from '~/routes/Route';

import SignIn from '~/pages/Sign/In';
import Dashboard from '~/pages/Dashboard';
import Profile from '~/pages/Profile';

export default function Routes() {
  return (
    <Switch>
      <Route path="/" exact component={SignIn} />

      <Route path="/dashboard" isPrivate component={Dashboard} />
      <Route path="/profile" isPrivate component={Profile} />

      <Route path="/" component={() => <h3>404! Not Found...</h3>} />
    </Switch>
  );
}
