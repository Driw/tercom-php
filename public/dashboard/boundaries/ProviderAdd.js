
$(document).ready(function()
{
	Controller.init();
	Controller.setValidation();

	$('#controller-add').click(function() { Controller.addProvider(); });
	$('#controller-view').click(function() { Controller.viewProvider(); });
});

var Controller = Controller ||
{
	init: function()
	{
		this.form = $('#form-view');
		this.provider = {};
	},
	setValidation: function()
	{
		var controller = this;

		$.ajax({
			'url': API_ENDPOINT+ 'provider/settings',
			'type': 'POST',
			'dataType': 'json',
			'processData': false,
			'beforeSend': function() {
				controller.form.LoadingOverlay('show');
			},
			'error': function() {
				showModalApiError('não foi possível obter as validações para fornecedores');
			},
			'success': function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var result = response.result;
				var settings = parseAdvancedObject(response.result);

				controller.form.validate({
					'rules': {
						'cnpj': {
							'required': true,
							'remoteapi': {
								'webservice': 'provider/validate/cnpj',
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
						}
					}
				});
			},
			'complete': function() {
				controller.form.LoadingOverlay('hide');
			}
		});
	},
	setCurrentProvider: function(provider, message)
	{
		this.provider = provider;
		console.log(provider);

		$('#formLastProviderName').html(provider.fantasyName);
		$('#formLastProviderParagraph').fadeIn('slow');
		$('#formConfirmModal').modal('show');
		$('#formConfirmModalLabel').html(provider.fantasyName);
		$('#formConfirmModalMessage').html(message);
	},
	addProvider: function()
	{
		var controller = this;
		var formData = this.form.serialize();

		$.ajax({
			'url': API_ENDPOINT+ 'provider/add',
			'data': formData,
			'type': 'POST',
			'dataType': 'json',
			'processData': false,
			'beforeSend': function() {
				controller.form.LoadingOverlay('show');
			},
			'error': function() {
				showModalApiError('não foi possível adicionar o fornecedor');
			},
			'success': function(response) {
				if (apiNeedShowErrorModal(response))
					return;

				var result = response.result;
				var provider = parseAdvancedObject(response.result);
				controller.setCurrentProvider(provider, result.message);
			},
			'complete': function() {
				controller.form.LoadingOverlay('hide');
			}
		});
	},
	viewProvider: function()
	{
		if (this.provider.id === undefined)
			return; // FIXME Exibir um modal?

		window.location.replace('provider/view/' +this.provider.id);
	}
}
