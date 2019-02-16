
$(document).ready(function()
{
	ProductSearch.init();
});

var ProductSearch = ProductSearch ||
{
	init: function()
	{
		this.form = $('#form-search');
		this.table = $('#table-products');
		this.tbody = this.table.find('tbody');
		this.datatables = newDataTables(this.table);
		this.initFormtSettings();
	},
	initFormtSettings: function()
	{
		this.form.validate({
			'rules': {
				'filter': {
					'required': true,
				},
				'value': {
					'required': true,
				},
			},
			submitHandler: function(form) {
				try {
					ProductSearch.onSearch($(form));
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			},
		});
	},
	onSearch: function()
	{
		var form = ProductSearch.form[0];
		var value = $(form.value).val();
		var filter = $(form.filter).val();

		ws.product_search(filter, value, ProductSearch.form, ProductSearch.searchProducts);
	},
	searchProducts: function(products)
	{
		ProductSearch.datatables.rows().remove();
		ProductSearch.products = products.elements;
		ProductSearch.products.forEach(function(product, index)
		{
			var productRowData = ProductSearch.newProductRowData(index, product);
			ProductSearch.datatables.row.add(productRowData).draw();
		});
	},
	newProductRowData: function(index, product)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm {0}" onclick="ProductSearch.{1}(this, {2})" title="{3}">{4}</button>';
		var btnView = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonView', index, 'Ver Produto', ICON_VIEW);
		var btnViewPrices = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonPrices', index, 'Ver Produto', ICON_VIEW);
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
		var product = ProductSearch.products[index];
		Util.redirect('product/view/{0}'.format(product.id));
	},
	onButtonPrices: function(button, index)
	{
		var product = ProductSearch.products[index];
		Util.redirect('product/viewPrices/{0}'.format(product.id));
	},
	onButtonEnable: function(button, index)
	{
		var product = ProductSearch.products[index];
		var tr = $(button).parents('tr');

		ws.product_setActive(product.id, tr, function(product, message)
		{
			ProductSearch.onChangeProduct(product, tr, index);
			Util.showSuccess(message);
		});
	},
	onButtonDisable: function(button, index)
	{
		var product = ProductSearch.products[index];
		var tr = $(button).parents('tr');

		ws.product_setInactive(product.id, tr, function(product, message)
		{
			ProductSearch.onChangeProduct(product, tr, index);
			Util.showSuccess(message);
		});
	},
	onChangeProduct: function(product, tr, index)
	{
		ProductSearch.products[index] = product;
		var productRowData = ProductSearch.newProductRowData(index, product);
		ProductSearch.datatables.row(tr).data(productRowData);
	},
}
