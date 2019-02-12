
$(document).ready(function()
{
	ManufacturerSearch.init();
});

var ManufacturerSearch = ManufacturerSearch ||
{
	init: function()
	{
		ManufacturerSearch.manufacturers = [];
		ManufacturerSearch.form = $('#form-search');
		ManufacturerSearch.table = $('#manufacturers-table');
		ManufacturerSearch.tbody = ManufacturerSearch.table.find('tbody');
		ManufacturerSearch.datatables = newDataTables(ManufacturerSearch.table);
		ManufacturerSearch.form.submit(function()
		{
			try {
				ManufacturerSearch.searchManufacturer();
			} catch (e) {
				ManufacturerSearch.showError(e.stack);
			}
			return false;
		});
	},
	searchManufacturer: function()
	{
		var filter = ManufacturerSearch.form[0].filter.value;
		var value = ManufacturerSearch.form[0].value.value;
		ws.manufacturer_search(filter, value, ManufacturerSearch.tbody, ManufacturerSearch.onSearchManufacturer)
	},
	onSearchManufacturer: function(manufacturers)
	{
		ManufacturerSearch.datatables.clear().draw();
		ManufacturerSearch.manufacturers = manufacturers.elements;
		ManufacturerSearch.manufacturers.forEach(function(manufacturer, index)
		{
			ManufacturerSearch.addManufacturerRow(index, manufacturer);
		});
	},
	addManufacturerRow: function(index, manufacturer)
	{
		var manufacturerRowData = ManufacturerSearch.newManufacturerRowData(index, manufacturer);
		ManufacturerSearch.datatables.row.add(manufacturerRowData).draw();
	},
	newManufacturerRowData: function(index, manufacturer)
	{
		var btnTemplate = '<button type="button" class="btn {0} btn-sm" onclick="ManufacturerSearch.{1}({2})" title="{3}">{4}</button>';
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
}
