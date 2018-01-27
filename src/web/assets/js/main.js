$( document ).ready(function() {
    console.log("Yeah, I work! Document is ready.");  // custom JS will be here

	function log( message, log_type ) {
		var log_div = '#log_' + log_type;
		$( "<div class='message'>" ).text( message ).prependTo( log_div );
		$( "#log" ).scrollTop( 0 );
	}

	var path = $("#modal_input_assignOrganizer").data("autocomplete-path")
	$( "#modal_input_assignOrganizer" ).autocomplete({
		source: path,
		minLength: 2,
		appendTo: "#log_assignOrganizer",
		position: { my : "right tops", at: "right bottom" },
		response: function( event, ui ) {
			$('.message').remove()
			if(ui.content.length === 0) {
				log( "No results found", 'assignOrganizer' )
			}
		}
	});

	var path = $("#modal_input_assignLanguage").data("autocomplete-path")
	$( "#modal_input_assignLanguage" ).autocomplete({
		source: path,
		minLength: 2,
		appendTo: "#log_assignLanguage",
		position: { my : "right tops", at: "right bottom" },
		response: function( event, ui ) {
			$('.message').remove()
			if(ui.content.length === 0) {
				log( "No results found", 'assignLanguage' )
			}
		}
	});
});
