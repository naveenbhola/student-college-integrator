import React from 'react';

export default function Loading(props) {
  if (props.isLoading) {
    if (props.timedOut) {
      console.log('hi time');
      return <div>Loader timed out!</div>;
    } else if (props.pastDelay) {
      console.log('hi pastDelay');
      return <div>Loading...</div>;
    } else {
      console.log('hi null',props.isLoading);
      return null;
    }
  } else if (props.error) {
    return <div>Error! Component failed to load</div>;
  } else {
    console.log('hi last null');
    return null;
  }
}
