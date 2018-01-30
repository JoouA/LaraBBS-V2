$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#up-vote').click(function (event) {
    var target = $(event.target);

    var url = target.attr('data-url');

    var vote_user = target.attr('vote-user');

    var topic_id = target.attr('topic-id');

    var vote = target.attr('vote-zan');

    $.ajax({
        url: url,
        method: "POST",
        data: {"vote_user": vote_user, "topic_id": topic_id},
        dataType: "JSON",
        success: function (data) {
            if (data.status == 'success') {
                if (vote == 'vote') {
                    target.text('点赞');
                    target.attr('vote-zan', 'unVote');
                    //移除掉相关的头像
                    $('.user-lists').find("a[data-userId='" + vote_user + "']").fadeOut('slow/400/fast', function () {
                        $(this).remove();
                    });
                } else {
                    target.text('已点赞');
                    target.attr('vote-zan', 'vote');

                    //将用户的头像增加进去
                    // @CJ 如果是点赞，并且是没有点过赞的
                    var newContent = $('.voted-template').clone();
                    newContent.attr('data-userId', vote_user);
                    newContent.attr('href', data.user_link);
                    newContent.find('img').attr('src', data.user_avatar);

                    newContent.prependTo('.user-lists').show('fast', function () {
                        $(this).addClass('animated swing');
                    });

                    $('.vote-hint').hide();

                }
                console.log('点赞成功');
            } else {
                console.log('点赞失败');
            }
        }
    });
});

$('#login-out').on('click', function(e) {
    var langText = $(this).data('lang-loginout');

    swal({
        title: langText,
        text: langText,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: "取消",
        confirmButtonText: "退出",
    }).then(function (result) {
        if (result.value){
            event.preventDefault();
            document.getElementById('logout-form').submit();
            console.log('logout success');
        }else {
            console.log('logout cancel');
        }
    });

    return false;
});