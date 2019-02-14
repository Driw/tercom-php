
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
		if (ProductGroupList.idProductFamily > 0)
			ws.productFamily_getCategories(ProductGroupList.idProductFamily, ProductGroupList.tbody, ProductGroupList.onProductFamilyLoaded);
		else
			ws.productGroup_getAll(ProductGroupList.tbody, ProductGroupList.onProductCategoriesLoaded);
	},
	onProductFamiliesLoaded: function(productFamilies)
	{
		ProductGroupList.selectFamily.selectpicker();

		var option = Util.createElementOption('Todas', 0);
		ProductGroupList.selectFamily.append(option);

		ProductGroupList.productFamilies = productFamilies.elements;
		ProductGroupList.productFamilies.forEach(productFamily =>
		{
			var option = Util.createElementOption(productFamily.name, productFamily.id, ProductGroupList.idProductFamily == productFamily.id);
			ProductGroupList.selectFamily.append(option);
		});
		ProductGroupList.selectFamily.selectpicker('refresh');
	},
	onProductFamilyLoaded: function(productFamily)
	{
		ProductGroupList.loadDatatables(productFamily.productCategories.elements);
	},
	onProductCategoriesLoaded: function(productGroups)
	{
		ProductGroupList.loadDatatables(productGroups.elements);
	},
	loadDatatables: function(productCategories)
	{
		ProductGroupList.datatables.clear().draw();
		ProductGroupList.productCategories = productCategories;
		ProductGroupList.productCategories.forEach((productCategory, index) =>
		{
			var rowData = ProductGroupList.newProductCategoryRowData(index, productCategory);
			ProductGroupList.datatables.row.add(rowData).draw();
		});

	},
	onProductFamilyChange: function()
	{
		ProductGroupList.idProductFamily = $(this).val();
		ProductGroupList.loadProductCategories();
	},
	newProductCategoryRowData: function(index, productCategory)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm {0}" onclick="ProductGroupList.{1}({2})" title="{3}">{4}</button>';
		var btnView = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonView', index, 'Ver Grupo', ICON_VIEW);
		var btnGroup = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonSubgrupos', index, 'Listar Subgrupos', ICON_PRODUCT_SUBGROUP);
		var btnRemove = btnTemplate.format(BTN_CLASS_REMOVE, 'onButtonRemove', index, 'Excluir Grupo', ICON_REMOVE);

		return [
			productCategory.name,
			'<div class="btn-group">{0}{1}{2}</div>'.format(btnView, btnGroup, btnRemove),
		];
	},
	onButtonAdd: function()
	{
		Util.redirect('productGroup/add/{0}'.format(ProductGroupList.idProductFamily));
	},
	onButtonView: function(index)
	{
		var productCategory = ProductGroupList.productCategories[index];
		Util.redirect('productGroup/view/{0}'.format(productCategory.id));
	},
	onButtonSubgrupos: function(index)
	{
		var productCategory = ProductGroupList.productCategories[index];
		Util.redirect('productSubgroup/list/{0}'.format(productCategory.id));
	},
	onButtonRemove: function(index)
	{
		var productCategory = ProductGroupList.productCategories[index];
		Util.redirect('productGroup/remove/{0}'.format(productCategory.id));
	},
}
