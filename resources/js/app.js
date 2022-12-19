import './bootstrap';

window.doPostAjax = function (url, data, success, error) {
    $.ajax({
        url: url,
        method: 'post',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
        },
        data: data,
        success: success,
        error: error,
    });
};

window.doGetAjax = function (url, successCallBack, errorCallback) {
    $.get(url).done(successCallBack).fail(errorCallback);
};