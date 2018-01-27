$( document ).ready(function() {
    console.log("Yeah, I work! Document is ready.");  // custom JS will be here

    function log( message, log_type ) {
        var log_div = '#log_' + log_type;
        $( "<div class='message'>" ).text( message ).prependTo( log_div );
        $( "#log_assign_language" ).scrollTop( 0 );
    }

    function log( message, log_type ) {
        var log_div = '#log_' + log_type;
        $( "<div class='message'>" ).text( message ).prependTo( log_div );
        $( "#log_assign_organizer" ).scrollTop( 0 );
}

    var path = $("#modal_input_assign_organizer").data("autocomplete-path")
    $( "#modal_input_assign_organizer" ).autocomplete({
        source: path,
        minLength: 1,
        appendTo: "#log_assign_organizer",
        position: { my : "right tops", at: "right bottom" },
        response: function( event, ui ) {
            $('.message').remove()
            if(ui.content.length === 0) {
                log( "No results found", 'assign_organizer' )
            }
        }
    });

    var path = $("#modal_input_assign_language").data("autocomplete-path")
    $( "#modal_input_assign_language" ).autocomplete({
        source: path,
        minLength: 1,
        appendTo: "#log_assign_language",
        position: { my : "right tops", at: "right bottom" },
        response: function( event, ui ) {
            $('.message').remove()
            if(ui.content.length === 0) {
                log( "No results found", 'assign_language' )
            }
        }
    });
});
