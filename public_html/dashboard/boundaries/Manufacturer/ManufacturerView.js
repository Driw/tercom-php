
$(document).ready(function()
{
        ManufacturerView.init();
});

var ManufacturerView = ManufacturerView ||
{
    init: function()
    {
		this.form = $('#form-manufacturer-view');
		this.idManufacturer = $(this.form[0].idManufacturer).val();
		this.initForm();
		this.loadManufacturer();
    },
	initForm: function()
	{
		ws.manufacturer_settings(this.form, this.onFormSettings);
	},
	loadManufacturer: function()
	{
		ws.manufacturer_get(this.idManufacturer, this.form, this.onManufacturerLoaded);
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
					ManufacturerView.submit($(form));
				} catch (e) {
					console.log(e.stack);
					alert(e.message);
				}
				return false;
			},
		});
	},
	onManufacturerLoaded: function(manufacturer)
	{
		var form = ManufacturerView.form[0];
		$(form.fantasyName).val(manufacturer.fantasyName);
	},
    submit: function(form)
	{
		ws.manufacturer_set(ManufacturerView.idManufacturer, form, ManufacturerView.onSubmited);
    },
	onSubmited: function(manufacturer, message)
	{
		ManufacturerView.onManufacturerLoaded(manufacturer);

		$('#row-message').show('slow');
		$('#row-message-span').html(message);

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
}
