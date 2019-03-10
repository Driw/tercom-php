
$(document).ready(function()
{
		ManufacturerView.init();
});

var ManufacturerView = ManufacturerView ||
{
	init: function()
	{
		ManufacturerView.loaded = false;
		ManufacturerView.form = $('#form-manufacturer-view');
		ManufacturerView.idManufacturer = $(ManufacturerView.form[0].idManufacturer).val();
		ManufacturerView.initForm();
		ManufacturerView.loadManufacturer();
	},
	initForm: function()
	{
		ws.manufacturer_settings(ManufacturerView.form, ManufacturerView.onFormSettings);
	},
	loadManufacturer: function()
	{
		ws.manufacturer_get(ManufacturerView.idManufacturer, ManufacturerView.form, ManufacturerView.onManufacturerLoaded);
	},
	onFormSettings: function(settings)
	{
		ManufacturerView.form.validate({
			'rules': {
				'fantasyName': {
					'required': true,
					'rangelength': [ settings.minFantasyNameLen, settings.maxFantasyNameLen ],
					'remoteapi': {
						'webservice': 'manufacturer/avaiable/fantasyName/{value}/{idManufacturer}',
						'parameters': {
							'idManufacturer': ManufacturerView.idManufacturer,
						}
					},
				},
			},
			submitHandler: function(form) {
				try {
					ManufacturerView.confirm($(form));
				} catch (e) {
					ManufacturerView.showError(e.stack);
				}
				return false;
			},
		});
	},
	onManufacturerLoaded: function(manufacturer, message)
	{
		var form = ManufacturerView.form[0];
		$(form.fantasyName).val(manufacturer.fantasyName);

		if (ManufacturerView.loaded)
			Util.showInfo(message);
		else
			Util.showSuccess(message);

		ManufacturerView.loaded = true;
	},
	confirm: function()
	{
		var message = 'Uma vez que os dados do fabricante tenham sido atualizados não poderão ser revertidos. Deseja continuar?';
		Util.showConfirm('Atualizar dados do fabricante', message, ManufacturerView.submit);
	},
	submit: function(confirm)
	{
		if (confirm)
			ws.manufacturer_set(ManufacturerView.idManufacturer, ManufacturerView.form, ManufacturerView.onSubmited);
		return true;
	},
	onSubmited: function(manufacturer, message)
	{
		ManufacturerView.onManufacturerLoaded(manufacturer, message);
	},
}
