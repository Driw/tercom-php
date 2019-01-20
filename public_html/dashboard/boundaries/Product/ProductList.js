
$(document).ready(function()
{
	ProductList.init();
});

var ProductList = ProductList ||
{
	init: function()
	{
		ProductList.products = [];
		ProductList.table = $('#table-product-list');
		ProductList.tbody = ProductList.table.children('tbody');
		ProductList.datatables = newDataTables(ProductList.table);
		ProductList.wsProductGetAll();
	},
	wsProductGetAll: function()
	{
		ws.product_getAll(ProductList.tbody, ProductList.onProductGetAll);
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
	},
	newProductRowData: function(index, product)
	{
		var id = product.id;
		var btnView = '<button type="button" class="btn btn-info" data-index="{0}" onclick="ProductList.onClickBtnView(this)">Ver</button>'.format(index);
		var btnActive = '<button type="button" class="btn btn-primary" data-index="{0}" onclick="ProductList.onClickBtnActive(this)">Ativar</button>'.format(index);
		var btnDesactive = '<button type="button" class="btn btn-secondary" data-index="{0}" onclick="ProductList.onClickBtnInactive(this)">Desativar</button>'.format(index);
		var btnViewPrices = '<button type="button" class="btn btn-info" data-index="{0}" onclick="ProductList.onClickBtnPrices(this)">Preços</button>'.format(index);

		return [
			product.id,
			product.name,
			product.description,
			product.productUnit === null ? '-' : product.productUnit.name,
			product.productCategory === null ? '-' : product.productCategory.name,
			'<div class="btn-group" id="product-{0}">{1}{2}{3}</div>'.format(product.id, btnView, btnViewPrices, product.inactive ? btnActive : btnDesactive),
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
	onClickBtnView: function(button)
	{
		var index = button.dataset.index;
		var product = ProductList.products[index];
		Util.redirect('product/view/{0}'.format(product.id), true);
	},
	onClickBtnPrices: function(button)
	{
		var index = button.dataset.index;
		var product = ProductList.products[index];
		Util.redirect('product/viewPrices/{0}'.format(product.id), true);
	},
	onClickBtnActive: function(button)
	{
		var index = button.dataset.index;
		var product = ProductList.products[index];
		var tr = $(button).parents('tr');
		var td = $(button).parents('td');

		ws.product_setActive(product.id, tr, ProductList.onProductSetInactive);
	},
	onClickBtnInactive: function(button)
	{
		var index = button.dataset.index;
		var product = ProductList.products[index];
		var tr = $(button).parents('tr');
		var td = $(button).parents('td');

		ws.product_setInactive(product.id, tr, ProductList.onProductSetInactive);
	},
	onProductSetInactive: function(product)
	{
		var tr = $('#product-' +product.id).parents('tr');
		var index = ProductList.setProduct(product);
		var productRowData = ProductList.newProductRowData(index, product);
		var row = ProductList.datatables.row(tr).data(productRowData);
	},
}
