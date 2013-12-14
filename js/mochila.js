var debug = false;
var mochila = null;

function Mochila(dagr_list, currDagr) {
    this.dagrList = dagr_list;
    this.currDagr = currDagr;
    this.parentList = null;
    this.server = {
    };
    
    this.dagrs = {
        initDagrNavigation: function (mochila) {
            $(mochila).on("dagrload", function() {
                $(".dagr-item .dagr-title").click(function () {
                    console.log("DAGR Click");
                    mochila.displayDagrContents($(this).parent().attr("id"));
                });
                
                $(".dagr-item .view-dagr").click(function () {
                    console.log("Dagr Shown");
                    var dagrId = $(this).parent().parent().parent().attr("id");
                    mochila.displayDagrMetaData(dagrId);
                    
                });
                
                $("#goto-parent").click(function() {
                    var guid = mochila.currDagr.parentGuid;
                    console.log(mochila.currDagr.parentGuid);
                    mochila.displayDagrContents(guid);
                });
                
                $("#delete-dagr-modal").on("show.bs.modal", function(e){
                    var guid = $(e.relatedTarget).parent().parent().parent().attr("id")
                    $("#delete-dagr-guid").val(guid);
                    $.ajax("getAffectedDagrs.php", {
                        type: "GET",
                        dataType: "json",
                        data: { guid: guid },
                        error: function () {
                            console.error("Error getting affected dagrs");
                        },
                        success: function (data, textStatus, jqXHR) {
                            if(data.length > 1){
                                var template = $("#affected-dagr-template").html();
                                var html = Mustache.to_html(template, {children: data});
                                $("#affected-dagrs-container").html(html);
                                $("#recursive-selection").removeClass("hidden");
                            } else {
                                $("#affected-dagrs-container").html("<h2>Are you sure you want to delete " + data[0].title + "?</h2>");
                                $("#recursive-selection").addClass("hidden");
                            }
                        }
                        
                    });
                    
                });
                
                $("#delete-dagr-submit").click(function() {
                    var guid = $("#delete-dagr-guid").val();
                    var recursive = $("#delete-dagr-modal input[name='delete-type']:checked").val() == "true";
                    mochila.deleteDagr(guid, recursive);
                });
                
                $("#search-button").click(function(){
                    mochila.search();
                    
                });
                
                $("#search-input").keyup(function(e){
                    if(e.keyCode == 13){
                        mochila.search();
                    }
                    
                });
                
                $("#update-metadata").click(function(e){
                    mochila.updateDagr();
                });
                
                $("#reset-metadata").click(function(e){
                    var guid = $("#guid-metadata").val();
                    mochila.displayDagrMetaData(guid);
                });
                
                
                
                
                
            });
            
        }
    };
    
    this.metaData = {
        init: function(parentList) {
            console.log("init_metadata");
            console.log(parentList);
            var parents = $.map(parentList, function (d) {return {id: d.guid, text:d.title};});
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
            init: function(parentList){
                $("#url-parent-dagr").select2({
                    placeholder: "Parent",
                    data: parentList,
                    createSearchChoice: function(term){
                        return {id: "-1 "+term, value:term, text:term};
                    }
                });
            }
        },
        file: {
            init: function(parentList) {
                $("#file-parent-dagr").select2({
                    placeholder: "Parent",
                    data: parentList,
                    createSearchChoice: function(term){
                        return {id: "-1 "+term, value:term, text:term};
                    }
                });
            }   
            
        },
        init: function(mochila){
            var parents = $.map(mochila.parentList, function (d) {return {id: d.guid, text:d.title};});
            this.file.init(parents);
            this.url.init(parents);
            
        }
        
        
    };
    
}


Mochila.prototype.refreshDagr = function (guid) {
    var currMochila = this;
    console.log("REFRESH DAGR");
    $.ajax("getDagr.php", {
        type: "GET",
        dataType: "json",
        data: {
            guid: guid
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("error");
        },
        
        success: function(data, textStatus, jqXHR){
            console.log(data);
            alert("look at console");
            currMochila.dagrList = data["children"];
            currMochila.currDagr = data["parent"];
            currMochila.refresh();
        }
    });
    
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
    var currMochila = this;
    $.ajax("getParents.php", {
        type: "GET",
        dataType: "json",
        error: function(jqXHR, textStatus, errorThrown){
            console.log(textStatus);
        },
        success: function(data, textStatus, jqXHR){
            console.info("getParentDagrs");
            console.log(data);
            data.push({title: "No parent", guid:"-1"})
            currMochila.parentList = data;
            console.log("trigger to send");
            $(currMochila).trigger("parentsLoaded", [this.parentList]);
            
        }
    });
    
}

Mochila.prototype.displayDagrContents = function(guid) {
    //var children = $.map(this.dagrList, function (d) { if (d.parent == parentGuid){ return d; } });
    //Display the contents
    
    var dagr = null;
    if(this.currDagr != null && guid == this.currDagr.guid){
        dagr = this.currDagr;
    } else {
        dagr = this.getDagr(guid);
    }
    
    
    if (dagr == null){
        this.displayParentDagr(guid);
    } else if (dagr.type == "parent") {
        this.displayParentDagr(dagr.guid);
    } else if(dagr.type == "url"){
        this.displayUrl(dagr.location);
    } else {
        this.displayFile(dagr.location);
    }
    
}

Mochila.prototype.displayParentDagr = function(guid) {
    if(guid != null){
        window.location.href = "getDagr.php?guid="+guid;
    } else {
        window.location.href = "getDagr.php";
    }
}

Mochila.prototype.displayFile = function(fileLocation){
    console.log(fileLocation);
    $.ajax("viewDagr.php", {
        type: "POST",
        data:{location: fileLocation},
        success: function(data, message, jqHXR){
            console.log(data);
            window.location.href = data;
        }
        
    });
    //On success display pdf
    //window.location.href = "profile.pdf";
    //On failure just use the regular file and not the pdf
}

Mochila.prototype.displayUrl = function (url) {
    window.location.href = url;
}


Mochila.prototype.displayDagrMetaData = function(guid) {
    var dagr = this.getDagr(guid);
    console.log(dagr);
    if(dagr != null){
        $("#metadata-container").slideDown();
        $("#dagr-contents-container").removeClass("container");
        $("#guid-metadata").val(guid);
        $("#dagr-title").val(dagr.title);
        $("#author-metadata").val(dagr.author);
        $("#date-metadata").html(dagr.date);
        $("#size-metadata").html(dagr.size);
        $("#type-metadata").html(dagr.file_type);
        $("#contents-container").attr("class", "col-md-10")
        $("#contents-container").addClass("col-md-offset-2");
        if(dagr.parentGuid != null){
            $("#dagr-parent").select2("val", dagr.parentGuid);
        } else {
            $("#dagr-parent").select2("val", "-1");
        }
        $("#dagr-tags").tokenfield("setTokens",dagr.tags);
        
    }
    
    
}

Mochila.prototype.displayDagr = function(){
    var currMochila = this;
    console.log(this.dagrList);
    if(this.dagrList != null && this.dagrList.length > 0){
        var template = $("#dagrItemTemplate").html();
        var html = Mustache.to_html(template, {dagrs: this.dagrList});
        $("#dagr-contents-container").html(html);
        $("#goto-parent").attr("data-parent", this.currDagr==null ? null :this.currDagr.parentGuid);
    } else {
        
        if(this.currDagr.guid == null){
            $("#dagr-contents-container").load("html/EmptyDagr.html #no-dagrs");
            
        } else { 
            $("#dagr-contents-container").load("html/EmptyDagr.html #sterile-dagr", function(){
                $("#return-up").click(function(){
                    var guid = currMochila.currDagr.parentGuid;
                    currMochila.displayDagrContents(guid);
                    console.log(mochila);
                });
                
            });
        }
    }
    $("#metadata-container").hide();
    $("#contents-container").attr("class", "col-md-12");
}


Mochila.prototype.setDagrList = function(dagrList) {
    this.dagrList = dagrList;
    $(this).trigger("dagrlistupdate", dagrList);
}


Mochila.prototype.deleteDagr = function(guid, recursive){
    console.log(guid);
    var currMochila = this;
    console.log("Mochila.deleteDagr()");
    $.ajax("dagrDelete.php",
           {
               type: "POST",
               data: {
                   guid: guid,
                   recursive: recursive
               },
               error: function (jqXHR, textStatus, errorThrown) {
                   alert("error");
                   //this.display
               },
               success: function(data, textStatus, jqXHR){
                   console.log(data);
                   currMochila.displayDagrContents(currMochila.currDagr.guid);
                   
               }
               
               
           });
}

Mochila.prototype.updateDagr = function(){
    console.log("updateDagr");
    var currMochila = this;
    var guid = $("#guid-metadata").val();
    var title = $("#dagr-title").val();
    var author = $("#author-metadata").val();
    var parent = $("#dagr-parent").val();
    var tags = $("#dagr-tags").val().split(", ");
    console.log(tags);
    var data = {
        guid: guid,
        title: title,
        author: author,
        parent: parent,
        tags: tags
    };
    
    
    $.ajax("updateDagr.php", {
        type: "POST",
        data: data,
        error: function () {
            console.log("updateDagr.php Error");
            
        },
        success: function(data, message, jqXHR){
            console.log("Dagr Updated");
            currMochila.displayDagrContents(currMochila.currDagr.guid);
        }
        
    });
    
    
}

Mochila.prototype.search = function (){
    var currMochila = this;
    var type = $("#search-dropdown input[name='search-type']:checked").val();
    if (type == "date"){
        var term = $("#from-date").val();
        window.location.href = "searchDagrs.php?type=" + encodeURIComponent(type) +"&term="+ encodeURIComponent(term);
    }else if (type == "time") {
        var start = $("#from-date").val();
        var end = $("#to-date").val();
        if(start < end){
            
            window.location.href = "searchDagrs.php?type=" + encodeURIComponent(type) +"&start="+ encodeURIComponent(start) + "&end=" + encodeURIComponent(end);
            
        }
    }else{
        var term = $("#search-input").val();
        if(term != "" && term != null){
            window.location.href = "searchDagrs.php?type=" + encodeURIComponent(type) +"&term="+ encodeURIComponent(term);
        }
    }
}

Mochila.prototype.init = function() {
    this.displayDagr();
    this.dagrs.initDagrNavigation(this);
    $(this).on("parentsLoaded", function() {
        console.log("parentsLoaded");
        this.metaData.init(this.parentList);
        this.popups.init(this);
    });
    this.getParentDagrs();
    $(this).trigger("dagrload");
    
}

Mochila.prototype.refresh = function (){
    this.init();
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
    mochila = new Mochila($dagr_info["children"],$dagr_info["parent"]);
    mochila.init();
    
});
