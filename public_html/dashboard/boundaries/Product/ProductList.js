
$(document).ready(function()
{
	ProductList.init();
});

var ProductList = ProductList ||
{
	init: function()
	{
		this.products = [];
		this.table = $('#table-product-list');
		this.tbody = this.table.children('tbody');
		this.datatables = newDataTables(this.table);
		this.wsProductGetAll();
	},
	wsProductGetAll: function()
	{
		ws.product_getAll(this.tbody, this.onProductGetAll);
	},
	onProductGetAll: function(result)
	{
		var products = result.elements;
		products.forEach(function(product)
		{
			ProductList.addProductRow(product);
		});
	},
	addProductRow: function(product)
	{
		var id = product.id;
		var index = ProductList.products.push(product) - 1;
		var productRowData = ProductList.newProductRowData(index, product);
		var row = ProductList.datatables.row.add(productRowData).draw();

		ProductList.setProductButtons(product.id);
	},
	newProductRowData: function(index, product)
	{
		var id = product.id;
		var btnView = '<button type="button" class="btn btn-info" data-index="' +index+ '" id="btn-product-view-' +id+ '">Ver</button>';
		var btnActive = '<button type="button" class="btn btn-primary" data-index="' +index+ '" id="btn-product-active-' +id+ '">Ativar</button>';
		var btnDesactive = '<button type="button" class="btn btn-secondary" data-index="' +index+ '" id="btn-product-inactive-' +id+ '">Desativar</button>';
		var btnViewPrices = '<button type="button" class="btn btn-info" data-index="' +index+ '" id="btn-product-prices-' +id+ '">Preços</button>';

		return [
			product.id,
			product.name,
			product.description,
			product.unit.name,
			product.category.family.name,
			'<div class="btn-group" id="product-' +product.id+ '">' + btnView + btnViewPrices + (product.inactive ? btnActive : btnDesactive) + '</div>',
		];
	},
	setProduct: function(product)
	{
		var index = ProductList.lookProductIndex(product.id);
		ProductList.products[index] = product;

		return index;
	},
	lookProductIndex: function(idProduct)
	{
		for (var i = 0; i < ProductList.products.length; i++)
			if (ProductList.products[i].id === idProduct)
				return i;

		return -1;
	},
	setProductButtons: function(idProduct)
	{
		$('#btn-product-view-' +idProduct).click(ProductList.onClickBtnView);
		$('#btn-product-active-' +idProduct).click(ProductList.onClickBtnActive);
		$('#btn-product-inactive-' +idProduct).click(ProductList.onClickBtnInactive);
		$('#btn-product-prices-' +idProduct).click(ProductList.onClickBtnPrices);
	},
	onClickBtnView: function()
	{
		var index = this.dataset.index;
		var product = ProductList.products[index];
		Util.redirect('product/view/' +product.id, true);
	},
	onClickBtnPrices: function()
	{
		var index = this.dataset.index;
		var product = ProductList.products[index];
		Util.redirect('product/viewPrices/' +product.id, true);
	},
	onClickBtnActive: function()
	{
		var index = this.dataset.index;
		var product = ProductList.products[index];
		var tr = $(this).parents('tr');
		var td = $(this).parents('td');

		ws.product_setActive(product.id, tr, ProductList.onProductSetInactive);
	},
	onClickBtnInactive: function()
	{
		var index = this.dataset.index;
		var product = ProductList.products[index];
		var tr = $(this).parents('tr');
		var td = $(this).parents('td');

		ws.product_setInactive(product.id, tr, ProductList.onProductSetInactive);
	},
	onProductSetInactive: function(product)
	{
		var tr = $('#product-' +product.id).parents('tr');
		var index = ProductList.setProduct(product);
		var productRowData = ProductList.newProductRowData(index, product);
		var row = ProductList.datatables.row(tr).data(productRowData);
		var id = product.id;
		ProductList.setProductButtons(product.id);
	},
}
