import React from 'react';
import Loadable from 'react-loadable';
import Loading from './../../reusable/utils/Loader';
const Footer = Loadable({
  loader: () => import('./FooterTemplate'/* webpackChunkName: 'footer' */),
  loading: Loading,
});

export default Footer;