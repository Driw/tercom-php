
$(document).ready(function()
{
	ProductUnitList.init();
});

var ProductUnitList = ProductUnitList ||
{
	init: function()
	{
		this.productUnits = [];
		this.table = $('#table-product-units');
		this.tbody = this.table.children('tbody');
		this.datatables = newDataTables(this.table);
		this.loadProductUnits();
	},
	loadProductUnits: function()
	{
		ws.productUnit_getAll(this.tbody, this.onProductUnitsLoaded);
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
		var row = ProductUnitList.datatables.row.add(rowData).draw();
	},
	newProductUnitRowData: function(index, productUnit)
	{
		var id = productUnit.id;
		var btnView = '<button type="button" class="btn btn-primary" data-index="' +index+ '" onclick="ProductUnitList.onButtonView(this)">Ver</button>';
		var btnRemove = '<button type="button" class="btn btn-danger" data-index="' +index+ '" onclick="ProductUnitList.onButtonRemove(this)">Excluir</button>';

		return [
			productUnit.shortName,
			productUnit.name,
			'<div class="btn-group">' + btnView + btnRemove + '</div>',
		];
	},
	onButtonView: function(button)
	{
		var index = button.dataset.index;
		var productUnit = ProductUnitList.productUnits[index];

		if (productUnit !== undefined)
			Util.redirect('productUnit/view/' +productUnit.id, true);
	},
	onButtonRemove: function(button)
	{
		var index = button.dataset.index;
		var productUnit = ProductUnitList.productUnits[index];

		if (productUnit !== undefined)
			Util.redirect('productUnit/remove/' +productUnit.id, true);
	},
}
