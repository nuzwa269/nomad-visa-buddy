(function($){
	$(document).ready(function(){
		// Placeholder global public JS
		$('.nvb-export-csv').on('click', function(e){
			e.preventDefault();
			var countryId = $(this).data('country');
			if(!countryId) return;
			$.post(nvb_public.ajax_url, {
				action: 'nvb_export_checklist_csv',
				country_id: countryId
			}, function(resp){
				if(resp.success && resp.data.csv) {
					var csv = atob(resp.data.csv);
					var blob = new Blob([csv], {type: 'text/csv'});
					var url = URL.createObjectURL(blob);
					var a = document.createElement('a');
					a.href = url;
					a.download = 'nvb_checklist_'+countryId+'.csv';
					document.body.appendChild(a);
					a.click();
					a.remove();
				} else {
					alert('No data to export.');
				}
			});
		});
	});
})(jQuery);
