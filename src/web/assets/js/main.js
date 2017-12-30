console.log("Yeah, I work!");  // custom JS will be here



function log( message ) {
	$( "<div class='message'>" ).text( message ).prependTo( "#log" );
	$( "#log" ).scrollTop( 0 );
}

var path = $("#modal_input").data("autocomplete-path")
$( "#modal_input" ).autocomplete({
	source: path,
	minLength: 2,
	appendTo: "#log",
	position: { my : "right tops", at: "right bottom" },
	response: function( event, ui ) {
		if(ui.content.length === 0) {
			log( "No results found" )
		} else {
			$('.message').remove()
		}
	}
});
