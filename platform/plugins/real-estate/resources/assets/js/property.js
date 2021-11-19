class Property {
    static change(element) {
        const property_id = element.val();
        let contract = $(document).find('select[data-type=contract]');
        const url = `/contracts/property/${property_id}/contracts`;
        if (element.val() !== '') {
            $.ajax({
                url,
                type: 'GET',
                beforeSend: () => {
                    element.closest('form').find('button[type=submit], input[type=submit]').prop('disabled', true);
                },
                success: resp =>  {
                    let option = '<option value="">' + (contract.data('placeholder')) + '</option>';
                    $.each(resp.data,(index, item) => {
                        if (item.id === contract.data('origin-value')) {
                            option += '<option value="' + item.id + '" selected="selected">' + item.name + '</option>';
                        } else {
                            option += '<option value="' + item.id + '">' + item.name + '</option>';
                        }

                    });
                    contract.html(option);
                    element.closest('form').find('button[type=submit], input[type=submit]').prop('disabled', false);
                }
            });
        }
    }
}

$(document).ready(() => {
    let $property_fields = $(document).find('select[data-type=property]');

    if ($property_fields.length > 0) {
        $.each($property_fields, (index, el) => {
            Property.change($(el));
        });
        $(document).on('change', 'select[data-type=property]', event =>  {
            Property.change($(event.currentTarget));
        });
    }
});
