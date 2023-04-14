require([
        'jquery',
        'Magento_Ui/js/modal/modal'
    ],
    function(
        $,
        modal
    ) {
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: 'FeedBack (Why you should delete account?)',
            buttons: [{
                text: $.mage.__('Continue...'),
                class: '',
                click: function () {
                    if (!$('#customtext').val()){
                        alert($('#customTextRequire').val());
                        return false;
                    }
                    $('#feedback').val($('#customtext').val());
                    $('#delete-account').attr('type','submit');
                    $('#delete-account').click();
                }
            }]
        };

        var popup = modal(options, $('#popup-modal'));
        $("#delete-account").on('click',function(){
            $("#popup-modal").modal("openModal");
        });

    }
);