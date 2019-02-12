
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
		ProviderList.datatables = newDataTables(ProviderList.table);
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
			var row = ProviderList.datatables.row.add(providerRowData).draw();
		});
	},
	newProviderRowData: function(index, provider)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm btn-{0}" onclick="ProviderList.{1}({2})" title="{3}">{4}</button>';
		var btnView = btnTemplate.format('primary', 'onButtonView', index, 'Ver', ICON_VIEW);
		var btnSite = btnTemplate.format('info', 'onButtonSite', index, 'Site', ICON_LINk);
		var btnProducts = btnTemplate.format('info', 'onButtonProducts', index, 'Produtos', ICON_PRODUCT);
		var btnServices = btnTemplate.format('info', 'onButtonServices', index, 'Serviços', ICON_SERVICE);

		return [
			provider.id,
			provider.cnpj,
			provider.companyName,
			provider.fantasyName,
			provider.spokesman,
			'<div class="btn-group">{0}{1}{2}{3}</div>'.format(btnView, btnSite, btnProducts, btnServices),
		];
	},
	onButtonView: function(index)
	{
		var provider = ProviderList.providers[index];
		Util.redirect('provider/view/' +provider.id);
	},
	onButtonSite: function(index)
	{
		var provider = ProviderList.providers[index];
		window.open('http://' +provider.site, '_blank');
	},
	onButtonProducts: function(index)
	{
		var provider = ProviderList.providers[index];
		Util.redirect('products/provider/' +provider.id);
	},
	onButtonServices: function(index)
	{
		var provider = ProviderList.providers[index];
		Util.redirect('services/provider/' +provider.id);
	},
}
