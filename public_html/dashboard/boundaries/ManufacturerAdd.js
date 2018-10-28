
$(document).ready(function()
{
        ManufacturerAdd.init();
});

var ManufacturerAdd = ManufacturerAdd ||
{
    init: function()
    {
        this.form = $('#form-manufacturer-add');
		this.initForm();
    },
	initForm: function()
	{
		ws.manufacturer_settings(this.form, this.onFormSettings);
	},
	onFormSettings: function(settings)
	{
		ManufacturerAdd.form.validate({
			'rules': {
				'fantasyName': {
					'required': true,
					'rangelength': [ settings.minFantasyNameLen, settings.maxFantasyNameLen ],
					'remoteapi': {
						'webservice': 'manufacture/avaiable/fantasyName',
					},
				},
			},
			submitHandler: function(form) {
				try {
					ManufacturerAdd.submit($(form));
				} catch (e) {
					console.log(e.stack);
					alert(e.message);
				}
				return false;
			},
		});
	},
    submit: function()
	{
		ws.manufacturer_add(ManufacturerAdd.form, ManufacturerAdd.onSubmited);
    },
	onSubmited: function(manufacturer, message)
	{
		$('#row-message').show('slow');
		$('#row-message-span').html(message);

		ManufacturerAdd.form.trigger('reset');

		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	},
}
