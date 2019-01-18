
$(document).ready(function()
{
	ProductAdd.init();
});

var ProductAdd = ProductAdd ||
{
	init: function()
	{
		this.form = $('#form-product-add');
		this.initFormSettings();
		this.initSelects();
		this.initModal();
	},
	initFormSettings: function()
	{
		ws.product_settings(this.form, ProductAdd.onSettings);
	},
	initSelects: function()
	{
		this.selectUnit = $(ProductAdd.form[0].idProductUnit);
		this.selectFamily = $(ProductAdd.form[0].idProductFamily);
		this.selectGroup = $(ProductAdd.form[0].idProductGroup);
		this.selectSubgroup = $(ProductAdd.form[0].idProductSubGroup);
		this.selectSector = $(ProductAdd.form[0].idProductSector);
		this.selectFamily.change(ProductAdd.onProductFamily);
		this.selectGroup.change(ProductAdd.onProductGroup);
		this.selectSubgroup.change(ProductAdd.onProductSubgroup);
		this.loadProductUnits();
		this.loadProductFamilies();
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
		ProductAdd.onSubLoadCategory('Selecione um grupo', ProductAdd.selectGroup, productFamily.productGroups.elements);
	},
	onLoadProductSubgroups: function(productGroup)
	{
		ProductAdd.onSubLoadCategory('Selecione um subgrupo', ProductAdd.selectSubgroup, productGroup.productSubGroups.elements);
	},
	onLoadProductSectores: function(productSubgroup)
	{
		ProductAdd.onSubLoadCategory('Selecione um setor', ProductAdd.selectSector, productSubgroup.productSectores.elements);
	},
	onSubLoadCategory: function(emptyMessage, select, elements)
	{
		select.empty();

		var option = Util.createElementOption(emptyMessage, '0');
		select.append(option);

		elements.forEach(function(element)
		{
			var option = Util.createElementOption(element.name, element.id);
			select.append(option);
		});
	},
	onProductFamily: function()
	{
		var idProductFamily = this.options[this.selectedIndex].value;
		ProductAdd.selectGroup.prop('disabled', idProductFamily === '');
		ProductAdd.selectSubgroup.prop('disabled', true);
		ProductAdd.selectSector.prop('disabled', true);
		ProductAdd.selectGroup.empty();
		ProductAdd.selectSubgroup.empty();
		ProductAdd.selectSector.empty();

		if (idProductFamily !== '')
			ProductAdd.loadProductGroups(idProductFamily);
	},
	onProductGroup: function()
	{
		var idProductGroup = this.options[this.selectedIndex].value;
		ProductAdd.selectSubgroup.prop('disabled', idProductGroup === '');
		ProductAdd.selectSector.prop('disabled', true);
		ProductAdd.selectSubgroup.empty();
		ProductAdd.selectSector.empty();

		if (idProductGroup !== '')
			ProductAdd.loadProductSubgroups(idProductGroup);
	},
	onProductSubgroup: function()
	{
		var idProductSubgroup = this.options[this.selectedIndex].value;
		ProductAdd.selectSector.prop('disabled', idProductSubgroup === '');
		ProductAdd.selectSector.empty();

		if (idProductSubgroup !== '')
			ProductAdd.loadProductSector(idProductSubgroup);
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
			Util.redirect('product/view/' +ProductAdd.lastProduct.id);
	}
};
