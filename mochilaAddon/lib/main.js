var data = require("sdk/self").data;
var tabs = require('tabs');
var Request = require("sdk/request").Request;
 
// Create a panel whose content is defined in "text-entry.html".
// Attach a content script called "get-text.js".
var text_entry = require("sdk/panel").Panel({
  width: 300,
  height: 300,
  contentURL: data.url("text-entry.html"),
  contentScriptFile: data.url("get-text.js")
});
 
// Listen for messages called "text-entered" coming from
// the content script. The message payload is the text the user
// entered.
// In this implementation we'll just log the text to the console.
text_entry.port.on("buttonpressed", function (text) {
  // Get the current URL
  console.log("Sending GET request to http://mochila.coffeecupcoding.com/dagrADD.php?q="+tabs.activeTab.url+text);

  // Send a GET request to the mochila server with the url
  var httpRequest = Request({
    url: "http://mochila.coffeecupcoding.com/dagrADD.php?q="+tabs.activeTab.url+text,
    onComplete: function (response) {
      console.log("Received response:"+ response.text);
    }

  }).get();

  text_entry.hide();
});
 
// Create a widget, and attach the panel to it, so the panel is
// shown when the user clicks the widget.
require("sdk/widget").Widget({
  label: "Text entry",
  id: "text-entry",
  contentURL: data.url("mochila.png"),
  panel: text_entry
});
