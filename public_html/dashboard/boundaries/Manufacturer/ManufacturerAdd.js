
$(document).ready(function()
{
		ManufacturerAdd.init();
});

var ManufacturerAdd = ManufacturerAdd ||
{
	init: function()
	{
		ManufacturerAdd.form = $('#form-manufacturer-add');
		ManufacturerAdd.initForm();
	},
	initForm: function()
	{
		ws.manufacturer_settings(ManufacturerAdd.form, ManufacturerAdd.onFormSettings);
	},
	onFormSettings: function(settings)
	{
		ManufacturerAdd.form.validate({
			'rules': {
				'fantasyName': {
					'required': true,
					'rangelength': [ settings.minFantasyNameLen, settings.maxFantasyNameLen ],
					'remoteapi': {
						'webservice': 'manufacturer/avaiable/fantasyName',
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
		message += ' <button class="btn btn-info btn-sm" onclick="ManufacturerAdd.onViewLast()">{0} Ver Fabricante</button>'.format(ICON_VIEW);
		ManufacturerAdd.lastAdded = manufacturer;
		ManufacturerAdd.form.trigger('reset');
		Util.showSuccess(message);
	},
	onViewLast: function()
	{
		if (ManufacturerAdd.lastAdded !== undefined)
			Util.redirect('manufacturer/view/{0}'.format(ManufacturerAdd.lastAdded.id));
	},
}
