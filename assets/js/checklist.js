(function($){
	$(document).ready(function(){
		// Interactivity: checkboxes, save to localStorage, export print (pdf)
		$('.nvb-checklist').each(function(){
			var container = $(this);
			var country = container.data('country') || 'unknown';
			container.find('input[type="checkbox"]').on('change', function(){
				var state = {};
				container.find('input[type="checkbox"]').each(function(i, el){
					state[$(el).attr('name')] = $(el).is(':checked') ? 1 : 0;
				});
				localStorage.setItem('nvb_checklist_' + country, JSON.stringify(state));
			});
			// Restore
			var saved = localStorage.getItem('nvb_checklist_' + country);
			if(saved){
				try{
					var obj = JSON.parse(saved);
					for(var k in obj){
						container.find('input[name="'+k+'"]').prop('checked', obj[k] == 1);
					}
				}catch(e){}
			}
			// Export to PDF (print)
			container.find('.nvb-export-pdf').on('click', function(e){
				e.preventDefault();
				var html = container.prop('outerHTML');
				var w = window.open('', '_blank');
				w.document.write('<html><head><title>Checklist</title>');
				w.document.write('<style>body{font-family:Arial,Helvetica,sans-serif;padding:20px;} .nvb-checklist{max-width:800px;}</style>');
				w.document.write('</head><body>');
				w.document.write(html);
				w.document.write('</body></html>');
				w.document.close();
				w.print();
			});
		});
	});
})(jQuery);
