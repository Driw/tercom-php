
$(document).ready(function()
{
	ProductList.init();
});

var ProductList = ProductList ||
{
	init: function()
	{
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
		ProductList.products = result.elements;
		ProductList.products.forEach((product, index) =>
		{
			var productRowData = ProductList.newProductRowData(index, product);
			ProductList.datatables.row.add(productRowData).draw();
		});
	},
	newProductRowData: function(index, product)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm {0}" onclick="ProductList.{1}(this, {2})" title="{3}">{4}</button>';
		var btnView = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonView', index, 'Ver Produto', ICON_VIEW);
		var btnViewPrices = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonPrices', index, 'Ver Produto', ICON_PRICES);
		var btnEnable = btnTemplate.format(BTN_CLASS_ENABLE, 'onButtonEnable', index, 'Habilitar/Mostrar Produto', ICON_ENABLE);
		var btnDisable = btnTemplate.format(BTN_CLASS_DISABLE, 'onButtonDisable', index, 'Desabilitar/Esconder Produto', ICON_DISABLE);

		return [
			product.id,
			product.name,
			product.description,
			product.productUnit === null ? '-' : product.productUnit.name,
			product.productCategory === null ? '-' : product.productCategory.name,
			'<div class="btn-group">{0}{1}{2}</div>'.format(btnView, btnViewPrices, product.inactive ? btnEnable : btnDisable),
		];
	},
	onButtonView: function(button, index)
	{
		var product = ProductList.products[index];
		Util.redirect('product/view/{0}'.format(product.id));
	},
	onButtonPrices: function(button, index)
	{
		var product = ProductList.products[index];
		Util.redirect('product/viewPrices/{0}'.format(product.id));
	},
	onButtonEnable: function(button, index)
	{
		var product = ProductList.products[index];
		var tr = $(button).parents('tr');

		ws.product_setActive(product.id, tr, function(product, message)
		{
			ProductList.onChangeProduct(product, tr, index);
			Util.showSuccess(message);
		});
	},
	onButtonDisable: function(button, index)
	{
		var product = ProductList.products[index];
		var tr = $(button).parents('tr');

		ws.product_setInactive(product.id, tr, function(product, message)
		{
			ProductList.onChangeProduct(product, tr, index);
			Util.showSuccess(message);
		});
	},
	onChangeProduct: function(product, tr, index)
	{
		ProductList.products[index] = product;
		var productRowData = ProductList.newProductRowData(index, product);
		ProductList.datatables.row(tr).data(productRowData);
	},
}
