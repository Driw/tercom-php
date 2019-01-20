
$(document).ready(function()
{
	ProviderSearch.init();
	ProviderSearch.form.submit(function() {
		try { ProviderSearch.searchProvider(); } catch (e) { alert(e.message); console.error("%O", e); }
		return false;
	});
});

var ProviderSearch = ProviderSearch ||
{
	init: function()
	{
		ProviderSearch.providers = [];
		ProviderSearch.form = $('#form-search');
		ProviderSearch.table = $('#providers-table');
		ProviderSearch.tbody = ProviderSearch.table.find('tbody');
		ProviderSearch.dataTable = newDataTables(ProviderSearch.table);
	},
	searchProvider: function()
	{
		var filter = ProviderSearch.form[0].filter.value;
		var value = ProviderSearch.form[0].value.value;
		ws.provider_search(filter, value, ProviderSearch.tbody, ProviderSearch.onSearchProvider)
	},
	onSearchProvider: function(providers)
	{
		ProviderSearch.dataTable.clear();
		ProviderSearch.providers = providers.elements;
		ProviderSearch.providers.forEach(function(provider, index)
		{
			var id = provider.id;
			ProviderSearch.providers[provider.id] = provider;

			var siteButton = '<button type="button" class="btn btn-info btn-site" data-id="' +id+ '">Site</button>';
			var openButton = '<button type="button" class="btn btn-primary btn-see" data-id="' +id+ '">Ver</button>';
			ProviderSearch.dataTable.row.add([
				provider.id,
				provider.cnpj,
				provider.companyName,
				provider.fantasyName,
				provider.spokesman,
				'<div class="btn-group">{0}{1}</div>'.format(openButton, siteButton),
			]).draw();
		});

		$('.btn-site').click(function() {
			var provider = ProviderSearch.providers[this.dataset.id];
			window.open('http://{0}'.format(provider.site), '_blank');
		});

		$('.btn-see').click(function() {
			var provider = ProviderSearch.providers[this.dataset.id];
			window.open('provider/view/{0}'.format(provider.id), '_blank');
		});
	},
}
