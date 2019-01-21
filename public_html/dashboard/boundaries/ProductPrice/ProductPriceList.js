
$(document).ready(function()
{
	ProductPriceList.init();
});

var ProductPriceList = ProductPriceList ||
{
	init: function()
	{
		ProductPriceList.form = $('#form-product-prices');
		ProductPriceList.idProduct = $(ProductPriceList.form[0].idProduct).val();

		ProductPriceList.table = $('#table-product-prices');
		ProductPriceList.tbody = ProductPriceList.table.children('tbody');
		ProductPriceList.datatables = newDataTables(ProductPriceList.table);

		ProductPriceList.loadProduct();
		ProductPriceList.loadProductPrices();
	},
	loadProduct: function()
	{
		ws.product_get(ProductPriceList.idProduct, ProductPriceList.form, ProductPriceList.onProductLoaded);
	},
	loadProductPrices: function()
	{
		ws.productPrice_getAll(ProductPriceList.idProduct, ProductPriceList.tbody, ProductPriceList.onProductPricesLoaded);
	},
	onProductLoaded: function(product)
	{
		var form = ProductPriceList.form[0];
		$(form.name).val(product.name);
		$(form.unit).val(product.productUnit.name);
		$(form.description).html(product.description);
	},
	onProductPricesLoaded: function(productPrices)
	{
		ProductPriceList.productPrices = productPrices.elements;
		ProductPriceList.productPrices.forEach(function(productPrice, index)
		{
			var dataRow = ProductPriceList.newDataRow(index, productPrice);
			ProductPriceList.datatables.row.add(dataRow).draw();
		});
	},
	newDataRow: function(index, productPrice)
	{
		var btnView = '<button class="btn btn-primary" data-index="{0}" onclick="ProductPriceList.onButtonView(this)">Ver</button>'.format(index);
		var btnRemove = '<button class="btn btn-danger" data-index="{0}" onclick="ProductPriceList.onButtonRemove(this)">Excluir</button>'.format(index);

		return [
			productPrice.name,
			productPrice.amount,
			productPrice.price,
			productPrice.productPackage.name,
			productPrice.productType.name,
			'<div class="btn-group">{0}{1}</div>'.format(btnView, btnRemove),
		];
	},
	onButtonView: function(button)
	{
		var index = button.dataset.index;
		var productPrice = ProductPriceList.productPrices[index];

		if (productPrice !== undefined)
			Util.redirect('product/viewPrice/{0}'.format(productPrice.id), true);
	},
	onButtonRemove: function(button)
	{
		var index = button.dataset.index;
		var productPrice = ProductPriceList.productPrices[index];

		if (productPrice !== undefined)
			Util.redirect('product/removePrice/{0}'.format(productPrice.id), true);
	}
}
