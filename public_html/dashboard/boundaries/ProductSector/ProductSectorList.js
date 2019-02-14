
$(document).ready(function()
{
	ProductSectorList.init();
});

var ProductSectorList = ProductSectorList ||
{
	init: function()
	{
		ProductSectorList.table = $('#table-product-subgroups');
		ProductSectorList.idProductSubgroup = $('#idProductSubgroup').val();
		ProductSectorList.selectSubgroup = $('#groups');
		ProductSectorList.selectSubgroup.change(ProductSectorList.onProductSubgroupChange);
		ProductSectorList.tbody = ProductSectorList.table.children('tbody');
		ProductSectorList.datatables = newDataTables(ProductSectorList.table);
		ProductSectorList.loadProductFamilies();
		ProductSectorList.loadProductCategories();
	},
	loadProductFamilies: function()
	{
		ws.productSubgroup_getAll(ProductSectorList.selectSubgroup, ProductSectorList.onProductFamiliesLoaded);
	},
	loadProductCategories: function()
	{
		if (ProductSectorList.idProductSubgroup > 0)
			ws.productSubgroup_getCategories(ProductSectorList.idProductSubgroup, ProductSectorList.tbody, ProductSectorList.onProductSubgroupLoaded);
		else
			ws.productSector_getAll(ProductSectorList.tbody, ProductSectorList.onProductCategoriesLoaded);
	},
	onProductFamiliesLoaded: function(productFamilies)
	{
		ProductSectorList.selectSubgroup.selectpicker();

		var option = Util.createElementOption('Todos', 0);
		ProductSectorList.selectSubgroup.append(option);

		ProductSectorList.productFamilies = productFamilies.elements;
		ProductSectorList.productFamilies.forEach(productSubgroup =>
		{
			var option = Util.createElementOption(productSubgroup.name, productSubgroup.id, ProductSectorList.idProductSubgroup == productSubgroup.id);
			ProductSectorList.selectSubgroup.append(option);
		});
		ProductSectorList.selectSubgroup.selectpicker('refresh');
	},
	onProductSubgroupLoaded: function(productSubgroup)
	{
		ProductSectorList.loadDatatables(productSubgroup.productCategories.elements);
	},
	onProductCategoriesLoaded: function(productSectors)
	{
		ProductSectorList.loadDatatables(productSectors.elements);
	},
	loadDatatables: function(productCategories)
	{
		ProductSectorList.datatables.clear().draw();
		ProductSectorList.productCategories = productCategories;
		ProductSectorList.productCategories.forEach((productCategory, index) =>
		{
			var rowData = ProductSectorList.newProductCategoryRowData(index, productCategory);
			ProductSectorList.datatables.row.add(rowData).draw();
		});
	},
	onProductSubgroupChange: function()
	{
		ProductSectorList.idProductSubgroup = $(this).val();
		ProductSectorList.loadProductCategories();
	},
	newProductCategoryRowData: function(index, productCategory)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm {0}" onclick="ProductSectorList.{1}({2})" title="{3}">{4}</button>';
		var btnView = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonView', index, 'Ver Setor', ICON_VIEW);
		var btnRemove = btnTemplate.format(BTN_CLASS_REMOVE, 'onButtonRemove', index, 'Excluir Setor', ICON_REMOVE);

		return [
			productCategory.name,
			'<div class="btn-group">{0}{1}</div>'.format(btnView, btnRemove),
		];
	},
	onButtonAdd: function()
	{
		Util.redirect('productSector/add/{0}'.format(ProductSectorList.idProductSubgroup));
	},
	onButtonView: function(index)
	{
		var productCategory = ProductSectorList.productCategories[index];
		Util.redirect('productSector/view/{0}'.format(productCategory.id));
	},
	onButtonRemove: function(index)
	{
		var productCategory = ProductSectorList.productCategories[index];
		Util.redirect('productSector/remove/{0}'.format(productCategory.id));
	},
}
