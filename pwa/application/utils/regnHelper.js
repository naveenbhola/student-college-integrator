/*
Wrapper functions for PWA desktop registration with jQuery.
This JS file will NOT be used when registration is moved to PWA.
*/

export const showRegistrationFormWrapper = (formData) => {
  registrationForm.showRegistrationForm(formData);
}

export const showResponseFormWrapper = (listingId, actionType, listingType, formData, extraParam) => {
  responseForm.showResponseForm(listingId, actionType, listingType, formData, extraParam);
}

export const showLoginFormWrapper = (formData) => {
  registrationForm.showLoginLayer(formData);
}

export const signOutUserWrapper = () => {
  SignOutUser();
}

export const showResetPasswordLayerWrapper = (uname, uemail, rgrnContext, ugrp) => {
  registrationForm.showResetPasswordLayer(uname, uemail, rgrnContext, ugrp);
}
