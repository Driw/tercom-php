
$(document).ready(function()
{
	ProductUnitList.init();
});

var ProductUnitList = ProductUnitList ||
{
	init: function()
	{
		ProductUnitList.table = $('#table-product-units');
		ProductUnitList.tbody = ProductUnitList.table.children('tbody');
		ProductUnitList.datatables = newDataTables(ProductUnitList.table);
		ProductUnitList.loadProductUnits();
	},
	loadProductUnits: function()
	{
		ws.productUnit_getAll(ProductUnitList.tbody, ProductUnitList.onProductUnitsLoaded);
	},
	onProductUnitsLoaded: function(productUnits)
	{
		ProductUnitList.productUnits = productUnits.elements;
		ProductUnitList.productUnits.forEach(function(productUnit, index)
		{
			ProductUnitList.addProductUnitRow(index, productUnit);
		});
	},
	addProductUnitRow: function(index, productUnit)
	{
		var rowData = ProductUnitList.newProductUnitRowData(index, productUnit);
		ProductUnitList.datatables.row.add(rowData).draw();
	},
	newProductUnitRowData: function(index, productUnit)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm {0}" onclick="ProductUnitList.{1}({2})" title="{3}">{4}</button>';
		var btnView = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonView', index, 'Ver Embalagem de Produto', ICON_VIEW);
		var btnRemove = btnTemplate.format(BTN_CLASS_REMOVE, 'onButtonRemove', index, 'Excluir Embalagem de Produto', ICON_REMOVE);

		return [
			productUnit.shortName,
			productUnit.name,
			'<div class="btn-group">{0}{1}</div>'.format(btnView, btnRemove),
		];
	},
	onButtonView: function(index)
	{
		var productUnit = ProductUnitList.productUnits[index];
		Util.redirect('productUnit/view/{0}'.format(productUnit.id));
	},
	onButtonRemove: function(index)
	{
		var productUnit = ProductUnitList.productUnits[index];
		Util.redirect('productUnit/remove/{0}'.format(productUnit.id));
	},
	onButtonAdd: function(index)
	{
		Util.redirect('productUnit/add');
	},
}
