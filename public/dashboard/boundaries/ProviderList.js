
$(document).ready(function()
{
	var providers = [];
	var table = $('#providers-table');
	var tbody = table.find('tbody');
	var dataTable = newDataTables(table);

	loadProviders();

	function loadProviders()
	{
		$.ajax({
			'type': 'post',
			'url': API_ENDPOINT+ 'provider/list/all',
			'contentType': 'application/json; charset=utf8',
			'cache': 'false',
			'beforeSend': function() {
				tbody.LoadingOverlay('show');
			},
			'error': function() {
				showModalApiError('não foi possível obter a lista de fornecedores');
			},
			'success': function(response) {
				if (apiNeedShowErrorModal(response))
					return;
				var result = response.result;
				var elements = result.providers.attributes.elements;

				elements.forEach(function(provider, index) {
					var provider = parseAdvancedObject(provider);
					var id = provider.id;
					providers[provider.id] = provider;

					var siteButton = '<button type="button" class="btn btn-info btn-site" data-id="' +id+ '">Site</button>';
					var openButton = '<button type="button" class="btn btn-primary btn-see" data-id="' +id+ '">Ver</button>';
					var row = dataTable.row.add([
						provider.id,
						provider.cnpj,
						provider.companyName,
						provider.fantasyName,
						provider.spokesman,
						'<div class="btn-group">' +siteButton+openButton+ '</div>',
					]).draw();
				});

				$('.btn-site').click(function() {
					var provider = providers[this.dataset.id];
					window.open('http://' +provider.site, '_blank');
				});

				$('.btn-see').click(function() {
					var provider = providers[this.dataset.id];
					window.open('provider/view/' +provider.id, '_blank');
				});
			},
			'complete': function() {
				tbody.LoadingOverlay('hide');
			}
		});
	}
});
