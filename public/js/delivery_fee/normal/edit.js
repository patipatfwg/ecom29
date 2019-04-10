$("#btn-submit").click(function(event){
    $(this).button("loading");
    event.preventDefault(event);
    var form = $("#form-delivery-fee-normal");
    var id = form.attr('id');
    var url = form.attr('url');
    var method = form.attr('method');
    var data = form.serialize();

    form.valid();
    callAjax(method, url, data, null, function(){
        location.reload();
    },
    null,
    function(){
        $("#btn-submit").button('reset');
    });
});

function addRow(element)
{
    var randomNum = Math.floor((Math.random() + Math.random()) * 100000000);
    var clone    = $('#edit-table tbody tr:nth-child(1)').clone();
    clone.find(':text').val('');
    clone.find(':text').attr('value', '');
    clone.find(':text[name*="min"]').attr({
        id: 'min' + randomNum,
        name: 'data[new_' + randomNum + '][min]',
        readonly: false
    });
    clone.find(':text[name*="fee"]').attr({
        id: 'fee' + randomNum,
        name: 'data[new_' + randomNum + '][fee]'
    });
    $("#td-add").before(clone);
}