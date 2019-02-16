
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
		var btnTemplate = '<button class="btn btn-sm {0}" onclick="ProductPriceList.{1}({2})" title="{3}">{4}</button>';
		var btnView = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonView', index, 'Ver Preço de Produto', ICON_VIEW);
		var btnRemove = btnTemplate.format(BTN_CLASS_REMOVE, 'onButtonRemove', index, 'Excluir Preço de Produto', ICON_REMOVE);

		return [
			productPrice.name,
			productPrice.provider.fantasyName,
			productPrice.manufacturer === null ? '-' : productPrice.manufacturer.fantasyName,
			productPrice.amount,
			productPrice.price,
			productPrice.productPackage.name,
			productPrice.productType === null ? '-' : productPrice.productType.name,
			'<div class="btn-group">{0}{1}</div>'.format(btnView, btnRemove),
		];
	},
	onButtonView: function(index)
	{
		var productPrice = ProductPriceList.productPrices[index];
		Util.redirect('product/viewPrice/{0}'.format(productPrice.id));
	},
	onButtonRemove: function(index)
	{
		var productPrice = ProductPriceList.productPrices[index];
		Util.redirect('product/removePrice/{0}'.format(productPrice.id));
	},
	onButtonAdd: function()
	{
		Util.redirect('product/addPrice/{0}'.format(ProductPriceList.idProduct), true);
	},
}
