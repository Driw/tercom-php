
$(document).ready(function()
{
		TercomProfileView.init();
});

var TercomProfileView = TercomProfileView ||
{
	init: function()
	{
		TercomProfileView.loaded = false;
		TercomProfileView.form = $('#form-tercom-profile-view');
		TercomProfileView.id = $(this.form[0].idTercomProfile).val();
		TercomProfileView.initFormSettings();
		TercomProfileView.loadTercomProfile();
	},
	initFormSettings: function()
	{
		ws.tercomProfile_settings(this.form, TercomProfileView.onSettings);
	},
	loadTercomProfile: function()
	{
		ws.tercomProfile_get(this.id, this.form, this.onTercomProfileLoaded);
	},
	onSettings: function(settings)
	{
		TercomProfileView.form.validate({
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
					TercomProfileView.submit($(form));
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			}
		});
	},
	onTercomProfileLoaded: function(tercomProfile, message)
	{
		var form = TercomProfileView.form[0];
		$(form.name).val(tercomProfile.name);
		$(form.assignmentLevel).val(tercomProfile.assignmentLevel);

		if (TercomProfileView.loaded)
			Util.showSuccess(message);
		else
			Util.showInfo(message);

		TercomProfileView.loaded = true;
	},
	submit: function(form)
	{
		ws.tercomProfile_set(TercomProfileView.id, TercomProfileView.form, TercomProfileView.onSubmited);
	},
	onSubmited: function(tercomProfile, message)
	{
		TercomProfileView.onTercomProfileLoaded(tercomProfile, message);
	}
}
