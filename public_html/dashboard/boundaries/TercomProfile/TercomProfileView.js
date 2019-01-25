
$(document).ready(function()
{
		TercomProfileView.init();
});

var TercomProfileView = TercomProfileView ||
{
	init: function()
	{
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
					console.log(e);
				}
				return false;
			}
		});
	},
	onTercomProfileLoaded: function(tercomProfile)
	{
		var form = TercomProfileView.form[0];
		$(form.name).val(tercomProfile.name);
		$(form.assignmentLevel).val(tercomProfile.assignmentLevel);
	},
	submit: function(form)
	{
		ws.tercomProfile_set(TercomProfileView.id, TercomProfileView.form, TercomProfileView.onSubmited);
	},
	onSubmited: function(tercomProfile, message)
	{
		TercomProfileView.onTercomProfileLoaded(tercomProfile);

		$('#row-message').show(DEFAULT_FADE);
		$('#row-message-span').html(message);
		setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
	}
}
