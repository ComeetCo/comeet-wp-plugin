comeet_init = {
   "token":               comeetvar.comeet_token,
   "company-uid":         comeetvar.comeet_uid,
   "candidate-source-storage": false,
   "color":               comeetvar.comeet_color,
   "background-color":    comeetvar.comeet_bgcolor,
   "thankyou-url":        comeetvar.comeet_thankyou_url,
   "css-url":             comeetvar.comeet_css_url,

};
if(comeetvar.comeet_css_cache == 'set_no_cache'){
   comeet_init['css-cache'] = false;
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
(function(){var a=function(){window.COMEET.set("candidate-source-storage",!0)};window.COMEET?a():window.comeetUpdate=a})();
