//$Id: check_status.bbb.js,v 1.1.2.1 2010/03/15 16:55:31 sanduhrs Exp $
Drupal.behaviors.bbbCheckStatusInit = function () {
  Drupal.bbbCheckStatus();
  setInterval("Drupal.bbbCheckStatus();", 5000);
}

Drupal.bbbCheckStatus = function () {
  var url = bbb_check_status_url;
  $.getJSON(url, function(data) {
    console.log(data);
    if (data.is_running == true) {
      location.href = location.href + '/meeting/attend';
    }
  });
}