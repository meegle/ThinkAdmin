;(function($) {
    // UI组件
    ADMIN_UI = window.ADMIN_UI || {
        alert: function(messageObj, closeFunc) {
            // $('.modal').modal('hide');//...
            var messageObj = {
                message: messageObj.message,
                btn_close: messageObj.btn_close
            };
            // 设置默认值
            if ($.type(closeFunc) != 'function') closeFunc = $.noop;
            if ($.type(messageObj.btn_close) != 'string') messageObj.btn_close = '确定';

            var $obj = $('#widget_alert');
            $obj
                .one('show', function() {
                    $('#widget_alert_message').html(messageObj.message);
                    $('#widget_alert_btn_close').html(messageObj.btn_close).one('click', function() {
                        $obj.one('hidden', closeFunc);
                        $obj.modal('hide');
                        return false;
                    });
                });
            $obj.modal({keyboard: false});
            return true;
        },

        dialog: function(messageObj, submitFunc, cancelFunc) {
            // $('.modal').modal('hide');//...
            var messageObj = {
                message: messageObj.message,
                btn_submit: messageObj.btn_submit,
                btn_cancel: messageObj.btn_cancel
            };
            // 设置默认值
            if ($.type(submitFunc) != 'function') submitFunc = $.noop;
            if ($.type(cancelFunc) != 'function') cancelFunc = $.noop;
            if ($.type(messageObj.btn_submit) != 'string') messageObj.btn_submit = '确定';
            if ($.type(messageObj.btn_cancel) != 'string') messageObj.btn_cancel = '取消';

            var $obj = $('#widget_dialog');
            $obj
                .one('show', function() {
                    $('#widget_dialog_message').html(messageObj.message);
                    $('#widget_dialog_btn_submit').html(messageObj.btn_submit).one('click', function() {
                        $obj.one('hidden', submitFunc);
                        $obj.modal('hide');
                        return false;
                    });
                    $('#widget_dialog_btn_cancel').html(messageObj.btn_cancel).one('click', function() {
                        $obj.one('hidden', cancelFunc);
                        $obj.modal('hide');
                        return false;
                    });
                });
            $obj.modal({keyboard: false});
            return true;
        },
        ajaxLoad: function(url, messageObj, submitFunc, cancelFunc, loadFunc) {
            // $('.modal').modal('hide');//...
            var messageObj = {
                header: messageObj.header,
                btn_submit: messageObj.btn_submit,
                btn_cancel: messageObj.btn_cancel
            };
            // 设置默认值
            if ($.type(submitFunc) != 'function') submitFunc = $.noop;
            if ($.type(cancelFunc) != 'function') cancelFunc = $.noop;
            if ($.type(messageObj.btn_submit) != 'string') messageObj.btn_submit = '确定';
            if ($.type(messageObj.btn_cancel) != 'string') messageObj.btn_cancel = '取消';
            if ($.type(loadFunc) != 'function') loadFunc = $.noop;

            var $obj = $('#widget_ajax');
            $obj
                .one('show', function() {
                    $('#widget_ajax_header').html(messageObj.header);
                    $('#widget_ajax_message').html('加载中...').load(url, loadFunc);
                    $('#widget_ajax_btn_submit').html(messageObj.btn_submit).one('click', function() {
                        $obj.one('hidden', submitFunc);
                        $obj.modal('hide');
                        return false;
                    });
                    $('#widget_ajax_btn_cancel').html(messageObj.btn_cancel).one('click', function() {
                        $obj.one('hidden', cancelFunc);
                        $obj.modal('hide');
                        return false;
                    });
                });
            $obj.modal({keyboard: false});
            return true;
        }
    };

    // 全局Ajax处理
    $.ajaxSetup({
        cache: false,// 关闭AJAX缓存
        complete: function(xhr) {},
        data: {},
        error: function(xhr, textStatus, errorThrown) {
            // 请求失败处理
        }
    });
    //----- 表单相关 start -----
    $('body')
    //提交按钮设置
    .delegate(':submit[data-action]', 'click', function() {
        var $this = $(this);
        $this.parents('form').attr('action', $this.attr('data-action'));
    })
    //表单异步提交
    .delegate('form.J_ajaxForm', 'submit', function(e) {
        var $this = $(this);
        var options = {
            dataType: 'json',
            success: function(res) {
                if (res.status) {
                    $this.find('div.alert').remove();
                    ADMIN_UI.alert({'message':res.info}, function() {
                        if ($.type(res.url) == 'string') {
                            window.location.href = res.url;
                        } else {
                            if ($.type(res.script) == 'string') {
                                eval('(' + res.script + ')');
                            } else {
                                $this.get(0).reset();//重置表单
                            }
                        }
                    });
                } else {
                    var $alert = $this.find('div.alert');
                    if ($alert.length) {
                        $alert.removeClass('hide').addClass('alert alert-error inline').html(res.info);
                    } else {
                        $this.children('.form-actions').append($('<div>').addClass('alert inline alert-error').html(res.info));
                    }
                    if ($.type(res.script) == 'string') {
                        eval('(' + res.script + ')');
                    }
                }
            }
        };
        $this.ajaxSubmit(options);
        return false;
    })
    //复选框全选
    .delegate(':checkbox.group-checkable', 'change', function() {
        var $this = $(this);
        var set = $this.attr('data-set');
        var checked = $this.is(':checked');
        $(set).each(function() {
            if (checked) {
                $(this).prop('checked', true);
            } else {
                $(this).prop('checked', false);
            }
        });
        $.uniform.update(set);
    })
    //----- 表单相关 end -----
    //----- 分页/搜索 start -----
    .delegate('.pagination a[href]', 'click', function() {
        var callback = $.noop;
        if (typeof ajaxCallback == 'function') callback = ajaxCallback;
        $('.portlet-body').load($(this).attr('href') + ' ' + '.portlet-body>', callback);
        return false;
    })
    .delegate('#searchForm', 'submit', function() {
        var $this = $(this);
        var callback = $.noop;
        if (typeof ajaxCallback == 'function') callback = ajaxCallback;
        $('.portlet-body').load($this.attr('action') + ' ' + '.portlet-body>', $this.serialize(), callback);
        return false;
    });
    //----- 分页/搜索 end -----
    $(function() {
        //导航菜单显示
        $('.page-sidebar-menu').find('span.hide').filter(":contains('"+window.location.pathname.toLowerCase()+"')").each(function(i) {
            $this = $(this);
            $currentElement = $this;
            for (var j = 5; j > 0; j--) {
                $currentElement = $currentElement.parent('li');
                if (!$currentElement.length) break;
                $currentElement.addClass('active');
                $currentElement = $currentElement.parent('ul.sub-menu').prev('a').children('span.arrow');
                if (!$currentElement.length) break;
                $currentElement.addClass('open');
                $currentElement = $currentElement.end().end();
                // console.dir($currentElement);
            }
        });
        //modalmanager 初始化配置
        $.fn.modalmanager.defaults.resize = true;
    });
})(jQuery);