$(document).ready(function() {
    console.log("is this being called");
    $("input[name='search-type']").change(function(){
        console.log("is this working");
        var searchType = $(this).next("label").text();
        var caret = $("<span/>").addClass("caret");
        defineSearchBox(searchType);
        $("#search-type-dropdown").text(searchType).append(caret);
    });
    
    $("#from-date").on("change.bfhdatepicker", function(e){
        var endDate = $(this).val();
        $("#to-date").attr("data-min", endDate);
    });
    
    
    var $contents = $("#add-dagr-popover")
    
    
    $("#add-dagr").popover({
        title: "Add a DAGR",
        html: true,
        content: $contents.html(),
        placement: "bottom"
        
    });
    
    
    
});


function reachInitialization(){
    var dagr_list = null;
    console.log("reach init");
    $.ajax("getAllGuidsAndTitles.php", {
        type: "GET",
        dataType: "json",
        error: function (){
            console.log("something is wrong");
        },
        success: function(data, message, jqXHR){
            dagr_list = data;
            console.log(data);
            console.log(message);
            $("#search-input").select2({
                placeholder: "Select a DAGR",
                data: $.map(dagr_list, function(d) {
                    return {
                        id: d.guid,
                        text: d.title + "  -  " + d.guid 
                    }
                    
                })
            });
        }
    });
    console.log(dagr_list);
    
}

function defineSearchBox(searchType){
    searchType = searchType.toLowerCase();
    console.log("Defining search");
    switch(searchType){
        case "orphan":
        case "sterile":
            $("#to-date, #to-text, #from-date").addClass("hidden");
            $("#search-input").val("empty");
            $("#search-input").attr("type", "text").addClass("hidden");
            break;
        case "author":
        case "file type":
        case "keyword":
        case "tag":
            $("#to-date, #to-text, #from-date").addClass("hidden");
            $("#search-input").select2("destroy");
            $("#search-input").val("");
            $("#search-input").attr("type", "text").removeClass("hidden");
            break;
        case "reach":
            $("#to-date, #to-text, #from-date").addClass("hidden");
            reachInitialization();
            
            break;
        case "size":
            $("#to-date, #to-text, #from-date").addClass("hidden");
            $("#search-input")
            .attr("type", "number")
            .attr("min", '0')
            .removeClass("hidden");
            break;
        case "time range":
            $("#to-date, #to-text, #from-date").removeClass("hidden");
            $("#to-date, #from-date").removeClass("col-md-12").addClass("col-md-5");
            $("#search-input").addClass("hidden");
            break;
        case "date":
            $("#to-date, #to-text").addClass("hidden");
            $("#from-date").removeClass("hidden col-md-5").addClass("col-md-12");
            $("#search-input").addClass("hidden");
            break;
            
            
    }   
}
