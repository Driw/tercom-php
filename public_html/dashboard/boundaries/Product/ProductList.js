
$(document).ready(function()
{
	ProductList.init();
});

var ProductList = ProductList ||
{
	init: function()
	{
		ProductList.idModal = $('#product-customer-modal');
		ProductList.idForm = $('#form-product-customer-view');
		ProductList.table = $('#table-product-list');
		ProductList.tbody = ProductList.table.children('tbody');
		ProductList.datatables = newDataTables(ProductList.table);
		ProductList.initIdForm();
		ProductList.wsProductGetAll();
	},
	initIdForm: function()
	{
		ProductList.idForm.validate({
			'rules': {
				'idProductCustomer': {
					'required': true,
					'remoteapi': {
						'webservice': 'product/avaiable/idProductCustomer/{value}/{idProduct}',
						'parameters': {
							'idProduct': function() { return ProductList.product.id; },
						},
					},
				},
			},
			submitHandler: function(form) {
				try {
					ProductList.submitIdProductCustomer(form);
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			},
		});
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
		var btnViewPrices = btnTemplate.format(BTN_CLASS_VIEW, 'onButtonPrices', index, 'Ver Preços do Produto', ICON_PRICES);
		var btnIdProductCustomer = btnTemplate.format(BTN_CLASS_OPEN, 'onButtonId', index, 'Alterar Cliente Produto ID', ICON_OPEN);
		var btnEnable = btnTemplate.format(BTN_CLASS_ENABLE, 'onButtonEnable', index, 'Habilitar/Mostrar Produto', ICON_ENABLE);
		var btnDisable = btnTemplate.format(BTN_CLASS_DISABLE, 'onButtonDisable', index, 'Desabilitar/Esconder Produto', ICON_DISABLE);

		return [
			IS_LOGIN_TERCOM ? product.id : product.idProductCustomer,
			product.name,
			product.description,
			product.productUnit === null ? '-' : product.productUnit.name,
			product.productCategory === null ? '-' : product.productCategory.name,
			'<div class="btn-group">{0}{1}{2}{3}</div>'.format(btnView, btnViewPrices, btnIdProductCustomer, product.inactive ? btnEnable : btnDisable),
		];
	},
	submitIdProductCustomer: function(form)
	{
		ws.product_setIdProductCustomer(ProductList.product.id, form);
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
	onButtonId: function(button, index)
	{
		var product = ProductList.products[index];
		var form = ProductList.idForm[0];
		$(form.idProductCustomer).val(product.idProductCustomer);

		ProductList.idModal.modal('show');
		ProductList.index = index;
		ProductList.product = product;
		ProductList.productTr = $(button).parents('tr');
	},
	onButtonIdUpdate: function()
	{
		ws.product_setCustomerId(ProductList.product.id, $(ProductList.idForm[0].idProductCustomer).val(), ProductList.idForm, ProductList.onIdUpdated);
	},
	onIdUpdated: function(product, message)
	{
		var productRowData = ProductList.newProductRowData(ProductList.index, product);
		ProductList.datatables.row(ProductList.productTr).data(productRowData);
		ProductList.products[ProductList.index] = product;
		ProductList.idModal.modal('hide');
		Util.showSuccess(message);
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
