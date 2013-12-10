var debug = true;
var mochila = null;

function Mochila() {
    this.dagrList = [];
    //TODO Define Server
    this.server = {
    };
    
    this.dagrs = {
        initDagrNavigation: function () {
            $(mochila).on("dagrload", function () {
                console.log("DAGR LOADED");
                $(".dagr-item").click(function () {
                    console.log("DAGR Click");
                    mochila.displayDagrMetaData(this.id);
                });
                
                $(".dagr-item .view-dagr").click(function () {
                    console.log("Dagr Shown");
                    var dagrId = $(this).parent().parent().parent().attr("id")
                    mochila.displayDagrContents(dagrId);
                    
                });
                
                $("#goto-parent").click(function() {
                    var guid = $(this).attr("data-parent");
                    mochila.displayDagrContents(guid);
                });
            });
        },
        
    };
    
    this.metaData = {
        init: function() {
            console.log(mochila.dagrList);
            var parents = mochila.getParentDagrs();
            console.log(parents);
            $("#dagr-parent").select2({
                placeholder: "Parent",
                data: parents,
                createSearchChoice: function(term){
                    return {id: "-1 "+term, value:term, text:term};
                }
            });
            
            $("#dagr-tags").tokenfield();
        }
        
    };
    
    
    this.file = {
        isPdfable : function (file) {
            var pdfableFiles = ["doc", "docx", "xls", "xlsx", "ppt", "pptx", "txt", "rtf", "ps", "eps", "prn", ".wpd", "odt", "odp", "ods", "odg", "odf", "sxw", "sxi", "sxc", "sxd", "stw", "psd", "xps"];
            
            //TODO add check for the no "." in the filename
            var extension = file.slice(file.lastIndexOf(".") + 1);
            return (pdfableFiles.indexOf(extension) > 0);
            
        }
    };
    
    this.popups = {
        url: {
            init: function(){
                var parents = mochila.getParentDagrs()
                $("#url-parent-dagr").select2({
                    placeholder: "Parent",
                    data: parents,
                    createSearchChoice: function(term){
                        return {id: "-1 "+term, value:term, text:term};
                    }
                });
            }
        },
        file: {
            init: function() {
                var parents = mochila.getParentDagrs()
                $("#file-parent-dagr").select2({
                    placeholder: "Parent",
                    data: parents,
                    createSearchChoice: function(term){
                        return {id: "-1 "+term, value:term, text:term};
                    }
                });
            }   
            
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

Mochila.prototype.getParentDagrs = function() {
    var data = $.map(this.dagrList, function(d) {
        if (d.parentGuid == null) {
            return { id:d.guid, text: d.title};
        }});
    return data;
}

Mochila.prototype.displayDagrContents = function(guid) {
    //var children = $.map(this.dagrList, function (d) { if (d.parent == parentGuid){ return d; } });
    var children = [];
    var currDagr = this.getDagr(guid);
    console.log("currDagr");
    for (var x in this.dagrList){
        console.log(this.dagrList[x].parentGuid);
        //Grab children
        if (this.dagrList[x].parentGuid == guid){
            children.push(this.dagrList[x]);
        }
    }
    
    console.log(children);
    console.log(currDagr);
    //Display the contents
    if (currDagr== null || currDagr.type == "parent") {
        var template = $("#dagrItemTemplate").html();
        var html = Mustache.to_html(template, {dagrs: children});
        $("#dagr-contents-container").html(html);
        $("#goto-parent").attr("data-parent", currDagr==null ? null :currDagr.parentGuid);
    } else if(currDagr.type == "url"){
        this.displayUrl(currDagr.content.contentLocation);
    } else {
        this.displayFile(currDagr.content.contentLocation);
    }
    $("#metadata-container").addClass("hidden");
    $("#contents-container").attr("class", "col-md-12");
    $(this).trigger("dagrload");
}

Mochila.prototype.displayFile = function(fileLocation){
    //TODO
    //Grab file and convert to pdf
    //On success display pdf
    window.location.href = "profile.pdf";
    //On failure just use the regular file and not the pdf
}

Mochila.prototype.displayUrl = function (url) {
    window.location.href = url;
}


Mochila.prototype.displayDagrMetaData = function(guid) {
    var dagr = this.getDagr(guid);
    console.log(dagr);
    if(dagr != null){
        $("#metadata-container").removeClass("hidden");
        $("#contents-container").attr("class", "col-md-10")
        $("#dagr-title").val(dagr.title);
        $("#author-metadata").html(dagr.author);
        $("#date-metadata").html(dagr.date);
        $("#size-metadata").html(dagr.size);
        $("#type-metadata").html(dagr.content.contentType);
        $("#dagr-parent").select2("val", dagr.parentGuid);
        $("#dagr-tags").tokenfield("setTokens",dagr.tags);
        
    } else {
        
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
            contentType: "parent",
            contentLocation: null
        },
        tags: ["tag4"]
        
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
            contentLocation: "http://www.google.com"
        },
        tags: ["tag1" ,"tag2", "tag3"]
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
        },
        tags: ["tag1" ,"tag2", "tag3"]
        
    }
    
]



$(document).ready(function(){
    mochila = new Mochila();
    if(debug){
        mochila.setDagrList(MockData);
        mochila.displayDagrContents(null);
        mochila.displayDagrMetaData();
        mochila.dagrs.initDagrNavigation();
        mochila.metaData.init();
        mochila.popups.file.init();
        mochila.popups.url.init();
        $(mochila).trigger("dagrload", ["custom" ,"event"]);
        console.log("Mochila initialized");
    } else { 
        //Pull date from the server and setTheDagrList
    }
});
