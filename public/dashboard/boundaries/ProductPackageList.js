
$(document).ready(function()
{
	ProductPackageList.init();
});

var ProductPackageList = ProductPackageList ||
{
	init: function()
	{
		this.productPackages = [];
		this.table = $('#table-product-types');
		this.tbody = this.table.children('tbody');
		this.datatables = newDataTables(this.table);
		this.loadProductPackages();
	},
	loadProductPackages: function()
	{
		ws.productPackage_getAll(this.tbody, this.onProductPackagesLoaded);
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
		var row = ProductPackageList.datatables.row.add(rowData).draw();
	},
	newProductPackageRowData: function(index, productPackage)
	{
		var id = productPackage.id;
		var btnView = '<button type="button" class="btn btn-info" data-index="' +index+ '" onclick="ProductPackageList.onButtonView(this)">Ver</button>';
		var btnRemove = '<button type="button" class="btn btn-primary" data-index="' +index+ '" onclick="ProductPackageList.onButtonRemove(this)">Excluir</button>';

		return [
			productPackage.name,
			'<div class="btn-group">' + btnView + btnRemove + '</div>',
		];
	},
	onButtonView: function(button)
	{
		var index = button.dataset.index;
		var productPackage = ProductPackageList.productPackages[index];

		if (productPackage !== undefined)
			Util.redirect('productPackage/view/' +productPackage.id, true);
	},
	onButtonRemove: function(button)
	{
		var index = button.dataset.index;
		var productPackage = ProductPackageList.productPackages[index];

		if (productPackage !== undefined)
			Util.redirect('productPackage/remove/' +productPackage.id, true);
	},
}
