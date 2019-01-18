
$(document).ready(function()
{
	ProductSearch.init();
});

var ProductSearch = ProductSearch ||
{
	init: function()
	{
		this.providers = [];
		this.form = $('#form-search');
		this.table = $('#table-products');
		this.tbody = this.table.find('tbody');
		this.datatables = newDataTables(this.table);
		this.initFormtSettings();
	},
	initFormtSettings: function()
	{
		this.form.validate({
			'rules': {
				'filter': {
					'required': true,
				},
				'value': {
					'required': true,
				},
			},
			submitHandler: function(form) {
				ProductSearch.onSearch($(form));
				return false;
			},
		});
	},
	onSearch: function()
	{
		var form = ProductSearch.form[0];
		var value = $(form.value).val();
		var filter = $(form.filter).val();

		ws.product_search(filter, value, ProductSearch.form, ProductSearch.searchProducts);
	},
	searchProducts: function(products)
	{
		ProductSearch.datatables.rows().remove();
		ProductSearch.products = products.elements;
		ProductSearch.products.forEach(function(product, index)
		{
			var viewButton = '<button type="button" class="btn btn-info" data-index="' +index+ '" onclick="ProductSearch.onButtonSee(this)">Ver</button>';
			var pricesButton = '<button type="button" class="btn btn-primary" data-index="' +index+ '" onclick="ProductSearch.onButtonPrices(this)">Preços</button>';
			var row = ProductSearch.datatables.row.add([
				product.id,
				product.name,
				product.description,
				product.utility,
				product.category.family.name,
				'<div class="btn-group">' + viewButton + pricesButton + '</div>',
			]).draw();
		});
	},
	onButtonSee: function(button)
	{
		var index = button.dataset.index;
		var product = ProductSearch.products[index];

		if (product !== undefined)
			Util.redirect('product/view/' +product.id, true);
	},
	onButtonPrices: function(button)
	{
		var index = button.dataset.index;
		var product = ProductSearch.products[index];

		if (product !== undefined)
			Util.redirect('productPrices/view/' +product.id, true);
	}
}
