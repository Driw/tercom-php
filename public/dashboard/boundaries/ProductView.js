
$(document).ready(function()
{
	ProductView.init();
});

var ProductView = ProductView ||
{
	init: function()
	{
		this.form = $('#form-product-view');
		this.idProduct = $(this.form[0].idProduct).val();
		this.loadProduct();
		this.initFormSettings();
	},
	initFormSettings: function()
	{
		ws.product_settings(this.form, ProductView.onSettings);
	},
	initSelects: function()
	{
		this.selectUnit = $(ProductView.form[0].idProductUnit);
		this.selectFamily = $(ProductView.form[0].idProductFamily);
		this.selectGroup = $(ProductView.form[0].idProductGroup);
		this.selectSubgroup = $(ProductView.form[0].idProductSubGroup);
		this.selectSector = $(ProductView.form[0].idProductSector);
		this.selectFamily.change(ProductView.onProductFamily);
		this.selectGroup.change(ProductView.onProductGroup);
		this.selectSubgroup.change(ProductView.onProductSubgroup);
		this.loadProductUnits();
		this.loadProductFamilies();
	},
	loadProduct: function()
	{
		ws.product_get(ProductView.idProduct, this.form, ProductView.onLoadProduct);
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
		ws.productGroup_getAll(idProductFamily, ProductView.selectGroup, ProductView.onLoadProductGroups);
	},
	loadProductSubgroups: function(idProductGroup)
	{
		ws.productSubgroup_getAll(idProductGroup, ProductView.selectSubgroup, ProductView.onLoadProductSubgroups);
	},
	loadProductSector: function(idProductSubgroup)
	{
		ws.productSector_getAll(idProductSubgroup, ProductView.selectSector, ProductView.onLoadProductSectores);
	},
	onLoadProduct: function(product)
	{
		ProductView.product = product;

		var form = ProductView.form[0];
		$(form.name).val(product.name);
		$(form.description).html(product.description);
		$(form.utility).html(product.utility);

		ProductView.initSelects();
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

		if (ProductView.product.unit.id !== 0)
			$(form.idProductUnit).val(ProductView.product.unit.id);
	},
	onLoadProductFamilies: function(productFamilies)
	{
		ProductView.onSubLoadCategory('Selecione uma família', ProductView.selectFamily, productFamilies.elements);
	},
	onLoadProductGroups: function(productFamily)
	{
		ProductView.onSubLoadCategory('Selecione um grupo', ProductView.selectGroup, productFamily.productGroups.elements);
	},
	onLoadProductSubgroups: function(productGroup)
	{
		ProductView.onSubLoadCategory('Selecione um subgrupo', ProductView.selectSubgroup, productGroup.productSubGroups.elements);
	},
	onLoadProductSectores: function(productSubgroup)
	{
		ProductView.onSubLoadCategory('Selecione um setor', ProductView.selectSector, productSubgroup.productSectores.elements);
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

		var form = ProductView.form[0];

		switch (select[0].name)
		{
			case 'idProductFamily':
				if (ProductView.product.category.family.id !== 0)
				{
					select.val(ProductView.product.category.family.id);
					select.change();
				}
				break;

			case 'idProductGroup':
				if (ProductView.product.category.group.id !== 0)
				{
					select.val(ProductView.product.category.group.id);
					select.change();
				}
				break;

			case 'idProductSubGroup':
				if (ProductView.product.category.subgroup.id !== 0)
				{
					select.val(ProductView.product.category.subgroup.id);
					select.change();
				}
				break;

			case 'idProductSector':
				if (ProductView.product.category.sector.id !== 0)
				{
					select.val(ProductView.product.category.sector.id);
					select.change();
				}
				break;
		}
	},
	onProductFamily: function()
	{
		var idProductFamily = this.options[this.selectedIndex].value;
		ProductView.selectGroup.prop('disabled', idProductFamily === '');
		ProductView.selectSubgroup.prop('disabled', true);
		ProductView.selectSector.prop('disabled', true);
		ProductView.selectGroup.empty();
		ProductView.selectSubgroup.empty();
		ProductView.selectSector.empty();

		if (idProductFamily !== '')
			ProductView.loadProductGroups(idProductFamily);
	},
	onProductGroup: function()
	{
		var idProductGroup = this.options[this.selectedIndex].value;
		ProductView.selectSubgroup.prop('disabled', idProductGroup === '');
		ProductView.selectSector.prop('disabled', true);
		ProductView.selectSubgroup.empty();
		ProductView.selectSector.empty();

		if (idProductGroup !== '')
			ProductView.loadProductSubgroups(idProductGroup);
	},
	onProductSubgroup: function()
	{
		var idProductSubgroup = this.options[this.selectedIndex].value;
		ProductView.selectSector.prop('disabled', idProductSubgroup === '');
		ProductView.selectSector.empty();

		if (idProductSubgroup !== '')
			ProductView.loadProductSector(idProductSubgroup);
	},
	submit: function() {
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
					console.log(e);
				}
				return false;
			}
		});
	},
	onSubmited: function(product, message) {
		$('#product-view-name').html(product.name);
		$('#product-view-message').html(message);
		$('#product-view-paragraph').show('slow');

		setTimeout(function() { $('#product-view-paragraph').hide('slow'); }, 3000);
	}
}
