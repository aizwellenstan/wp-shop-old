jQuery( document ).ready(
	function ($) {

		if (window.matchMedia( "(max-width: 767px)" ).matches) {
			// The viewport is less than 768 pixels wide
			$( '.whatsapp_class' ).show();
		}

	}
);


function open_share_window(url){
	window.open( url,'newwindow', 'width=500,height=400' );
	return false;
}

function share(shareurl,desc)
{
	url     = 'https://facebook.com/sharer.php?display=popup&quote=' + desc + '&u=' + shareurl;
	options = 'toolbar=0,status=0,resizable=1,width=626,height=436';
	window.open( url,'sharer',options );
}
