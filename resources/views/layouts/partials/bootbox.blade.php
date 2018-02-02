<script>
    function bbconfirm(id,title) {
        bootbox.confirm({
            title: '請你確定一下',
            message: title,
            buttons: {
                confirm: {
                    label: '我很確定',
                    className: 'btn-success'
                },
                cancel: {
                    label: '我按錯了',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    document.getElementById(id).submit();
                }
                console.log('This was logged in the callback: ' + result);
            }
        });
    }
    function bbconfirm2(id,title) {
        link = document.getElementById(id).href;
        document.getElementById(id).href='#';
        bootbox.confirm({
            title: '請你確定一下',
            message: title,
            buttons: {
                confirm: {
                    label: '我很確定',
                    className: 'btn-success'
                },
                cancel: {
                    label: '我按錯了',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    location.href = link;
                }else{
                    document.getElementById(id).href=link;
                }

                console.log('This was logged in the callback: ' + result);
            }
        });
    }
    function bbalert(word){
        bootbox.alert(word);
    }

    function bbwait(){
        var dialog = bootbox.dialog({
            message: '<p class="text-center">請等待一會，不要關閉視窗，也不要按F5...</p>',
            closeButton: false
        });
        dialog.modal('hide');
    }
    function bbconfirm3(id,title) {
        bootbox.confirm({
            title: '請你確定一下',
            message: title,
            buttons: {
                confirm: {
                    label: '我很確定',
                    className: 'btn-success'
                },
                cancel: {
                    label: '我按錯了',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                    document.getElementById(id).submit();
                    var dialog = bootbox.dialog({
                        message: '<p class="text-center">請等待一會，不要關閉視窗，也不要按F5...</p>',
                        closeButton: false
                    });
                    dialog.modal('hide');
                }else{
                    $("#b_submit").show();
                }
                console.log('This was logged in the callback: ' + result);
            }
        });
    }
</script>