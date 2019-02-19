
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
					Util.showError(e.stack);
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
		message += ' <button class="btn btn-sm {0}" onclick="TercomProfileAdd.onButtonLast()">{1} Ver Serviço</button>'.format(BTN_CLASS_VIEW, ICON_VIEW);
		TercomProfileAdd.lastAdded = tercomProfile;
		TercomProfileAdd.form.trigger('reset');
		Util.showSuccess(message);
	},
	onButtonLast: function()
	{
		Util.redirect('tercomProfile/view/{0}'.format(TercomProfileAdd.lastAdded.id));
	},
}
