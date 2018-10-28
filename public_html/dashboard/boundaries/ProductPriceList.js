
$(document).ready(function()
{
	ProductPriceList.init();
});

var ProductPriceList = ProductPriceList ||
{
	init: function()
	{
		this.form = $('#form-product-prices');
		this.idProduct = $(this.form[0].idProduct).val();

		this.table = $('#table-product-prices');
		this.tbody = this.table.children('tbody');
		this.datatables = newDataTables(this.table);

		this.loadProduct();
		this.loadProductPrices();
	},
	loadProduct: function()
	{
		ws.product_get(this.idProduct, this.form, this.onProductLoaded);
	},
	loadProductPrices: function()
	{
		ws.productPrice_getAll(this.idProduct, this.tbody, this.onProductPricesLoaded);
	},
	onProductLoaded: function(product)
	{
		var form = ProductPriceList.form[0];
		$(form.name).val(product.name);
		$(form.unit).val(product.unit.name);
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
		var btnView = '<button class="btn btn-primary" data-index="' +index+ '" onclick="ProductPriceList.onButtonView(this)">Ver</button>';
		var btnRemove = '<button class="btn btn-danger" data-index="' +index+ '" onclick="ProductPriceList.onButtonRemove(this)">Excluir</button>';

		return [
			productPrice.name,
			productPrice.amount,
			productPrice.price,
			productPrice['package'].name,
			productPrice.productType.name,
			'<div class="btn-group">' + btnView + btnRemove + '</div>',
		];
	},
	onButtonView: function(button)
	{
		var index = button.dataset.index;
		var productPrice = ProductPriceList.productPrices[index];

		if (productPrice !== undefined)
			Util.redirect('product/viewPrice/' +productPrice.id, true);
	},
	onButtonRemove: function(button)
	{
		var index = button.dataset.index;
		var productPrice = ProductPriceList.productPrices[index];

		if (productPrice !== undefined)
			Util.redirect('product/removePrice/' +productPrice.id, true);
	}
}
