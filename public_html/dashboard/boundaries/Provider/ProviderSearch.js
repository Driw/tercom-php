
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
		ProviderSearch.datatables = newDataTables(ProviderSearch.table);
	},
	searchProvider: function()
	{
		var filter = ProviderSearch.form[0].filter.value;
		var value = ProviderSearch.form[0].value.value;
		ws.provider_search(filter, value, ProviderSearch.tbody, ProviderSearch.onSearchProvider)
	},
	onSearchProvider: function(providers)
	{
		ProviderSearch.datatables.clear().draw();
		ProviderSearch.providers = providers.elements;
		ProviderSearch.providers.forEach(function(provider, index)
		{
			var providerRowData = ProviderSearch.newProviderRowData(index, provider);
			var row = ProviderSearch.datatables.row.add(providerRowData).draw();
		});
	},
	newProviderRowData: function(index, provider)
	{
		var btnTemplate = '<button type="button" class="btn btn-sm btn-{0}" onclick="ProviderSearch.{1}({2})" title="{3}">{4}</button>';
		var btnView = btnTemplate.format('primary', 'onButtonView', index, 'Ver', ICON_VIEW);
		var btnSite = btnTemplate.format('info', 'onButtonSite', index, 'Site', ICON_LINk);
		var btnProducts = btnTemplate.format('info', 'onButtonProducts', index, 'Produtos', ICON_PRODUCT);
		var btnServices = btnTemplate.format('info', 'onButtonServices', index, 'Serviรงos', ICON_SERVICE);

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
		var provider = ProviderSearch.providers[index];
		Util.redirect('provider/view/' +provider.id);
	},
	onButtonSite: function(index)
	{
		var provider = ProviderSearch.providers[index];
		window.open('http://' +provider.site, '_blank');
	},
	onButtonProducts: function(index)
	{
		var provider = ProviderSearch.providers[index];
		Util.redirect('products/provider/' +provider.id);
	},
	onButtonServices: function(index)
	{
		var provider = ProviderSearch.providers[index];
		Util.redirect('services/provider/' +provider.id);
	},
}
