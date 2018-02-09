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

                    var dialog = bootbox.dialog({
                        title: '請稍後，不要亂按！重新整理F5都會造成重覆訂餐！',
                        message: '<p><i class="fa fa-spin fa-spinner"></i> 儲存中...</p>'
                    });

                    document.getElementById(id).submit();

                    dialog.init(function(){
                        setTimeout(function(){
                            dialog.find('.bootbox-body').html('請等待畫面跳轉後，即完成！!');
                        }, 3000);
                    });
                }else{
                    $("#b_submit").show();
                }
                console.log('This was logged in the callback: ' + result);
            }
        });
    }
</script>