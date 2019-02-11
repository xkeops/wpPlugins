jQuery(document).ready(function($){

	
	function loadShadowDom(){
		var div = document.querySelector( "#shadowDom" );
		var template = document.querySelector( "template" );

		var sh;
		if ( 'attachShadow' in div )
		  sh = div.attachShadow( { mode: "closed" } );  //Shadow DOM v1
		else
		  sh = div.createShadowRoot();                 //Shadow DOM v0 fallback

		sh.appendChild( template.content.cloneNode( true ) );
	}
	loadShadowDom();
});