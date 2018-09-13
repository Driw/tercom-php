
$(document).ready(function()
{
	ManufacturerList.init();
});

var ManufacturerList = ManufacturerList ||
{
	init: function()
	{
		this.manufacturers = [];
		this.table = $('#table-manufacturer-list');
		this.tbody = this.table.children('tbody');
		this.datatables = newDataTables(this.table);
		this.wsFabricanteGetAll();
	},
	wsFabricanteGetAll: function()
	{
		ws.fabricante_getAll(this.tbody, this.onManufacturerGetAll);
	},
	onFornecedorGetAll: function(result)
	{
		var fornecedores = result.elements;
		fornecedores.forEach(function(manufacturer)
		{
			ManufacturerList.addManufacturerRow(manufacturer);
		});
	},
	addManufacturerRow: function(manufacturer)
	{
		var id = manufacturer.id;
		var index = ManufacturerList.manufacturers.push(manufacturer) - 1;
		var manufacturerRowData = ManufacturerList.newManufacturerRowData(index, manufacturer);
		var row = ManufacturerList.datatables.row.add(manufacturerRowData).draw();

		ManufacturerList.setManufacturerButtons(manufacturer.id);
	},
	newManufacturerRowData: function(index, manufacturer)
	{
		var id = manufacturer.id;
		var btnView = '<button type="button" class="btn btn-info" data-index="' +index+ '" id="btn-manufacturer-view-' +id+ '">Ver</button>';
		var btnActive = '<button type="button" class="btn btn-primary" data-index="' +index+ '" id="btn-manufacturer-active-' +id+ '">Ativar</button>';
		var btnDesactive = '<button type="button" class="btn btn-secondary" data-index="' +index+ '" id="btn-manufacturer-inactive-' +id+ '">Desativar</button>';
		var btnViewPrices = '<button type="button" class="btn btn-info" data-index="' +index+ '" id="btn-manufacturer-prices-' +id+ '">Preços</button>';

		return [
			manufacturer.id,
			manufacturer.fantasyName,
			'<div class="btn-group" id="manufacturer-' +manufacturer.id+ '">' + btnView + btnViewPrices + (manufacturer.inactive ? btnActive : btnDesactive) + '</div>',
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
	setManufacturerButtons: function(idManufacturer)
	{
		$('#btn-manufacturer-view-' +idManufacturer).click(ManufacturerList.onClickBtnView);
		$('#btn-manufacturer-active-' +idManufacturer).click(ManufacturerList.onClickBtnActive);
		$('#btn-manufacturer-inactive-' +idManufacturer).click(ManufacturerList.onClickBtnInactive);
		$('#btn-manufacturer-prices-' +idManufacturer).click(ManufacturerList.onClickBtnPrices);
	},
	onClickBtnView: function()
	{
		var index = this.dataset.index;
		var manufacturer = ManufacturerList.manufacturers[index];
		Util.redirect('manufacturer/view/' +manufacturer.id, true);
	},
	onClickBtnPrices: function()
	{
		var index = this.dataset.index;
		var manufacturer = ManufacturerList.manufacturers[index];
		Util.redirect('manufacturerValues/view/' +manufacturer.id, true);
	},
	onClickBtnActive: function()
	{
		var index = this.dataset.index;
		var manufacturer = ManufacturerList.manufacturers[index];
		var tr = $(this).parents('tr');
		var td = $(this).parents('td');

		ws.manufacturer_setActive(manufacturer.id, tr, ManufacturerList.onManufacturerSetInactive);
	},
	onClickBtnInactive: function()
	{
		var index = this.dataset.index;
		var manufacturer = ManufacturerList.manufacturers[index];
		var tr = $(this).parents('tr');
		var td = $(this).parents('td');

		ws.manufacturer_setInactive(manufacturer.id, tr, ManufacturerList.onManufacturerSetInactive);
	},
	onManufacturerSetInactive: function(manufacturer)
	{
		var tr = $('#manufacturer-' +manufacturer.id).parents('tr');
		var index = ManufacturerList.setManufacturer(manufacturer);
		var manufacturerRowData = ManufacturerList.newManufacturerRowData(index, manufacturer);
		var row = ManufacturerList.datatables.row(tr).data(manufacturerRowData);
		var id = manufacturer.id;
		ManufacturerList.setManufacturerButtons(manufacturer.id);
	},
}
