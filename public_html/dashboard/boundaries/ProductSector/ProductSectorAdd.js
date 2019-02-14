
$(document).ready(function()
{
	ProductSectorAdd.init();
});

var ProductSectorAdd = ProductSectorAdd ||
{
	init: function()
	{
		ProductSectorAdd.form = $('#form-product-sector-add');
		ProductSectorAdd.idProductSubgroup = $('#idProductSubgroup').val();
		ProductSectorAdd.selectSubgroup = $(ProductSectorAdd.form[0].idProductSubgroup);
		ProductSectorAdd.initForm();
		ProductSectorAdd.loadProductSubgroups();
	},
	initForm: function()
	{
		ws.productSector_settings(ProductSectorAdd.form, ProductSectorAdd.onFormSettings);
	},
	loadProductSubgroups: function()
	{
		ws.productSubgroup_getAll(ProductSectorAdd.selectSubgroup, ProductSectorAdd.onProductSubgroupsLoaded);
	},
	onFormSettings: function(settings)
	{
		ProductSectorAdd.form.validate({
			'rules': {
				'idProductSector': {
					'required': true,
				},
			},
			submitHandler: function(form) {
				try {
					ProductSectorAdd.submit($(form));
				} catch (e) {
					console.log(e.stack);
					alert(e.message);
				}
				return false;
			},
		});
	},
	onProductSubgroupsLoaded: function(productSubgroups)
	{
		ProductSectorAdd.selectSubgroup.selectpicker();
		ProductSectorAdd.productSubgroups = productSubgroups.elements;
		ProductSectorAdd.productSubgroups.forEach(productSubgroup =>
		{
			var selected = ProductSectorAdd.idProductSubgroup == productSubgroup.id;
			var option = Util.createElementOption(productSubgroup.name, productSubgroup.id, selected);
			ProductSectorAdd.selectSubgroup.append(option);
		});
		ProductSectorAdd.selectSubgroup.selectpicker('refresh');
	},
	submit: function(form)
	{
		ws.productSector_add(form, ProductSectorAdd.onSubmited);
	},
	onSubmited: function(productCategory, message)
	{
		message += ' <button class="btn btn-sm {}" onclick="ProductSectorAdd.onButtonLast()">{1} Ver Setor</button>'.format(BTN_CLASS_VIEW, ICON_VIEW);
		ProductSectorAdd.lastAdded = productCategory;
		ProductSectorAdd.form.trigger('reset');
		Util.showSuccess(message);
	},
	onButtonLast: function()
	{
		Util.redirect('productSector/view/{0}'.format(ProductSectorAdd.lastAdded.id));
	}
}
