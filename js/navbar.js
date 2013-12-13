$(document).ready(function() {
    $("input[name='search-type']").change(function(){
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


function defineSearchBox(searchType){
    searchType = searchType.toLowerCase();
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
        case "reach":
            $("#to-date, #to-text, #from-date").addClass("hidden");
            $("#search-input").val("");
            $("#search-input").attr("type", "text").removeClass("hidden");
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
