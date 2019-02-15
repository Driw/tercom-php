
$(document).ready(function()
{
	ProductPackageList.init();
});

var ProductPackageList = ProductPackageList ||
{
	init: function()
	{
		ProductPackageList.productPackages = [];
		ProductPackageList.table = $('#table-product-types');
		ProductPackageList.tbody = ProductPackageList.table.children('tbody');
		ProductPackageList.datatables = newDataTables(ProductPackageList.table);
		ProductPackageList.loadProductPackages();
	},
	loadProductPackages: function()
	{
		ws.productPackage_getAll(ProductPackageList.tbody, ProductPackageList.onProductPackagesLoaded);
	},
	onProductPackagesLoaded: function(productPackages)
	{
		ProductPackageList.productPackages = productPackages.elements;
		ProductPackageList.productPackages.forEach(function(productPackage, index)
		{
			ProductPackageList.addProductPackageRow(index, productPackage);
		});
	},
	addProductPackageRow: function(index, productPackage)
	{
		var rowData = ProductPackageList.newProductPackageRowData(index, productPackage);
		ProductPackageList.datatables.row.add(rowData).draw();
	},
	newProductPackageRowData: function(index, productPackage)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm {0}" onclick="ProductPackageList.{1}({2})" title="{3}">{4}</button>';
		var btnView = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonView', index, 'Ver Embalagem de Produto', ICON_VIEW);
		var btnRemove = btnTemplate.format(BTN_CLASS_REMOVE, 'onButtonRemove', index, 'Excluir Embalagem de Produto', ICON_REMOVE);

		return [
			productPackage.name,
			'<div class="btn-group">{0}{1}</div>'.format(btnView, btnRemove),
		];
	},
	onButtonView: function(index)
	{
		var productPackage = ProductPackageList.productPackages[index];
		Util.redirect('productPackage/view/' +productPackage.id);
	},
	onButtonRemove: function(index)
	{
		var productPackage = ProductPackageList.productPackages[index];
		Util.redirect('productPackage/remove/' +productPackage.id);
	},
	onButtonAdd: function(index)
	{
		Util.redirect('productPackage/add');
	},
}
