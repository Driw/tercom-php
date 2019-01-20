
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
					console.log(e.stack);
					alert(e.message);
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
		ManufacturerRemove.onManufacturerLoaded(manufacturer);

		$('#row-message').show('slow');
		$('#row-message-span').html(message);
		ManufacturerRemove.form.fadeOut();

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
}
