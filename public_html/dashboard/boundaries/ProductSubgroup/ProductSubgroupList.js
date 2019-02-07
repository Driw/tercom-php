
$(document).ready(function()
{
	ProductSubgroupList.init();
});

var ProductSubgroupList = ProductSubgroupList ||
{
	init: function()
	{
		ProductSubgroupList.table = $('#table-product-subgroups');
		ProductSubgroupList.idProductGroup = $('#idProductGroup').val();
		ProductSubgroupList.selectGroup = $('#groups');
		ProductSubgroupList.selectGroup.change(ProductSubgroupList.onProductGroupChange);
		ProductSubgroupList.tbody = ProductSubgroupList.table.children('tbody');
		ProductSubgroupList.datatables = newDataTables(ProductSubgroupList.table);
		ProductSubgroupList.loadProductFamilies();
		ProductSubgroupList.loadProductCategories();
	},
	loadProductFamilies: function()
	{
		ws.productGroup_getAll(ProductSubgroupList.selectGroup, ProductSubgroupList.onProductFamiliesLoaded);
	},
	loadProductCategories: function()
	{
		if (ProductSubgroupList.idProductGroup > 0)
			ws.productGroup_getCategories(ProductSubgroupList.idProductGroup, ProductSubgroupList.tbody, ProductSubgroupList.onProductGroupLoaded);
		else
			ws.productSubgroup_getAll(ProductSubgroupList.tbody, ProductSubgroupList.onProductCategoriesLoaded);
	},
	onProductFamiliesLoaded: function(productFamilies)
	{
		ProductSubgroupList.selectGroup.selectpicker();

		var option = Util.createElementOption('Todos', 0);
		ProductSubgroupList.selectGroup.append(option);

		ProductSubgroupList.productFamilies = productFamilies.elements;
		ProductSubgroupList.productFamilies.forEach(productGroup =>
		{
			var option = Util.createElementOption(productGroup.name, productGroup.id, ProductSubgroupList.idProductGroup == productGroup.id);
			ProductSubgroupList.selectGroup.append(option);
		});
		ProductSubgroupList.selectGroup.selectpicker('refresh');
	},
	onProductGroupLoaded: function(productGroup)
	{
		ProductSubgroupList.loadDatatables(productGroup.productCategories.elements);
	},
	onProductCategoriesLoaded: function(productSubgroups)
	{
		ProductSubgroupList.loadDatatables(productSubgroups.elements);
	},
	loadDatatables: function(productCategories)
	{
		ProductSubgroupList.datatables.clear().draw();
		ProductSubgroupList.productCategories = productCategories;
		ProductSubgroupList.productCategories.forEach((productCategory, index) =>
		{
			var rowData = ProductSubgroupList.newProductCategoryRowData(index, productCategory);
			ProductSubgroupList.datatables.row.add(rowData).draw();
		});
	},
	onProductGroupChange: function()
	{
		ProductSubgroupList.idProductGroup = $(this).val();
		ProductSubgroupList.loadProductCategories();
	},
	newProductCategoryRowData: function(index, productCategory)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm btn-{0}" onclick="ProductSubgroupList.{1}({2})">{3}</button>';
		var btnView = btnTemplate.format('primary', 'onButtonView', index, 'Ver');
		var btnSubgroup = btnTemplate.format('info', 'onButtonSectores', index, 'Setores');
		var btnRemove = btnTemplate.format('danger', 'onButtonRemove', index, 'Remover');

		return [
			productCategory.name,
			'<div class="btn-group">{0}{1}{2}</div>'.format(btnView, btnSubgroup, btnRemove),
		];
	},
	onButtonAdd: function()
	{
		Util.redirect('productSubgroup/add/{0}'.format(ProductSubgroupList.idProductGroup), true);
	},
	onButtonView: function(index)
	{
		var productCategory = ProductSubgroupList.productCategories[index];

		if (productCategory !== undefined)
			Util.redirect('productSubgroup/view/{0}'.format(productCategory.id), true);
	},
	onButtonSectores: function(index)
	{
		var productCategory = ProductSubgroupList.productCategories[index];

		if (productCategory !== undefined)
			Util.redirect('productSector/list/{0}'.format(productCategory.id), true);
	},
	onButtonRemove: function(index)
	{
		var productCategory = ProductSubgroupList.productCategories[index];

		if (productCategory !== undefined)
			Util.redirect('productSubgroup/remove/{0}'.format(productCategory.id), true);
	},
}
