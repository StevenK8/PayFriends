function $_GET(param) {
	var vars = {};
	window.location.href.replace( location.hash, '' ).replace( 
		/[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
		function( m, key, value ) { // callback
			vars[key] = value !== undefined ? value : '';
		}
	);

	if ( param ) {
		return vars[param] ? vars[param] : null;	
	}
	return vars;
}

function getXMLHttpRequest() {
	var xhr = null;
	
	if(window.XMLHttpRequest || window.ActiveXObject) {
		if(window.ActiveXObject) {
			try {
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch(e) {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		}
		else {
			xhr = new XMLHttpRequest(); 
		}
	} 
	else {
		return null;
	}
	
	return xhr;
}

(function($) {
  'use strict';
  $(function() {
    $('.todo-list-add-btn').on("click", function(event) {
      event.preventDefault();
      var item = $(this).prevAll('.todo-list-input').val();
      
      if (item) {
        var xhr = getXMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("confirm-invite").innerHTML = "Invitation envoyée!";
            }
        };
        xhr.open("GET","ajoutMembre.php?username="+item+"&ide="+$_GET('id'),true);
        xhr.send();
      }

    });

  });
})(jQuery);