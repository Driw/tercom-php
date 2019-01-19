
$(document).ready(function()
{
	ProviderList.init();
	ProviderList.loadProviders();
});

var ProviderList = ProviderList ||
{
	init: function()
	{
		ProviderList.table = $('#table-providers');
		ProviderList.tbody = ProviderList.table.find('tbody');
		ProviderList.dataTable = newDataTables(ProviderList.table);
	},
	loadProviders: function()
	{
		ws.provider_getAll(ProviderList.tbody, ProviderList.onProvidersLoaded);
	},
	onProvidersLoaded: function(providers)
	{
		ProviderList.providers = providers.elements;
		ProviderList.providers.forEach(function(provider, index)
		{
			var providerRowData = ProviderList.newProviderRowData(index, provider);
			var row = ProviderList.dataTable.row.add(providerRowData).draw();
		});
	},
	newProviderRowData: function(index, provider)
	{
		var btnView = '<button type="button" class="btn btn-primary" data-index="' +index+ '" onclick="ProviderList.onButtonView(this)">Ver</button>';
		var btnSite = '<button type="button" class="btn btn-info" data-index="' +index+ '" onclick="ProviderList.onButtonSite(this)">Site</button>';

		return [
			provider.id,
			provider.cnpj,
			provider.companyName,
			provider.fantasyName,
			provider.spokesman,
			'<div class="btn-group">' +btnView+btnSite+ '</div>',
		];
	},
	onButtonView: function(button)
	{
		var index = button.dataset.index;
		var provider = ProviderList.providers[index];

		if (provider !== undefined)
			Util.redirect('provider/view/' +provider.id, true);
	},
	onButtonSite: function(button)
	{
		var index = button.dataset.index;
		var provider = ProviderList.providers[index];

		if (provider !== undefined)
			window.open('http://' +provider.site, '_blank');
	},
}
