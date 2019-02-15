
$(document).ready(function()
{
	ProductTypeList.init();
});

var ProductTypeList = ProductTypeList ||
{
	init: function()
	{
		ProductTypeList.table = $('#table-product-types');
		ProductTypeList.tbody = ProductTypeList.table.children('tbody');
		ProductTypeList.datatables = newDataTables(ProductTypeList.table);
		ProductTypeList.loadProductTypes();
	},
	loadProductTypes: function()
	{
		ws.productType_getAll(ProductTypeList.tbody, ProductTypeList.onProductTypesLoaded);
	},
	onProductTypesLoaded: function(productTypes)
	{
		ProductTypeList.productTypes = productTypes.elements;
		ProductTypeList.productTypes.forEach(function(productType, index)
		{
			ProductTypeList.addProductTypeRow(index, productType);
		});
	},
	addProductTypeRow: function(index, productType)
	{
		var rowData = ProductTypeList.newProductTypeRowData(index, productType);
		ProductTypeList.datatables.row.add(rowData).draw();
	},
	newProductTypeRowData: function(index, productType)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm {0}" onclick="ProductTypeList.{1}({2})" title="{3}">{4}</button>';
		var btnView = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonView', index, 'Ver Tipo de Produto', ICON_VIEW);
		var btnRemove = btnTemplate.format(BTN_CLASS_REMOVE, 'onButtonRemove', index, 'Excluir Tipo de Produto', ICON_REMOVE);

		return [
			productType.name,
			'<div class="btn-group">{0}{1}</div>'.format(btnView, btnRemove),
		];
	},
	onButtonView: function(index)
	{
		var productType = ProductTypeList.productTypes[index];
		Util.redirect('productType/view/' +productType.id);
	},
	onButtonRemove: function(index)
	{
		var productType = ProductTypeList.productTypes[index];
		Util.redirect('productType/remove/' +productType.id);
	},
	onButtonAdd: function()
	{
		Util.redirect('productType/add');
	},
}
