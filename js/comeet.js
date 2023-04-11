comeet_init = {
   "token":               comeetvar.comeet_token,
   "company-uid":         comeetvar.comeet_uid,
   "candidate-source-storage": false,
   "color":               comeetvar.comeet_color,
   "background-color":    comeetvar.comeet_bgcolor,
   "css-url":             comeetvar.comeet_css_url,
   "apply-as-employee":   comeetvar.comeet_apply_as_employee,
   "field-email-required":   comeetvar.comeet_field_email_required,
   "field-phone-required": comeetvar.comeet_field_phone_required,
   "field-resume": comeetvar.comeet_field_resume,
   "field-linkedin": comeetvar.comeet_field_linkedin,
   "require-profile": comeetvar.comeet_require_profile,
   "field-website": comeetvar.comeet_field_website,
   "field-website-required": comeetvar.comeet_field_website_required,
   "field-coverletter": comeetvar.comeet_field_coverletter,
   "field-coverletter-required": comeetvar.comeet_field_coverletter_required,
   "field-portfolio": comeetvar.comeet_field_portfolio,
   "field-portfolio-required": comeetvar.comeet_field_portfolio_required,
   "field-personalnote": comeetvar.comeet_field_personalnote,
   "field-personalnote-required": comeetvar.comeet_field_personalnote_required,
   "button-text": comeetvar.comeet_button_text,
   "font-size": comeetvar.comeet_font_size,
   "button-font-size": comeetvar.comeet_button_font_size,
   "labels-position": comeetvar.comeet_labels_position,
   "button-color": comeetvar.comeet_button_color,
   "social-pinterest": comeetvar.comeet_social_pinterest,
   "social-whatsapp": comeetvar.comeet_social_whatsapp,
   "social-employees": comeetvar.comeet_social_employees,
   "social-show-title": comeetvar.comeet_social_show_title,
   "social-share-url": comeetvar.comeet_social_share_url,
   "social-color": comeetvar.comeet_social_color,
};
if(comeetvar.comeet_css_cache == 'set_no_cache'){
   comeet_init['css-cache'] = false;
}
if(comeetvar.comeet_thankyou_url != 'no_thankyou_page'){
   comeet_init['thankyou-url'] = comeetvar.comeet_thankyou_url;
}

window.comeetInit = function() {
   COMEET.init(comeet_init);
};

(function(d, s, id) {
   var js, fjs = d.getElementsByTagName(s)[0];
   if (d.getElementById(id)) {
      return;
   }
   js = d.createElement(s);
   js.id = id;
   js.src = "//www.comeet.co/careers-api/api.js";
   fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'comeet-jsapi'));