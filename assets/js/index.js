var theme = localStorage.getItem('theme');
if (theme == 'dark') {
    $('body').addClass('mdui-theme-layout-dark');
    $('#change_style').html('<i class="mdui-icon material-icons">brightness_7</i>');
}
$('#change_style').click(function() {
    var theme = localStorage.getItem('theme');
    if (theme == 'dark') {
        localStorage.setItem('theme', 'white');
        $(this).html('<i class="mdui-icon material-icons">brightness_4</i>');
        $('body').removeClass('mdui-theme-layout-dark');
    } else {
        localStorage.setItem('theme', 'dark');
        $(this).html('<i class="mdui-icon material-icons">brightness_7</i>');
        $('body').addClass('mdui-theme-layout-dark');
    }
});


var page = getRequestParam('page');
var action = getRequestParam('action');
if (action == 'admin') {
    if (!page) {
        page = 'index';
    }
    $('.' + page).addClass('mdui-list-item-active');
} else {
    if (!action) {
        action = 'index';
    }
    $('.' + action).addClass('mdui-list-item-active');
}


/*
function loading(type){
	if(type == 'start'){
		$('#loading').empty();
		$('#loading').append('<div class="mdui-progress"><div class="mdui-progress-indeterminate"></div></div>');
		$('#submit').attr('disabled', 'disabled');
	}else if(type == 'stop'){
		$('#loading').empty();
		$('#submit').attr('disabled', null);
	}else{
		return false;
	}
}
*/

var loadingDialog = new mdui.Dialog('#loading', {
    history: false,
    modal: true,
    closeOnEsc: false,
});

function loading(type) {
    if (type == 'open') {
        loadingDialog.open();
    } else if (type == 'close') {
        loadingDialog.close();
    } else {
        return false;
    }
}

function scroll(id) {
    document.querySelector(id).scrollIntoView({ behavior: 'smooth' });
}

$(window).scroll(function() {
    var scrollTop = $(window).scrollTop();
    if (scrollTop > 130) {
        $('#fabUp').removeClass('mdui-fab-hide');
    } else {
        $('#fabUp').addClass('mdui-fab-hide');
    }
});

function goTop() {
    document.querySelector('#top').scrollIntoView({ behavior: 'smooth' });
    setTimeout(function() {
        mdui.snackbar({
            message: '吖，撞到头辣QAQ',
            position: 'top',
            timeout: 800
        });
    }, 500);
}
// 
// $('.like').click(function(){
// alert('点赞成功！QWQ');
// //$()
// });

function formSumbit() {
    var options = {
        success: submit,
        timeout: 3000,
    }

    function submit(data) {
        var code = JSON.parse(data).code;
        var msg = JSON.parse(data).msg;
        if (code == "200") {
            mdui.snackbar(msg);
            setTimeout(function() {
                window.location.reload();
            }, 1500);
        } else {
            mdui.snackbar(msg);
        }
    };

    $('form').submit(function() {
        $(this).ajaxSubmit(options);
        return false;
    });
}

$('#back').click(function() {
    window.history.go(-1);
});

function getRequestParam(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if (pair[0] == variable) { return pair[1]; }
    }
    return (false);
}

function jump(url) {
    if (!url) {
        return false;
    } else {
        window.open('?action=jump&url=' + url);
    }
}

function checkMailAddress(v) {
    var reg = /^\w+((.\w+)|(-\w+))@[A-Za-z0-9]+((.|-)[A-Za-z0-9]+).[A-Za-z0-9]+$/; //正则表达式
    if (!reg.test(v)) { //正则验证不通过，格式不对
        return false;
    } else {
        return true;
    }
}


$('#exit_login').click(function() {
    var exitLogin = confirm('你真的要退出登录吗？');
    if (exitLogin) {
        fetch('./Data/api.php?type=exitLogin')
            .then(response => response.json())
            .then(json => {
                mdui.snackbar(json.msg);
                if (json.code == 0) {
                    setTimeout(function() {
                        window.location.href = '?action=admin&page=login';
                    }, 1000);
                }
            })
    }
});

$('#submit').click(function() {
    var username = $('#username').val();
    var password = $('#password').val();
    if (username && password) {
        $.ajax({
            url: './Data/post.php',
            method: 'POST',
            data: {
                type: 'login',
                username: username,
                password: password
            },
            beforeSend: function() {
                loading('open');
            },
            success: function(data) {
                var code = JSON.parse(data).code;
                var msg = JSON.parse(data).msg;
                mdui.snackbar(msg);
                if (code == 0) {
                    window.location.href = '?action=admin';
                }
            },
            error: function() {
                mdui.snackbar('网络错误！');
            },
            complete: function() {
                loading('close');
            }
        });
    } else {
        mdui.snackbar('请输入完整！');
    }
});