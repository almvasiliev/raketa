$().ajaxStop($.unblockUI());
	$(document).ready(function () {

		$('.load, #fill_table').bind('click',function () {
			$(document).ajaxStart($.blockUI({message: "Loading, please wait...", css: {backgroundColor: '#818181', color: '#fff'}})).ajaxStop($.unblockUI); 
			
			var id=$(this).attr("id");
			var date=$(this).attr("id");

			if (id == 'sort_by_ID_desc')
				{
					$('#sort_by_ID_desc').hide();
					$('#sort_by_ID_asc').show();
					id = 'sort_by_ID_desc';
					$('#sort_by_id').val('sort_by_ID_desc');
				}
			else
				{
					if (id == 'sort_by_ID_asc')
						{
							$('#sort_by_ID_asc').hide();
							$('#sort_by_ID_desc').show();
							id = 'sort_by_ID_asc';
							$('#sort_by_id').val('sort_by_ID_asc');
						}
					else
						{
							id = $('#sort_by_id').val();
						}
				}

			if (date == 'sort_by_date_desc')
				{
					$('#sort_by_date_desc').hide();
					$('#sort_by_date_asc').show();
					date = 'sort_by_date_desc';
					$('#sort_by_date').val('sort_by_date_desc');
				}
			else
				{
					if (date == 'sort_by_date_asc')
						{
							$('#sort_by_date_asc').hide();
							$('#sort_by_date_desc').show();
							date = 'sort_by_date_asc';
							$('#sort_by_date').val('sort_by_date_asc');
						}
					else
						{
							date = $('#sort_by_date').val();
						}
				}

			var ds = $('#ds').val();
			var df = $('#df').val();
			$.post('./load.php',{ds: ds, df: df, id: id, date: date},function(data) {
				$('#loader').html(data);
			});
		});

		$('#fill_table').click(function () {
			$.post('./fill_table.php',{},function(data) {
				$('#loader').html(data);
			});
		});
	});