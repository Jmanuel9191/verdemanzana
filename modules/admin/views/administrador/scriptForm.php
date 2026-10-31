<?php
$script= <<< JS
function newRow(url,form) {
    var input = $("#formEvento #adicional");
    var index = 0;
    if (input.find(".redes-input-index").length > 0) {
        $(".redes-input-index").each(function () {
            index = Math.max(index, parseInt(this.value)) + 1;
        });
    }
    $.ajax({
        type: 'GET',
        url: url,
        data: 'index=' + index,
        dataType: 'html',
        success: function (html) {
            input.append(html);
            input.siblings('.redes-header').show();
            $('#cantidad').val( parseInt($('#cantidad').val())+1);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            alert('Error!');
        }
    });
}

$(document).on('click','#formEvento .redes-input-remove', function(event) {
    event.preventDefault();
    $(this).parents('.redes-input:first').remove();
    $('.redes-input-container').filter(function () {
        return $.trim($(this).text()) === '' && $(this).children().length == 0
    }).siblings('.redes-header').hide();
    $('#cantidad').val( parseInt($('#cantidad').val())-1);
});
JS;
$this->registerJs($script, \yii\web\View::POS_HEAD);