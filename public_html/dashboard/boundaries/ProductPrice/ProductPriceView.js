
$(document).ready(function()
{
	ProductPriceView.init();
});

var ProductPriceView = ProductPriceView ||
{
	init: function()
	{
		ProductPriceView.loaded = false;
		ProductPriceView.formProduct = $('#form-product');
		ProductPriceView.form = $('#form-product-price-view');
		var form = ProductPriceView.form[0];
		ProductPriceView.selectProvider = $(form.idProvider);
		ProductPriceView.selectManufacturer = $(form.idManufacturer);
		ProductPriceView.selectProductPackage = $(form.idProductPackage);
		ProductPriceView.selectProductType = $(form.idProductType);
		ProductPriceView.idProductPrice = $(form.idProductPrice).val();

		ProductPriceView.initForm();
		ProductPriceView.loadProductPrice();
		ProductPriceView.loadProviders();
		ProductPriceView.loadManufacturers();
		ProductPriceView.loadProductPackages();
		ProductPriceView.loadProductTypes();
	},
	initForm: function()
	{
		ws.productPrice_settings(ProductPriceView.form, ProductPriceView.onFormSettings);
	},
	loadProductPrice: function()
	{
		ws.productPrice_get(ProductPriceView.idProductPrice, ProductPriceView.form, ProductPriceView.onLoadProductPrice);
	},
	loadProviders: function()
	{
		ws.provider_getAll(ProductPriceView.selectProvider, ProductPriceView.onLoadProviders);
	},
	loadManufacturers: function()
	{
		ws.manufacturer_getAll(ProductPriceView.selectManufacturer, ProductPriceView.onLoadManufacturers);
	},
	loadProductPackages: function()
	{
		ws.productPackage_getAll(ProductPriceView.selectProductPackage, ProductPriceView.onLoadProductPackages);
	},
	loadProductTypes: function()
	{
		ws.productType_getAll(ProductPriceView.selectProductType, ProductPriceView.onLoadProductTypes);
	},
	onFormSettings: function(settings)
	{
		ProductPriceView.form.validate({
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
					ProductPriceView.submit($(form));
				} catch (e) {
					console.log(e.stack);
					alert(e.message);
				}
				return false;
			},
		});
	},
	submit: function(form)
	{
		ws.productPrice_set(ProductPriceView.idProductPrice, form, ProductPriceView.onSubmited)
	},
	onSubmited: function(productPrice, message)
	{
		ProductPriceView.onLoadProductPrice(productPrice, message);
	},
	onLoadProductPrice: function(productPrice, message)
	{
		ProductPriceView.productPrice = productPrice;

		var form = ProductPriceView.form[0];
		$(form.name).val(productPrice.name);
		$(form.amount).val(productPrice.amount);
		$(form.price).val(Util.formatMoney(productPrice.price));
		$(form.idProvider).val(productPrice.provider.id).selectpicker('refresh');
		$(form.idProductPackage).val(productPrice.productPackage.id).selectpicker('refresh');

		if (productPrice.manufacturer !== null)
			$(form.idManufacturer).val(productPrice.manufacturer.id).selectpicker('refresh');

		if (productPrice.productType !== null)
			$(form.idProductType).val(productPrice.productType.id).selectpicker('refresh');

		ws.product_get(productPrice.product.id, ProductPriceView.formProduct, ProductPriceView.onLoadProduct);

		if (ProductPriceView.loaded)
			Util.showSuccess(message);
		else
			Util.showInfo(message);

		ProductPriceView.loaded = true;
	},
	onLoadProduct: function(product)
	{
		var form = ProductPriceView.formProduct[0];
		$(form.name).val(product.name);
		$(form.unit).val(product.productUnit.name);
		$(form.description).html(product.description);

		if ($(ProductPriceView.form[0].name).val === '')
			$(ProductPriceView.form[0].name).val(product.name);
	},
	onLoadProviders: function(providers)
	{
		ProductPriceView.selectProvider.empty();
		ProductPriceView.selectProvider.append(Util.createElementOption('selecione um fornecedor', ''));

		ProductPriceView.providers = providers.elements;
		ProductPriceView.providers.forEach(function(provider)
		{
			var selected = ProductPriceView.productPrice !== undefined && ProductPriceView.productPrice.provider.id === provider.id;
			var option = Util.createElementOption('{0} - {1}'.format(provider.fantasyName, Util.formatCnpj(provider.cnpj)), provider.id, selected);
			ProductPriceView.selectProvider.append(option);
		});
		ProductPriceView.selectProvider.selectpicker('refresh');
	},
	onLoadManufacturers: function(manufacturers)
	{
		ProductPriceView.selectManufacturer.empty();
		ProductPriceView.selectManufacturer.append(Util.createElementOption('selecione um fabricante', 0));

		ProductPriceView.manufacturers = manufacturers.elements;
		ProductPriceView.manufacturers.forEach(function(manufacturer)
		{
			var selected =	ProductPriceView.productPrice !== undefined &&
							ProductPriceView.productPrice.manufacturer !== null &&
							ProductPriceView.productPrice.manufacturer.id === manufacturer.id;
			var option = Util.createElementOption(manufacturer.fantasyName, manufacturer.id, selected);
			ProductPriceView.selectManufacturer.append(option);
		});
		ProductPriceView.selectManufacturer.selectpicker('refresh');
	},
	onLoadProductPackages: function(productPackages)
	{
		ProductPriceView.selectProductPackage.empty();
		ProductPriceView.selectProductPackage.append(Util.createElementOption('selecione uma embalagem de produto'));

		ProductPriceView.productPackages = productPackages.elements;
		ProductPriceView.productPackages.forEach(function(productPackage)
		{
			var selected = ProductPriceView.productPrice !== undefined && ProductPriceView.productPrice.productPackage.id === productPackage.id;
			var option = Util.createElementOption(productPackage.name, productPackage.id, selected);
			ProductPriceView.selectProductPackage.append(option);
		});
		ProductPriceView.selectProductPackage.selectpicker('refresh');
	},
	onLoadProductTypes: function(productTypes)
	{
		ProductPriceView.selectProductType.empty();
		ProductPriceView.selectProductType.append(Util.createElementOption('selecione um tipo de produto', 0));

		ProductPriceView.productTypes = productTypes.elements;
		ProductPriceView.productTypes.forEach(function(productType)
		{
			var selected =	ProductPriceView.productPrice !== undefined &&
							ProductPriceView.productPrice.productType !== null &&
							ProductPriceView.productPrice.productType.id === productType.id;
			var option = Util.createElementOption(productType.name, productType.id, selected);
			ProductPriceView.selectProductType.append(option);
		});
		ProductPriceView.selectProductType.selectpicker('refresh');
	},
}
