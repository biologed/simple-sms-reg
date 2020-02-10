$(document).ready(function () {
    let upd, name = $("#name");
    function update() {
        if(name.val()) {
            $.ajax({
                type: "POST",
                url: 'action.php?link=update',
                data: {
                    update: true,
                    name: name.val()
                },
                success: [
                    function (results) {
                        $.notify(results);
                    }
                ]
            });
        }
    }
    $('#checkbox').change(function () {
        if ($('#checkbox').is(':checked')) {
            upd = setInterval(update, 2000);
        } else {
            clearInterval(upd);
        }
    });

    $('#send').click(function () {
        if(name.val() && !$("#code").val()) {
            $.ajax({
                type: "POST",
                url: 'action.php?link=reg',
                data: {
                    name: $("#name").val()
                },
                success: [
                    function () {
                        $("form .code-container").css('display', 'block');
                    }
                ]
            });
        }
    });

    $('#verify').click(function () {
        $.ajax({
            type: "POST",
            url: 'action.php?link=success',
            data: {
                name: $("#name").val(),
                code: $("#code").val()
            },
            success: [
                function (results) {
                    alert(results);
                    clearInterval(upd);
                }
            ]
        });
    })
});