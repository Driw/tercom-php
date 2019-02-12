
$(document).ready(function()
{
	ProviderAdd.init();
});

var ProviderAdd = ProviderAdd ||
{
	init: function()
	{
		this.form = $('#form-provider-add');
		this.initFormSettings();
	},
	initFormSettings: function()
	{
		ws.provider_settings(this.form, this.onFormSettings);
	},
	onFormSettings: function(settings)
	{
		ProviderAdd.form.validate({
			'rules': {
				'cnpj': {
					'required': true,
					'remoteapi': {
						'webservice': 'provider/avaiable/cnpj',
						'replacePattern': [ /\D/g, '' ],
					},
				},
				'companyName': {
					'required': true,
					'rangelength': [ settings.minCompanyNameLen, settings.maxCompanyNameLen ],
				},
				'fantasyName': {
					'required': true,
					'rangelength': [ settings.minFantasyNameLen, settings.maxFantasyNameLen ],
				},
				'spokesman': {
					'required': false,
					'rangelength': [ settings.minSpokesmanLen, settings.maxSpokesmanLen ],
				},
				'site': {
					'required': false,
					'maxlength': settings.maxSiteLen,
				},
			},
			submitHandler: function(form) {
				try {
					ProviderAdd.submit($(form));
				} catch (e) {
					console.log(e.stack);
					alert(e.message);
				}
				return false;
			}
		});
	},
	submit: function(form)
	{
		ws.provider_add(this.form, ProviderAdd.onSubmited);
	},
	onSubmited: function(provider, message)
	{
		ProviderAdd.lastProvider = provider;
		message = '<b>{0}</b> <button class="btn btn-sm btn-info" onclick="ProviderAdd.onViewProvider()">Ver Fornecedor</button>'.format(provider.fantasyName);
		Util.showSuccess(message, 15000);
	},
	onViewProvider: function()
	{
		if (ProviderAdd.lastProvider !== undefined)
			Util.redirect('provider/view/' +ProviderAdd.lastProvider.id);
	}
}
