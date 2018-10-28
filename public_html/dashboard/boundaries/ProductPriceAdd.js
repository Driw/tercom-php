
$(document).ready(function()
{
	ProductPriceAdd.init();
});

var ProductPriceAdd = ProductPriceAdd ||
{
	init: function()
	{
		this.formProduct = $('#form-product');
		this.idProduct = $(this.formProduct[0].idProduct).val();

		this.form = $('#form-product-price-add');
		var form = this.form[0];
		this.selectProvider = $(form.idProvider);
		this.selectManufacturer = $(form.idManufacture);
		this.selectProductPackage = $(form.idProductPackage);
		this.selectProductType = $(form.idProductType);

		this.initForm();
		this.loadProduct();
		this.loadProviders();
		this.loadManufacturers();
		this.loadProductPackages();
		this.loadProductTypes();
	},
	initForm: function()
	{
		ws.productPrice_settings(this.form, this.onFormSettings);
	},
	loadProduct: function()
	{
		ws.product_get(this.idProduct, this.formProduct, this.onLoadProduct);
	},
	loadProviders: function()
	{
		ws.provider_getAll(this.selectProvider, this.onLoadProviders);
	},
	loadManufacturers: function()
	{
		ws.manufacturer_getAll(this.selectManufacturer, this.onLoadManufacturers);
	},
	loadProductPackages: function()
	{
		ws.productPackage_getAll(this.selectProductPackage, this.onLoadProductPackages);
	},
	loadProductTypes: function()
	{
		ws.productType_getAll(this.selectProductType, this.onLoadProductTypes);
	},
	onFormSettings: function(settings)
	{
		ProductPriceAdd.form.validate({
			'rules': {
				'idProvider': {
					'required': true,
				},
				'idManufacture': {
					'required': true,
				},
				'idProductPackage': {
					'required': true,
				},
				'idProductType': {
					'required': true,
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
					console.log(e.stack);
					alert(e.message);
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
		$('#row-message').show('slow');
		$('#row-message-span').html(message);

		ProductPriceAdd.form.trigger('reset');
		ProductPriceAdd.selectProvider.val('default');

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
	onLoadProduct: function(product)
	{
		var form = ProductPriceAdd.formProduct[0];
		$(form.name).val(product.name);
		$(form.unit).val(product.unit.name);
		$(form.description).html(product.description);

		$(ProductPriceAdd.form[0].name).val(product.name);
	},
	onLoadProviders: function(providers)
	{
		ProductPriceAdd.selectProvider.empty();
		ProductPriceAdd.selectProvider.append(Util.createElementOption('selecione um fornecedor', ''));

		ProductPriceAdd.providers = providers.elements;
		ProductPriceAdd.providers.forEach(function(provider)
		{
			var option = Util.createElementOption(provider.fantasyName, provider.id);
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
}
