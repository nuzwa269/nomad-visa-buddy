(function($){
	$(document).ready(function(){
		// Basic JS for admin interactions (placeholder)
		$('.nvb-confirm-delete').on('click', function(e){
			if(!confirm('Are you sure you want to delete this item?')) {
				e.preventDefault();
			}
		});
	});
})(jQuery);
