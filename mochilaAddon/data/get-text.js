// When submit buton pressed, sent message to main
var sbutton = document.getElementById("sbutton");
sbutton.addEventListener("click", function() {
  // Get the input form the 2 text fields
  var titleInput = document.getElementById("dagrtitle");
  var tagInput = document.getElementById("dagrtag");
  var dtitle = titleInput.value;
  var dtag = tagInput.value;

  // Form the 2 inputs into url parameters
  var text = "&title="+dtitle+"&tags="+dtag;

  self.port.emit("buttonpressed", text);
});
