
$(document).ready(function()
{
	ProductPriceView.init();
});

var ProductPriceView = ProductPriceView ||
{
	init: function()
	{
		this.formProduct = $('#form-product');
		this.form = $('#form-product-price-view');
		var form = this.form[0];
		this.selectProvider = $(form.idProvider);
		this.selectManufacturer = $(form.idManufacture);
		this.selectProductPackage = $(form.idProductPackage);
		this.selectProductType = $(form.idProductType);
		this.idProductPrice = $(form.idProductPrice).val();

		this.initForm();
		this.loadProductPrice();
		this.loadProviders();
		this.loadManufacturers();
		this.loadProductPackages();
		this.loadProductTypes();
	},
	initForm: function()
	{
		ws.productPrice_settings(this.form, this.onFormSettings);
	},
	loadProductPrice: function()
	{
		ws.productPrice_get(this.idProductPrice, this.form, this.onLoadProductPrice);
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
		ProductPriceView.form.validate({
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
		ProductPriceView.onLoadProductPrice(productPrice);

		$('#row-message').show('slow');
		$('#row-message-span').html(message);

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
	onLoadProductPrice: function(productPrice)
	{
		ProductPriceView.productPrice = productPrice;

		var form = ProductPriceView.form[0];
		$(form.name).val(productPrice.name);
		$(form.amount).val(productPrice.amount);
		$(form.price).val(Util.formatMoney(productPrice.price));
		$(form.idProvider).val(productPrice.provider.id).selectpicker('refresh');
		$(form.idManufacture).val(productPrice.manufacture.id).selectpicker('refresh');
		$(form.idProductPackage).val(productPrice['package'].id).selectpicker('refresh');
		$(form.idProductType).val(productPrice.productType.id).selectpicker('refresh');

		ws.product_get(productPrice.product.id, ProductPriceView.formProduct, ProductPriceView.onLoadProduct);
	},
	onLoadProduct: function(product)
	{
		var form = ProductPriceView.formProduct[0];
		$(form.name).val(product.name);
		$(form.unit).val(product.unit.name);
		$(form.description).html(product.description);
		$('#product-price-add').click(function()
		{
			Util.redirect('product/addPrice/' +product.id, true);
		});

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
			var option = Util.createElementOption(provider.fantasyName, provider.id, selected);
			ProductPriceView.selectProvider.append(option);
		});
		ProductPriceView.selectProvider.selectpicker('refresh');
	},
	onLoadManufacturers: function(manufacturers)
	{
		ProductPriceView.selectManufacturer.empty();
		ProductPriceView.selectManufacturer.append(Util.createElementOption('selecione um fabricante', ''));

		ProductPriceView.manufacturers = manufacturers.elements;
		ProductPriceView.manufacturers.forEach(function(manufacturer)
		{
			var selected = ProductPriceView.productPrice !== undefined && ProductPriceView.productPrice.manufacture.id === manufacturer.id;
			var option = Util.createElementOption(manufacturer.fantasyName, manufacturer.id, selected);
			ProductPriceView.selectManufacturer.append(option);
		});
		ProductPriceView.selectManufacturer.selectpicker('refresh');
	},
	onLoadProductPackages: function(productPackages)
	{
		ProductPriceView.selectProductPackage.empty();
		ProductPriceView.selectProductPackage.append(Util.createElementOption('selecione uma embalagem de produto', ''));

		ProductPriceView.productPackages = productPackages.elements;
		ProductPriceView.productPackages.forEach(function(productPackage)
		{
			var selected = ProductPriceView.productPrice !== undefined && ProductPriceView.productPrice['package'].id === productPackage.id;
			var option = Util.createElementOption(productPackage.name, productPackage.id, selected);
			ProductPriceView.selectProductPackage.append(option);
		});
		ProductPriceView.selectProductPackage.selectpicker('refresh');
	},
	onLoadProductTypes: function(productTypes)
	{
		ProductPriceView.selectProductType.empty();
		ProductPriceView.selectProductType.append(Util.createElementOption('selecione um tipo de produto', ''));

		ProductPriceView.productTypes = productTypes.elements;
		ProductPriceView.productTypes.forEach(function(productType)
		{
			var selected = ProductPriceView.productPrice !== undefined && ProductPriceView.productPrice.productType.id === productType.id;
			var option = Util.createElementOption(productType.name, productType.id, selected);
			ProductPriceView.selectProductType.append(option);
		});
		ProductPriceView.selectProductType.selectpicker('refresh');
	},
}
