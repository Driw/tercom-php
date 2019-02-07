
$(document).ready(function()
{
	ProductFamilyList.init();
});

var ProductFamilyList = ProductFamilyList ||
{
	init: function()
	{
		ProductFamilyList.selectType = $('#idProductCategoryType');
		ProductFamilyList.table = $('#table-product-families');
		ProductFamilyList.tbody = ProductFamilyList.table.children('tbody');
		ProductFamilyList.datatables = newDataTables(ProductFamilyList.table);
		ProductFamilyList.loadProductCategories();
	},
	loadProductCategories: function()
	{
		ws.productFamily_getAll(ProductFamilyList.tbody, ProductFamilyList.onProductCategoriesLoaded);
	},
	onProductCategoriesLoaded: function(productCategories)
	{
		ProductFamilyList.productCategories = productCategories.elements;
		ProductFamilyList.productCategories.forEach(function(productCategory, index)
		{
			var rowData = ProductFamilyList.newProductCategoryRowData(index, productCategory);
			ProductFamilyList.datatables.row.add(rowData).draw();
		});
	},
	newProductCategoryRowData: function(index, productCategory)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm btn-{0}" onclick="ProductFamilyList.{1}({2})">{3}</button>';
		var btnView = btnTemplate.format('primary', 'onButtonView', index, 'Ver');
		var btnGroup = btnTemplate.format('info', 'onButtonGroups', index, 'Grupos');
		var btnRemove = btnTemplate.format('danger', 'onButtonRemove', index, 'Remover');

		return [
			productCategory.name,
			'<div class="btn-group">{0}{1}{2}</div>'.format(btnView, btnGroup, btnRemove),
		];
	},
	onButtonView: function(index)
	{
		var productCategory = ProductFamilyList.productCategories[index];

		if (productCategory !== undefined)
			Util.redirect('productFamily/view/{0}'.format(productCategory.id), true);
	},
	onButtonGroups: function(index)
	{
		var productCategory = ProductFamilyList.productCategories[index];

		if (productCategory !== undefined)
			Util.redirect('productGroup/list/{0}'.format(productCategory.id), true);
	},
	onButtonRemove: function(index)
	{
		var productCategory = ProductFamilyList.productCategories[index];

		if (productCategory !== undefined)
			Util.redirect('productFamily/remove/{0}'.format(productCategory.id), true);
	},
}
