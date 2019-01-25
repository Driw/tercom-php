
$(document).ready(function()
{
		TercomProfileAdd.init();
});

var TercomProfileAdd = TercomProfileAdd ||
{
	init: function()
	{
		TercomProfileAdd.form = $('#form-tercom-profile-add');
		TercomProfileAdd.initFormSettings();
	},
	initFormSettings: function()
	{
		ws.tercomProfile_settings(TercomProfileAdd.form, TercomProfileAdd.onSettings);
	},
	onSettings: function(settings)
	{
		TercomProfileAdd.form.validate({
			'rules': {
				'name': {
					'required': true,
					'rangelength': [ settings.minNameLen, settings.maxNameLen ],
				},
				'assignmentLevel': {
					'required': true,
					'range': [ settings.minAssignmentLevel, settings.maxAssignmentLevel ],
				},
			},
			submitHandler: function(form) {
				try {
					TercomProfileAdd.submit($(form));
				} catch (e) {
					console.log(e);
				}
				return false;
			}
		});
	},
	submit: function(form)
	{
		ws.tercomProfile_add(form, TercomProfileAdd.onSubmited);
	},
	onSubmited: function(tercomProfile, message)
	{
		TercomProfileAdd.form.trigger('reset');

		$('#row-message').show(DEFAULT_FADE);
		$('#row-message-span').html(message);
		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	}
}
