
$(document).ready(function()
{
	ManufacturerList.init();
});

var ManufacturerList = ManufacturerList ||
{
	init: function()
	{
		this.manufacturers = [];
		this.table = $('#table-manufacturers');
		this.tbody = this.table.children('tbody');
		this.datatables = newDataTables(this.table);
		this.loadManufacturers();
	},
	loadManufacturers: function()
	{
		ws.manufacturer_getAll(this.tbody, this.onManufacturersLoaded);
	},
	onManufacturersLoaded: function(manufacturers)
	{
		ManufacturerList.manufacturers = manufacturers.elements;
		ManufacturerList.manufacturers.forEach(function(manufacturer, index)
		{
			ManufacturerList.addManufacturerRow(index, manufacturer);
		});
	},
	addManufacturerRow: function(index, manufacturer)
	{
		var manufacturerRowData = ManufacturerList.newManufacturerRowData(index, manufacturer);
		var row = ManufacturerList.datatables.row.add(manufacturerRowData).draw();
	},
	newManufacturerRowData: function(index, manufacturer)
	{
		var id = manufacturer.id;
		var btnView = '<button type="button" class="btn btn-info" data-index="' +index+ '" onclick="ManufacturerList.onButtonView(this)">Ver</button>';
		var btnRemove = '<button type="button" class="btn btn-primary" data-index="' +index+ '" onclick="ManufacturerList.onButtonRemove(this)">Excluir</button>';

		return [
			manufacturer.id,
			manufacturer.fantasyName,
			'<div class="btn-group">' + btnView + btnRemove + '</div>',
		];
	},
	setManufacturer: function(manufacturer)
	{
		var index = ManufacturerList.lookManufacturerIndex(manufacturer.id);
		ManufacturerList.manufacturers[index] = manufacturer;

		return index;
	},
	lookManufacturerIndex: function(idManufacturer)
	{
		for (var i = 0; i < ManufacturerList.manufacturers.length; i++)
			if (ManufacturerList.manufacturers[i].id === idManufacturer)
				return i;

		return -1;
	},
	onButtonView: function(button)
	{
		var index = button.dataset.index;
		var manufacturer = ManufacturerList.manufacturers[index];

		if (manufacturer !== undefined)
			Util.redirect('manufacturer/view/' +manufacturer.id, true);
	},
	onButtonRemove: function(button)
	{
		var index = button.dataset.index;
		var manufacturer = ManufacturerList.manufacturers[index];

		if (manufacturer !== undefined)
			Util.redirect('manufacturers/remove/' +manufacturer.id, true);
	},
}
