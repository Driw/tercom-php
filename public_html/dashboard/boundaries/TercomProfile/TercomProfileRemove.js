
$(document).ready(function()
{
		TercomProfileRemove.init();
});

var TercomProfileRemove = TercomProfileRemove ||
{
	init: function()
	{
		TercomProfileRemove.form = $('#form-tercom-profile-view');
		TercomProfileRemove.id = $(this.form[0].idTercomProfile).val();
		TercomProfileRemove.initFormSettings();
		TercomProfileRemove.loadTercomProfile();
	},
	initFormSettings: function()
	{
		TercomProfileRemove.form.validate({
			submitHandler: function(form) {
				try {
					TercomProfileRemove.submit($(form));
				} catch (e) {
					Util.showError(e.stack);
				}
				return false;
			}
		});
	},
	loadTercomProfile: function()
	{
		ws.tercomProfile_get(this.id, this.form, this.onTercomProfileLoaded);
	},
	onTercomProfileLoaded: function(tercomProfile)
	{
		var form = TercomProfileRemove.form[0];
		$(form.name).val(tercomProfile.name);
		$(form.assignmentLevel).val(tercomProfile.assignmentLevel);
	},
	submit: function(form)
	{
		ws.tercomProfile_remove(TercomProfileRemove.id, TercomProfileRemove.form, TercomProfileRemove.onSubmited);
	},
	onSubmited: function(tercomProfile, message)
	{
		TercomProfileRemove.form.trigger('reset');
		TercomProfileRemove.form.hide('slow');
		Util.showSuccess(message);
	}
}
