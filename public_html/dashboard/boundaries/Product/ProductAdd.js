
$(document).ready(function()
{
	ProductAdd.init();
});

var ProductAdd = ProductAdd ||
{
	init: function()
	{
		ProductAdd.form = $('#form-product-add');
		ProductAdd.initFormSettings();
		ProductAdd.initSelects();
		ProductAdd.initModal();
	},
	initFormSettings: function()
	{
		ws.product_settings(ProductAdd.form, ProductAdd.onSettings);
	},
	initSelects: function()
	{
		ProductAdd.selectUnit = $(ProductAdd.form[0].idProductUnit);
		ProductAdd.selectFamily = $(ProductAdd.form[0].idProductFamily);
		ProductAdd.selectGroup = $(ProductAdd.form[0].idProductGroup);
		ProductAdd.selectSubgroup = $(ProductAdd.form[0].idProductSubGroup);
		ProductAdd.selectSector = $(ProductAdd.form[0].idProductSector);
		ProductAdd.selectFamily.change(ProductAdd.onProductFamily);
		ProductAdd.selectGroup.change(ProductAdd.onProductGroup);
		ProductAdd.selectSubgroup.change(ProductAdd.onProductSubgroup);
		ProductAdd.selectSector.change(ProductAdd.onProductSector);
		ProductAdd.loadProductUnits();
		ProductAdd.loadProductFamilies();
	},
	initModal: function()
	{
		$('#modal-product-add-view').click(ProductAdd.onProductView);
	},
	loadProductUnits: function()
	{
		ws.productUnit_getAll(ProductAdd.selectUnit, ProductAdd.onLoadProductUnits);
	},
	loadProductFamilies: function()
	{
		ws.productFamily_getAll(ProductAdd.selectFamily, ProductAdd.onLoadProductFamilies);
	},
	loadProductGroups: function(idProductFamily)
	{
		ws.productGroup_getAll(idProductFamily, ProductAdd.selectGroup, ProductAdd.onLoadProductGroups);
	},
	loadProductSubgroups: function(idProductGroup)
	{
		ws.productSubgroup_getAll(idProductGroup, ProductAdd.selectSubgroup, ProductAdd.onLoadProductSubgroups);
	},
	loadProductSector: function(idProductSubgroup)
	{
		ws.productSector_getAll(idProductSubgroup, ProductAdd.selectSector, ProductAdd.onLoadProductSectores);
	},
	onLoadProductUnits: function(productUnities)
	{
		var elements = productUnities.elements;
		var select = ProductAdd.selectUnit;
		select.empty();

		var option = Util.createElementOption('Selecione uma unidade', '');
		select.append(option);

		elements.forEach(function(element)
		{
			var option = Util.createElementOption(element.name+ ' (' +element.shortName+ ')', element.id);
			select.append(option);
		});
	},
	onLoadProductFamilies: function(productFamilies)
	{
		ProductAdd.onSubLoadCategory('Selecione uma família', ProductAdd.selectFamily, productFamilies.elements);
	},
	onLoadProductGroups: function(productFamily)
	{
		ProductAdd.onSubLoadCategory('Selecione um grupo', ProductAdd.selectGroup, productFamily.productCategories.elements);
	},
	onLoadProductSubgroups: function(productGroup)
	{
		ProductAdd.onSubLoadCategory('Selecione um subgrupo', ProductAdd.selectSubgroup, productGroup.productCategories.elements);
	},
	onLoadProductSectores: function(productSubgroup)
	{
		ProductAdd.onSubLoadCategory('Selecione um setor', ProductAdd.selectSector, productSubgroup.productCategories.elements);
	},
	onSubLoadCategory: function(emptyMessage, select, elements)
	{
		select.empty();

		var option = Util.createElementOption(emptyMessage, '0');
		select.append(option);

		elements.forEach(function(element)
		{
			var option = Util.createElementOption(element.name, element.id);
			option[0].dataset.type = element.type;
			select.append(option);
		});
	},
	setProductCategory: function(name, idProductCategory, idProductCategoryType)
	{
		var form = ProductAdd.form[0];
		$(form.productCategory).val(name);
		$(form.idProductCategory).val(idProductCategory);
		$(form.idProductCategoryType).val(idProductCategoryType);
	},
	onProductFamily: function()
	{
		var option = this.options[this.selectedIndex];
		var idProductFamily = option.value;
		ProductAdd.selectGroup.prop('disabled', idProductFamily === '');
		ProductAdd.selectSubgroup.prop('disabled', true);
		ProductAdd.selectSector.prop('disabled', true);
		ProductAdd.selectGroup.empty();
		ProductAdd.selectSubgroup.empty();
		ProductAdd.selectSector.empty();

		if (idProductFamily != 0)
		{
			ProductAdd.loadProductGroups(idProductFamily);	
			ProductAdd.setProductCategory($(option).html(), $(option).val(), option.dataset.type);
		}

		else
		{
			ProductAdd.setProductCategory('', '', '');
			ProductAdd.selectGroup.prop('disabled', true);
		}
	},
	onProductGroup: function()
	{
		var option = this.options[this.selectedIndex];
		var idProductGroup = option.value;
		ProductAdd.selectSubgroup.prop('disabled', idProductGroup === '');
		ProductAdd.selectSector.prop('disabled', true);
		ProductAdd.selectSubgroup.empty();
		ProductAdd.selectSector.empty();

		if (idProductGroup != 0)
		{
			ProductAdd.loadProductSubgroups(idProductGroup);
			ProductAdd.setProductCategory($(option).html(), $(option).val(), option.dataset.type);
		}

		else
		{
			ProductAdd.selectSubgroup.prop('disabled', true);
			var option = ProductAdd.selectFamily.find(':selected');
			ProductAdd.setProductCategory(
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
		ProductAdd.selectSector.prop('disabled', idProductSubgroup === '');
		ProductAdd.selectSector.empty();

		if (idProductSubgroup !== '')
		{
			ProductAdd.loadProductSector(idProductSubgroup);
			ProductAdd.setProductCategory($(option).html(), $(option).val(), option.dataset.type);
		}

		else
		{
			ProductAdd.selectSector.prop('disabled', true);
			var option = ProductAdd.selectGroup.find(':selected');
			ProductAdd.setProductCategory(
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
		ProductAdd.setProductCategory($(option).html(), $(option).val(), option.dataset.type);
		else
		{
			var option = ProductAdd.selectSubgroup.find(':selected');
			ProductAdd.setProductCategory(
				option.html(),
				option.val(),
				option.data('type'),
			);
		}
	},
	submit: function() {
		ws.product_add(ProductAdd.form, ProductAdd.onSubmited);
	},
	onSettings: function(settings)
	{
		ProductAdd.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
					'remoteapi': {
						'webservice': 'product/avaiable/name'
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
					ProductAdd.submit();
				} catch (e) {
					console.log(e);
				}
				return false;
			}
		});
	},
	onSubmited: function(product, response) {
		ProductAdd.lastProduct = product;
		ProductAdd.form.trigger('reset');

		$('#product-add-message').html(product.name);
		$('#product-add-paragraph').show('slow');

		$('#modal-product-add-label').html(product.name);
		$('#modal-product-add-message').html(response.message);
		$('#modal-product-add').modal('show');
	},
	onProductView: function()
	{
		if (ProductAdd.lastProduct !== undefined)
			Util.redirect('product/view/{0}'.format(ProductAdd.lastProduct.id));
	}
};
