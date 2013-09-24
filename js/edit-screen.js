
jQuery(document).ready(function($){

	$('#available-blocks a').click(function(e){
		e.preventDefault();
		var type = $(this).attr('data-type');
		var section = $( '<section data-type="' + type + '"><div class="handle"></div></section>' );
		section.appendTo( $(this).closest('ul').siblings('article') );
		section.trigger( 'newContentBlock', { type : type } );
	});

	$('#content-blocks-wrap').on( 'newContentBlock', 'article > section', function( event, data ) {
		$(this).append( '<h1>I am <code>' + data.type + '</code>!</h1>' );
		// Do whatever we like based on the type here.
		// This will also make it easy for plugins to add new content
		// block types, and catch them with their own javascript.
	});

	$('#content-blocks-wrap article').sortable({
		handle : '.handle'
	});

});
