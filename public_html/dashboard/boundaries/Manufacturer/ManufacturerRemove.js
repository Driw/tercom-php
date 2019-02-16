
$(document).ready(function()
{
		ManufacturerRemove.init();
});

var ManufacturerRemove = ManufacturerRemove ||
{
	init: function()
	{
		ManufacturerRemove.form = $('#form-manufacturer-remove');
		ManufacturerRemove.idManufacturer = $(ManufacturerRemove.form[0].idManufacturer).val();
		ManufacturerRemove.initForm();
		ManufacturerRemove.loadManufacturer();
	},
	initForm: function()
	{
		ManufacturerRemove.form.validate({
			submitHandler: function(form) {
				try {
					ManufacturerRemove.submit($(form));
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			},
		});
	},
	loadManufacturer: function()
	{
		ws.manufacturer_get(ManufacturerRemove.idManufacturer, ManufacturerRemove.form, ManufacturerRemove.onManufacturerLoaded);
	},
	onManufacturerLoaded: function(manufacturer)
	{
		var form = ManufacturerRemove.form[0];
		$(form.fantasyName).val(manufacturer.fantasyName);
	},
	submit: function(form)
	{
		ws.manufacturer_remove(ManufacturerRemove.idManufacturer, form, ManufacturerRemove.onSubmited);
	},
	onSubmited: function(manufacturer, message)
	{
		ManufacturerRemove.form.trigger('reset');
		ManufacturerRemove.form.hide('slow');
		Util.showSuccess(message);
	},
}
