
$(document).ready(function()
{
	ProductGroupList.init();
});

var ProductGroupList = ProductGroupList ||
{
	init: function()
	{
		ProductGroupList.table = $('#table-product-Groups');
		ProductGroupList.idProductFamily = $('#idProductFamily').val();
		ProductGroupList.selectFamily = $('#families');
		ProductGroupList.selectFamily.change(ProductGroupList.onProductFamilyChange);
		ProductGroupList.tbody = ProductGroupList.table.children('tbody');
		ProductGroupList.datatables = newDataTables(ProductGroupList.table);
		ProductGroupList.loadProductFamilies();
		ProductGroupList.loadProductCategories();
	},
	loadProductFamilies: function()
	{
		ws.productFamily_getAll(ProductGroupList.selectFamily, ProductGroupList.onProductFamiliesLoaded);
	},
	loadProductCategories: function()
	{
		if (ProductGroupList.selectFamily.val() > 0)
			ws.productGroup_getAll(ProductGroupList.idProductFamily, ProductGroupList.tbody, ProductGroupList.onProductCategoriesLoaded);
	},
	onProductFamiliesLoaded: function(productFamilies)
	{
		ProductGroupList.selectFamily.selectpicker();
		ProductGroupList.productFamilies = productFamilies.elements;
		ProductGroupList.productFamilies.forEach(productFamily =>
		{
			var option = Util.createElementOption(productFamily.name, productFamily.id, ProductGroupList.idProductFamily == productFamily.id);
			ProductGroupList.selectFamily.append(option);
		});
		ProductGroupList.selectFamily.selectpicker('refresh');
	},
	onProductCategoriesLoaded: function(productFamily)
	{
		ProductGroupList.datatables.clear().draw();
		ProductGroupList.productCategories = productFamily.productCategories.elements;
		ProductGroupList.productCategories.forEach((productCategory, index) =>
		{
			var rowData = ProductGroupList.newProductCategoryRowData(index, productCategory);
			ProductGroupList.datatables.row.add(rowData).draw();
		});
	},
	onProductFamilyChange: function()
	{
		ProductGroupList.idProductFamily = $(this).find(":selected").val();
		ProductGroupList.loadProductCategories();
	},
	newProductCategoryRowData: function(index, productCategory)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm btn-{0}" onclick="ProductGroupList.{1}({2})">{3}</button>';
		var btnView = btnTemplate.format('primary', 'onButtonView', index, 'Ver');
		var btnGroup = btnTemplate.format('info', 'onButtonSubgrupos', index, 'Subgrupos');
		var btnRemove = btnTemplate.format('danger', 'onButtonRemove', index, 'Remover');

		return [
			productCategory.name,
			'<div class="btn-group">{0}{1}{2}</div>'.format(btnView, btnGroup, btnRemove),
		];
	},
	onButtonAdd: function()
	{
		Util.redirect('productGroup/add/{0}'.format(ProductGroupList.idProductFamily), true);
	},
	onButtonView: function(index)
	{
		var productCategory = ProductGroupList.productCategories[index];

		if (productCategory !== undefined)
			Util.redirect('productGroup/view/{0}'.format(productCategory.id), true);
	},
	onButtonSubgrupos: function(index)
	{
		var productCategory = ProductGroupList.productCategories[index];

		if (productCategory !== undefined)
			Util.redirect('productSubgroup/list/{0}'.format(productCategory.id), true);
	},
	onButtonRemove: function(index)
	{
		var productCategory = ProductGroupList.productCategories[index];

		if (productCategory !== undefined)
			Util.redirect('productGroup/remove/{0}'.format(productCategory.id), true);
	},
}
