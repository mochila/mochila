/**
 * XULSchoolChrome namespace.
 */
if ("undefined" == typeof(MochilaChrome)) {
  var MochilaChrome = {};
};

/**
 * Controls the browser overlay for the Hello World extension.
 */
MochilaChrome.BrowserOverlay = {
  /**
   * Says 'Hello' to the user.
   */
  sayHello : function(aEvent) {
    let stringBundle = document.getElementById("urlgrabber-string-bundle");
    let message = stringBundle.getString("urlgrabber.label");
    
    var xmlhttp = new XMLHttpRequest();

    /*
    if (window.XMLHttpRequest) {
      xmlhttp = new XMLHttpRequest();
    } else {
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    */
    xmlhttp.onreadystatechange=function() {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        alert("Sent "+ xmlhttp.responseText + " to the Mochila Database.");
      }
    }

    xmlhttp.open("GET","http://mochila.coffeecupcoding.com/dagrADD.php?q="+window.content.document.location, true);

    xmlhttp.send();
  }
};
