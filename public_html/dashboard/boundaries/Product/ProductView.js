
$(document).ready(function()
{
	ProductView.init();
});

var ProductView = ProductView ||
{
	init: function()
	{
		ProductView.loaded = false;
		ProductView.form = $('#form-product-view');
		ProductView.idProduct = $(ProductView.form[0].idProduct).val();
		ProductView.loadProduct();
		ProductView.initFormSettings();
	},
	initFormSettings: function()
	{
		ws.product_settings(ProductView.form, ProductView.onSettings);
	},
	initSelects: function()
	{
		ProductView.selectUnit = $(ProductView.form[0].idProductUnit);
		ProductView.selectFamily = $(ProductView.form[0].idProductFamily);
		ProductView.selectGroup = $(ProductView.form[0].idProductGroup);
		ProductView.selectSubgroup = $(ProductView.form[0].idProductSubGroup);
		ProductView.selectSector = $(ProductView.form[0].idProductSector);
		ProductView.selectFamily.change(ProductView.onProductFamily);
		ProductView.selectGroup.change(ProductView.onProductGroup);
		ProductView.selectSubgroup.change(ProductView.onProductSubgroup);
		ProductView.selectSector.change(ProductView.onProductSector);
		ProductView.loadProductUnits();
		ProductView.loadProductFamilies();
	},
	loadProduct: function()
	{
		ws.product_get(ProductView.idProduct, ProductView.form, ProductView.onLoadProduct);
	},
	loadProductUnits: function()
	{
		ws.productUnit_getAll(ProductView.selectUnit, ProductView.onLoadProductUnits);
	},
	loadProductFamilies: function()
	{
		ws.productFamily_getAll(ProductView.selectFamily, ProductView.onLoadProductFamilies);
	},
	loadProductGroups: function(idProductFamily)
	{
		ws.productFamily_getCategories(idProductFamily, ProductView.selectGroup, ProductView.onLoadProductGroups);
	},
	loadProductSubgroups: function(idProductGroup)
	{
		ws.productGroup_getCategories(idProductGroup, ProductView.selectSubgroup, ProductView.onLoadProductSubgroups);
	},
	loadProductSector: function(idProductSubgroup)
	{
		ws.productSubgroup_getCategories(idProductSubgroup, ProductView.selectSector, ProductView.onLoadProductSectores);
	},
	onLoadProduct: function(product, message)
	{
		ProductView.product = product;

		var form = ProductView.form[0];
		$(form.name).val(product.name);
		$(form.description).html(product.description);
		$(form.utility).html(product.utility);

		ProductView.setProductCategoryReset();
		ProductView.initSelects();

		if (ProductView.loaded)
			Util.showSuccess(message);
		else
			Util.showInfo(message);

		ProductView.loaded = true;
	},
	onLoadProductUnits: function(productUnities)
	{
		var elements = productUnities.elements;
		var select = ProductView.selectUnit;
		select.empty();

		var option = Util.createElementOption('Selecione uma unidade', '');
		select.append(option);

		elements.forEach(function(element)
		{
			var option = Util.createElementOption(element.name+ ' (' +element.shortName+ ')', element.id);
			select.append(option);
		});

		var form = ProductView.form[0];

		if (ProductView.product.productUnit.id !== 0)
			$(form.idProductUnit).val(ProductView.product.productUnit.id);
	},
	onLoadProductFamilies: function(productFamilies)
	{
		ProductView.onSubLoadCategory('Sem categoria', ProductView.selectFamily, productFamilies.elements);
	},
	onLoadProductGroups: function(productFamily)
	{
		ProductView.onSubLoadCategory('Selecione um grupo', ProductView.selectGroup, productFamily.productCategories.elements);
	},
	onLoadProductSubgroups: function(productGroup)
	{
		ProductView.onSubLoadCategory('Selecione um subgrupo', ProductView.selectSubgroup, productGroup.productCategories.elements);
	},
	onLoadProductSectores: function(productSubgroup)
	{
		ProductView.onSubLoadCategory('Selecione um setor', ProductView.selectSector, productSubgroup.productCategories.elements);
	},
	onSubLoadCategory: function(emptyMessage, select, elements)
	{
		select.empty();

		var option = Util.createElementOption(emptyMessage, '0');
		var containsCategory = false;
		select.append(option);

		elements.forEach(function(element)
		{
			var option = Util.createElementOption(element.name, element.id);
			option[0].dataset.type = element.type;
			select.append(option);

			if (ProductView.product.productCategory !== null && ProductView.product.productCategory.id === element.id)
				containsCategory = true;
		});

		if (containsCategory)
		{
			select.val(ProductView.product.productCategory.id);
			select.change();
		}
	},
	setProductCategory: function(name, idProductCategory, idProductCategoryType)
	{
		var form = ProductView.form[0];
		$(form.productCategory).val(name);
		$(form.idProductCategory).val(idProductCategory);
		$(form.idProductCategoryType).val(idProductCategoryType);
	},
	setProductCategoryReset: function()
	{
		ProductView.setProductCategory(ProductView.product.productCategory !== null ? ProductView.product.productCategory.name : '-', 0, 0);
	},
	onProductFamily: function()
	{
		var option = this.options[this.selectedIndex];
		var idProductFamily = option.value;
		ProductView.selectGroup.prop('disabled', idProductFamily === '');
		ProductView.selectSubgroup.prop('disabled', true);
		ProductView.selectSector.prop('disabled', true);
		ProductView.selectGroup.empty();
		ProductView.selectSubgroup.empty();
		ProductView.selectSector.empty();

		if (idProductFamily != 0)
		{
			ProductView.loadProductGroups(idProductFamily);
			ProductView.setProductCategory($(option).html(), $(option).val(), option.dataset.type);
		}

		else
		{
			ProductView.setProductCategory('', '', '');
			ProductView.selectGroup.prop('disabled', true);
		}
	},
	onProductGroup: function()
	{
		var option = this.options[this.selectedIndex];
		var idProductGroup = option.value;
		ProductView.selectSubgroup.prop('disabled', idProductGroup === '');
		ProductView.selectSector.prop('disabled', true);
		ProductView.selectSubgroup.empty();
		ProductView.selectSector.empty();

		if (idProductGroup != 0)
		{
			ProductView.loadProductSubgroups(idProductGroup);
			ProductView.setProductCategory($(option).html(), $(option).val(), option.dataset.type);
		}

		else
		{
			ProductView.selectSubgroup.prop('disabled', true);
			var option = ProductView.selectFamily.find(':selected');
			ProductView.setProductCategory(
				option.html(),
				option.val(),
				option.data('type'),
			);
		}
	},
	onProductSubgroup: function()
	{
		var option = this.options[this.selectedIndex];
		var idProductSubgroup = option.value;
		ProductView.selectSector.prop('disabled', idProductSubgroup === '');
		ProductView.selectSector.empty();

		if (idProductSubgroup != 0)
		{
			ProductView.loadProductSector(idProductSubgroup);
			ProductView.setProductCategory($(option).html(), $(option).val(), option.dataset.type);
		}

		else
		{
			ProductView.selectSector.prop('disabled', true);
			var option = ProductView.selectGroup.find(':selected');
			ProductView.setProductCategory(
				option.html(),
				option.val(),
				option.data('type'),
			);
		}
	},
	onProductSector: function()
	{
		var option = this.options[this.selectedIndex];
		var idProductSector = option.value;

		if (idProductSector != 0)
			ProductView.setProductCategory($(option).html(), $(option).val(), option.dataset.type);
		else
		{
			var option = ProductView.selectSubgroup.find(':selected');
			ProductView.setProductCategory(
				option.html(),
				option.val(),
				option.data('type'),
			);
		}
	},
	onButtonViewPrices: function()
	{
		Util.redirect('product/viewPrices/{0}'.format(ProductView.idProduct), true);
	},
	submit: function()
	{
		ws.product_set(ProductView.idProduct, ProductView.form, ProductView.onSubmited);
	},
	onSettings: function(settings)
	{
		ProductView.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
					'remoteapi': {
						'webservice': 'product/avaiable/name/{value}/{idProduct}',
						'parameters': {
							'idProduct': ProductView.idProduct,
						}
					}
				},
				'description': {
					'required': true,
					'rangelength': [ settings.minDescriptionLen, settings.maxDescriptionLen ]
				},
				'utility': {
					'required': false,
					'maxlength': settings.maxUtilityLen,
				},
				'idProductUnit': {
					'required': true,
				},
			},
			submitHandler: function(form) {
				try {
					ProductView.submit();
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			}
		});
	},
	onSubmited: function(product, message)
	{
		ProductView.onLoadProduct(product, message);
	}
}
