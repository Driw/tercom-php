
$(document).ready(function()
{
	ManufacturerSearch.init();
	ManufacturerSearch.form.submit(function() {
		try { ManufacturerSearch.searchManufacturer(); } catch (e) { alert(e.message); console.error("%O", e); }
		return false;
	});
});

var ManufacturerSearch = ManufacturerSearch ||
{
	init: function()
	{
		ManufacturerSearch.manufacturers = [];
		ManufacturerSearch.form = $('#form-search');
		ManufacturerSearch.table = $('#manufacturers-table');
		ManufacturerSearch.tbody = ManufacturerSearch.table.find('tbody');
		ManufacturerSearch.dataTable = newDataTables(ManufacturerSearch.table);
	},
	searchManufacturer: function()
	{
		var filter = ManufacturerSearch.form[0].filter.value;
		var value = ManufacturerSearch.form[0].value.value;
		ws.manufacturer_search(filter, value, ManufacturerSearch.tbody, ManufacturerSearch.onSearchManufacturer)
	},
	onSearchManufacturer: function(manufacturers)
	{
		ManufacturerSearch.dataTable.clear();
		ManufacturerSearch.manufacturers = manufacturers.elements;
		ManufacturerSearch.manufacturers.forEach(function(manufacturer, index)
		{
			var id = manufacturer.id;
			ManufacturerSearch.manufacturers[manufacturer.id] = manufacturer;

			var openButton = '<button type="button" class="btn btn-primary btn-see" data-id="' +id+ '">Ver</button>';
			ManufacturerSearch.dataTable.row.add([
				manufacturer.id,
				manufacturer.fantasyName,
				'<div class="btn-group">{0}</div>'.format(openButton),
			]).draw();
		});

		$('.btn-see').click(function() {
			var manufacturer = ManufacturerSearch.manufacturers[this.dataset.id];
			window.open('manufacturer/view/{0}'.format(manufacturer.id), '_blank');
		});
	},
}
