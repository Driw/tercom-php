
$(document).ready(function()
{
	ProductTypeList.init();
});

var ProductTypeList = ProductTypeList ||
{
	init: function()
	{
		this.productTypes = [];
		this.table = $('#table-product-types');
		this.tbody = this.table.children('tbody');
		this.datatables = newDataTables(this.table);
		this.loadProductTypes();
	},
	loadProductTypes: function()
	{
		ws.productType_getAll(this.tbody, this.onProductTypesLoaded);
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
		var row = ProductTypeList.datatables.row.add(rowData).draw();
	},
	newProductTypeRowData: function(index, productType)
	{
		var id = productType.id;
		var btnView = '<button type="button" class="btn btn-info" data-index="' +index+ '" onclick="ProductTypeList.onButtonView(this)">Ver</button>';
		var btnRemove = '<button type="button" class="btn btn-primary" data-index="' +index+ '" onclick="ProductTypeList.onButtonRemove(this)">Excluir</button>';

		return [
			productType.name,
			'<div class="btn-group">' + btnView + btnRemove + '</div>',
		];
	},
	onButtonView: function(button)
	{
		var index = button.dataset.index;
		var productType = ProductTypeList.productTypes[index];

		if (productType !== undefined)
			Util.redirect('productType/view/' +productType.id, true);
	},
	onButtonRemove: function(button)
	{
		var index = button.dataset.index;
		var productType = ProductTypeList.productTypes[index];

		if (productType !== undefined)
			Util.redirect('productType/remove/' +productType.id, true);
	},
}
