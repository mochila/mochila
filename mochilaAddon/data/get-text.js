// Listen for the show message and populate selector
self.port.on("show", function(myjson) {
  var select = document.getElementById("parentSelector");
  for (var i = 0; i < myjson.length; i++) {
    var opt = document.createElement('option');
    opt.innerHTML = myjson[i].DAGR_TITLE;
    opt.value = myjson[i].DAGR_TITLE;
    select.appendChild(opt);
  } 
});

// When submit buton pressed, sent message to main
var sbutton = document.getElementById("sbutton");
sbutton.addEventListener("click", function() {
  // Get the input from the 2 text fields
  var titleInput = document.getElementById("dagrtitle");
  var tagInput = document.getElementById("dagrtag");
  var authorInput = document.getElementById("dagrauthor");
  var dtitle = titleInput.value;
  var dtag = tagInput.value;
  var dauthor = authorInput.value;

  // Get the value from the Parent checkbox and selector
  var parentOnInput = document.getElementById("useParent");
  var parentOn;
  var pInput = document.getElementById("parentSelector");
  var parentSelect;
  if (parentOnInput.checked) {
    parentOn = 1;
    parentSelect = pInput.options[pInput.selectedIndex].value;
  } else {
    parentOn = 0;
    parentSelect = 0;
  }
  
  // Form the 5 inputs into url parameters
  var text = "&title="+dtitle+"&tags="+dtag+"&author="+dauthor+"&parentOn="+parentOn+"&parentDagr="+parentSelect;

  var nospacetext = text.replace(/\s/gi,"_");

  self.port.emit("buttonpressed", nospacetext);

  // Reset all the text fields to blank
  titleInput.value = "";
  tagInput.value = "";
  authorInput.value = "";
});
