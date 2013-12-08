var debug = true;
var mochila = null;
function Mochila() {
    this.dagrList = [];
    //TODO Define Server
    this.server = {
    };
    
    this.dagrs = {
        initDagrNavigation: function () {
            $(".dagr-item").click(function () {
                console.log(this.id);
                mochila.displayDagr(this.id);
            });
        }
    };
}



Mochila.prototype.refreshDagrList = function () {
    //TODO retrieve dagr list from server
    $(this).trigger("dagrlistupdate", dagrList);
}

Mochila.prototype.getDagr = function(guid) {
    for(var curr = 0; curr < this.dagrList.length; curr++){
        var currDagr = this.dagrList[curr];
        console.log(currDagr.guid == guid);
        if(currDagr.guid == guid){
            return currDagr;
        }
    }
}

Mochila.prototype.displayDagrContents = function(parentGuid) {
    //var children = $.map(this.dagrList, function (d) { if (d.parent == parentGuid){ return d; } });
    var children = []
    var currDagr = null;
    for (var x in this.dagrList){
        console.log(this.dagrList[x].parentGuid);
        //Grab children
        if (this.dagrList[x].parentGuid == parentGuid){
            children.push(this.dagrList[x]);
        }
    }
    
    //Display the contents
    if (children.length > 0) {
        var template = $("#dagrItemTemplate").html();
        var html = Mustache.to_html(template, {dagrs: children});
        $("#dagr-contents-container").html(html);
    } else {
        //TODO display the view of the file
    }
}

Mochila.prototype.displayDagrMetaData = function(guid) {
    var dagr = this.getDagr(guid);
    console.log(dagr);
    if(dagr != null){
        $("#metadata-container").removeClass("hidden");
        $("#contents-container").attr("class", "col-md-10")
        var template = $("#metadata-form-template").html();
        var html = Mustache.to_html(template, dagr);
        $("#metadata-container").html(html);
    } else {
        $("#metadata-container").addClass("hidden");
        $("#contents-container").attr("class", "col-md-12")
    }
    
    
}

Mochila.prototype.displayDagr = function(guid){
    this.displayDagrContents(guid);
    this.displayDagrMetaData(guid);
}


Mochila.prototype.setDagrList = function(dagrList) {
    this.dagrList = dagrList;
    $(this).trigger("dagrlistupdate", dagrList);
}

var MockData =  [
    {
        guid: "1",
        title: "MY Dagr",
        author: "jimmy",
        date: "May 10, 2013",
        parent: null,
        size: "200mb",
        type: "parent",
        content:  {
            contentType: "url",
            contentLocation: "www.google.com"
        }
        
    },
    
    {
        guid: "2",
        title: "Google",
        author: "John",
        date: "June 10, 2013",
        parentGuid: "1",
        size: "100mb",
        children: null,
        type: "url",
        content: {
            contentType: "url",
            contentLocation: "www.google.com"
        }
    },
    
    {
        guid: "3",
        title: "My Example pdf",
        author: "Tim",
        date: "December, 10 2013",
        parentGuid: "1" ,
        size: "100mb",
        children: null,
        type: "file",
        content: {
            contentType: "pdf",
            contentLocation: "example.pdf"
        }
        
    }
    
]



$(document).ready(function(){
    mochila = new Mochila();
    if(debug){
        mochila.setDagrList(MockData);
        mochila.displayDagrContents(null);
        mochila.displayDagrMetaData();
        mochila.dagrs.initDagrNavigation(null);
        console.log("Mochila initialized");
    } else { 
        //Pull date from the server and setTheDagrList
    }
});
