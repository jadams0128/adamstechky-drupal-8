(function ($) {
   
   ;ADAMSTECHKY = {
    //Needed to capitalize the first letter and lowercase the rest
    capitalize: function (string) {
      return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
    },

    //Parses the phone variable to phonenumber format.
    formatPhoneNumber: function (s) {
      var s2 = (""+s).replace(/\D/g, '');
      var m = s2.match(/^(\d{3})(\d{3})(\d{4})$/);
      return (!m) ? null : "(" + m[1] + ") " + m[2] + "-" + m[3];
    },

    //Converts seconds to hours:mins:seconds.
    seconds2time: function (seconds) {
      var hours   = Math.floor(seconds / 3600);
      var minutes = Math.floor((seconds - (hours * 3600)) / 60);
      var seconds = seconds - (hours * 3600) - (minutes * 60);

      // round seconds
      seconds = Math.round(seconds * 100) / 100

      var result = (hours < 10 ? "0" + hours : hours);
          result += ":" + (minutes < 10 ? "0" + minutes : minutes);
          result += ":" + (seconds  < 10 ? "0" + seconds : seconds);
      return result;
    },

    //Takes todays date and subtracts day from it.
    getYesterdaysDate: function (day) {
        var date = new Date();
        date.setDate(date.getDate() - day);
        return date.getFullYear() + '-' + (date.getMonth()+1) + '-' + ("0" + date.getDate()).slice(-2);
    },

    sanitize: function(input) {
      if (input != null) {
        var output = input.replace(/<script[^>]*?>.*?<\/script>/gi, '').
    		 replace(/<[\/\!]*?[^<>]*?>/gi, '').
    		 replace(/<style[^>]*?>.*?<\/style>/gi, '').
    		 replace(/<![\s\S]*?--[ \t\n\r]*>/gi, '');
  	    return output;
      }
    },

    convertMilesToMeters: function(miles) {
      return miles * 1609.34;
    },
    
    renderMap: function() {
        var map;
        var marker;
        var uluru = {lat: 37.350162, lng: -83.105557};
        
      	var latlng = new google.maps.LatLng(37.350162, -83.105557);
	map = new google.maps.Map(document.getElementById('map-canvas'), {
	  center: latlng,
	  zoom: 14
	});
	
	marker = new google.maps.Marker({
          position: uluru,
          map: map
        });
    }
  }
  
  $(document).ready(function(){
     ADAMSTECHKY.renderMap(); 
  });
}(jQuery));