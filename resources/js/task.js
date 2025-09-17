// public/js/task.js
$(function () {
    // タイトル文字数カウント
    function updateTitleCount() {
        var count = $('#title').val().length;
        $('#title-char-count').text(count);
        if (count > 100) {
            $('#title-char-count').css('color', 'red');
        } else if (count > 80) {
            $('#title-char-count').css('color', 'orange');
        } else {
            $('#title-char-count').css('color', 'black');
        }
    }

    // 初期表示
    updateTitleCount();

    // イベント設定
    $('#title').on('keyup', updateTitleCount);
});

