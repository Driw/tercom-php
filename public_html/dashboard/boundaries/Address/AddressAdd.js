
$(document).ready(function()
{
        AddressAdd.init();
});

var AddressAdd = AddressAdd ||
{
    init: function()
    {
        AddressAdd.form = $('#form-address-add');
        AddressAdd.relationship = $(AddressAdd.form[0].relationship).val();
        AddressAdd.idRelationship = $(AddressAdd.form[0].idRelationship).val();
        AddressAdd.id = $(AddressAdd.form[0].idAddress).val();
        this.initFormSettings();
    },
    initFormSettings: function()
    {
        ws.address_settings(this.form, AddressAdd.onSettings);
    },
    onSettings: function(settings)
    {
        AddressAdd.form.validate({
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
                    AddressAdd.submit($(form));
                } catch (e) {
                    console.log(e);
                }
                return false;
            }
        });
    },
    submit: function(form)
    {
        ws.address_add(AddressAdd.relationship, AddressAdd.idRelationship, AddressAdd.form, AddressAdd.onSubmited);
    },
    onSubmited: function(address, message)
    {
        AddressAdd.form.trigger('reset');

        $('#row-message').show(DEFAULT_FADE);
        $('#row-message-span').html(message);
        setTimeout(function() { $('#row-message').hide('slow'); }, 3000);
    }
}

















//
