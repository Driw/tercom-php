
$(document).ready(function()
{
        AddressView.init();
});

var AddressView = AddressView ||
{
    init: function()
    {
        AddressView.form = $('#form-address-view');
        AddressView.relationship = $(AddressView.form[0].relationship).val();
        AddressView.idRelationship = $(AddressView.form[0].idRelationship).val();
        AddressView.id = $(AddressView.form[0].idAddress).val();
        AddressView.initFormSettings();
        AddressView.loadAddress();
    },
    initFormSettings: function()
    {
        ws.address_settings(AddressView.form, AddressView.onSettings);
    },
    loadAddress: function()
    {
        ws.address_get(AddressView.relationship, AddressView.idRelationship, AddressView.id, AddressView.form, AddressView.onAddressLoaded);
    },
    onSettings: function(settings)
    {
        AddressView.form.validate({
            'rules': {
                'state': {
                    'required': true,
                },
                'city': {
                    'required': true,
                    'rangelength': [ settings.minCityLen, settings.maxCityLen ],
                },
                'cep': {
                    'required': true,
                    'length': settings.cepLen + 1,
                },
                'neighborhood': {
                    'required': true,
                    'rangelength': [ settings.minNeighborhoodLen, settings.maxNeighborhoodLen ],
                },
                'street': {
                    'required': true,
                    'rangelength': [ settings.minStreetLen, settings.maxStreetLen ],
                },
                'number': {
                    'required': true,
                    'range': [ settings.minNumber, settings.maxNumber ],
                },
                'complement': {
                    'required': false,
                    'maxlength': settings.maxComplementLen,
                },
            },
            submitHandler: function(form) {
                try {
                    AddressView.submit($(form));
                } catch (e) {
                    console.log(e);
                }
                return false;
            }
        });
    },
    onAddressLoaded: function(address)
    {
        let form = AddressView.form[0];
        $(form.id).val(address.id);
        $(form.state).val(address.state);
        $(form.city).val(address.city);
        $(form.cep).val(address.cep).trigger('input');
        $(form.neighborhood).val(address.neighborhood);
        $(form.street).val(address.street);
        $(form.number).val(address.number);
        $(form.complement).val(address.complement);
    },
    submit: function(form)
    {
        ws.address_set(AddressView.relationship, AddressView.idRelationship, AddressView.id, AddressView.form, AddressView.onSubmited);
    },
    onSubmited: function(address, message)
    {
        AddressView.onAddressLoaded(address);

        $('#row-message').show(DEFAULT_FADE);
        $('#row-message-span').html(message);
        setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
    }
}

















//
