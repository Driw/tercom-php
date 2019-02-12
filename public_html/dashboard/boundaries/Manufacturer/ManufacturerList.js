
$(document).ready(function()
{
	ManufacturerList.init();
});

var ManufacturerList = ManufacturerList ||
{
	init: function()
	{
		ManufacturerList.manufacturers = [];
		ManufacturerList.table = $('#table-manufacturers');
		ManufacturerList.tbody = ManufacturerList.table.children('tbody');
		ManufacturerList.datatables = newDataTables(ManufacturerList.table);
		ManufacturerList.loadManufacturers();
	},
	loadManufacturers: function()
	{
		ws.manufacturer_getAll(ManufacturerList.tbody, ManufacturerList.onManufacturersLoaded);
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
		ManufacturerList.datatables.row.add(manufacturerRowData).draw();
	},
	newManufacturerRowData: function(index, manufacturer)
	{
		var btnTemplate = '<button type="button" class="btn {0} btn-sm" onclick="ManufacturerList.{1}({2})" title="{3}">{4}</button>';
		var btnView = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonView', index, 'Ver Fabricante', ICON_VIEW);
		var btnProducts = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonProducts', index, 'Produtos do Fabricante', ICON_PRODUCT);
		var btnRemove = btnTemplate.format(BTN_CLASS_REMOVE, 'onButtonRemove', index, 'Excluir Fabricante', ICON_REMOVE);

		return [
			manufacturer.id,
			manufacturer.fantasyName,
			'<div class="btn-group">{0}{1}{2}</div>'.format(btnView, btnProducts, btnRemove),
		];
	},
	onButtonView: function(index)
	{
		var manufacturer = ManufacturerList.manufacturers[index];
		Util.redirect('manufacturer/view/{0}'.format(manufacturer.id));
	},
	onButtonProducts: function(index)
	{
		var manufacturer = ManufacturerList.manufacturers[index];
		Util.redirect('product/manufacturer/{0}'.format(manufacturer.id));
	},
	onButtonRemove: function(index)
	{
		var manufacturer = ManufacturerList.manufacturers[index];
		Util.redirect('manufacturer/remove/{0}'.format(manufacturer.id));
	},
	onButtonAdd: function()
	{
		Util.redirect('manufacturer/add');
	},
}
