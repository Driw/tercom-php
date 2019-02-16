
$(document).ready(function()
{
	ProductPriceAdd.init();
});

var ProductPriceAdd = ProductPriceAdd ||
{
	init: function()
	{
		ProductPriceAdd.loaded = false;
		ProductPriceAdd.formProduct = $('#form-product');
		ProductPriceAdd.idProduct = $(ProductPriceAdd.formProduct[0].idProduct).val();

		ProductPriceAdd.form = $('#form-product-price-add');
		var form = this.form[0];
		ProductPriceAdd.selectProvider = $(form.idProvider);
		ProductPriceAdd.selectManufacturer = $(form.idManufacturer);
		ProductPriceAdd.selectProductPackage = $(form.idProductPackage);
		ProductPriceAdd.selectProductType = $(form.idProductType);

		ProductPriceAdd.initForm();
		ProductPriceAdd.loadProduct();
		ProductPriceAdd.loadProviders();
		ProductPriceAdd.loadManufacturers();
		ProductPriceAdd.loadProductPackages();
		ProductPriceAdd.loadProductTypes();
	},
	initForm: function()
	{
		ws.productPrice_settings(ProductPriceAdd.form, ProductPriceAdd.onFormSettings);
	},
	loadProduct: function()
	{
		ws.product_get(ProductPriceAdd.idProduct, ProductPriceAdd.formProduct, ProductPriceAdd.onLoadProduct);
	},
	loadProviders: function()
	{
		ws.provider_getAll(ProductPriceAdd.selectProvider, ProductPriceAdd.onLoadProviders);
	},
	loadManufacturers: function()
	{
		ws.manufacturer_getAll(ProductPriceAdd.selectManufacturer, ProductPriceAdd.onLoadManufacturers);
	},
	loadProductPackages: function()
	{
		ws.productPackage_getAll(ProductPriceAdd.selectProductPackage, ProductPriceAdd.onLoadProductPackages);
	},
	loadProductTypes: function()
	{
		ws.productType_getAll(ProductPriceAdd.selectProductType, ProductPriceAdd.onLoadProductTypes);
	},
	onFormSettings: function(settings)
	{
		ProductPriceAdd.form.validate({
			'rules': {
				'idProvider': {
					'required': true,
				},
				'idManufacturer': {
					'required': false,
				},
				'idProductPackage': {
					'required': true,
				},
				'idProductType': {
					'required': false,
				},
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
				},
				'amount': {
					'required': true,
					'range': [ settings.minAmount, settings.maxAmount ],
				},
				'price': {
					'required': true,
					'range': [ settings.minPrice, settings.maxPrice ],
				},
			},
			submitHandler: function(form) {
				try {
					ProductPriceAdd.submit($(form));
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			},
		});
	},
	submit: function(form)
	{
		ws.productPrice_add(ProductPriceAdd.idProduct, form, ProductPriceAdd.onSubmited)
	},
	onSubmited: function(productPrice, message)
	{
		message += ' <button class="btn btn-sm {0}" onclick="ProductPriceAdd.onButtonLast()">{1} Ver Preço de Produto</button>'.format(BTN_CLASS_VIEW, ICON_VIEW);
		ProductPriceAdd.lastAdded = productPrice;
		ProductPriceAdd.form.trigger('reset');
		Util.showSuccess(message);
	},
	onLoadProduct: function(product, message)
	{
		var form = ProductPriceAdd.formProduct[0];
		$(form.name).val(product.name);
		$(form.unit).val(product.productUnit.name);
		$(form.description).html(product.description);
		$(ProductPriceAdd.form[0].name).val(product.name);

		if (ProductPriceAdd.loaded)
			Util.showSuccess(message);
		else
			Util.showInfo(message);
	},
	onLoadProviders: function(providers)
	{
		ProductPriceAdd.selectProvider.empty();
		ProductPriceAdd.selectProvider.append(Util.createElementOption('selecione um fornecedor', ''));

		ProductPriceAdd.providers = providers.elements;
		ProductPriceAdd.providers.forEach(function(provider)
		{
			var option = Util.createElementOption('{0} - {1}'.format(provider.fantasyName, Util.formatCnpj(provider.cnpj)), provider.id);
			ProductPriceAdd.selectProvider.append(option);
		});
		ProductPriceAdd.selectProvider.selectpicker('refresh');
	},
	onLoadManufacturers: function(manufacturers)
	{
		ProductPriceAdd.selectManufacturer.empty();
		ProductPriceAdd.selectManufacturer.append(Util.createElementOption('selecione um fabricante', ''));

		ProductPriceAdd.manufacturers = manufacturers.elements;
		ProductPriceAdd.manufacturers.forEach(function(manufacturer)
		{
			var option = Util.createElementOption(manufacturer.fantasyName, manufacturer.id);
			ProductPriceAdd.selectManufacturer.append(option);
		});
		ProductPriceAdd.selectManufacturer.selectpicker('refresh');
	},
	onLoadProductPackages: function(productPackages)
	{
		ProductPriceAdd.selectProductPackage.empty();
		ProductPriceAdd.selectProductPackage.append(Util.createElementOption('selecione uma embalagem de produto', ''));

		ProductPriceAdd.productPackages = productPackages.elements;
		ProductPriceAdd.productPackages.forEach(function(productPackage)
		{
			var option = Util.createElementOption(productPackage.name, productPackage.id);
			ProductPriceAdd.selectProductPackage.append(option);
		});
		ProductPriceAdd.selectProductPackage.selectpicker('refresh');
	},
	onLoadProductTypes: function(productTypes)
	{
		ProductPriceAdd.selectProductType.empty();
		ProductPriceAdd.selectProductType.append(Util.createElementOption('selecione um tipo de produto', ''));

		ProductPriceAdd.productTypes = productTypes.elements;
		ProductPriceAdd.productTypes.forEach(function(productType)
		{
			var option = Util.createElementOption(productType.name, productType.id);
			ProductPriceAdd.selectProductType.append(option);
		});
		ProductPriceAdd.selectProductType.selectpicker('refresh');
	},
	onButtonLast: function()
	{
		Util.redirect('product/viewPrice/{0}'.format(ProductPriceAdd.lastAdded.id));
	}
}
