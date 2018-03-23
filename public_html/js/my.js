$('body').on('click', 'a[href*=deleteuser]', function(e){
    e.preventDefault();

    var user_id = $(this).data('id');

    $.ajax({
        url: 'userslist/deleteuser',
        type: 'POST',
        dataType: 'json',
        data: {
            id: user_id
        }
    })
        .done(function(data) {

            if (data.result == 'success') {
                // Получилось
                swal({
                    title: 'Успешно!',
                    text: 'Пользователь "'+ data.name +'" (логин: "'+ data.login +'") удалён',
                    type: 'success',
                    confirmButtonColor: '#619c34',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    // Перезагружаем страницу заново
                    window.location.reload();
                })
            } else {
                // Ошибка - выдаём сообщение с номером ошибки
                swal({
                    type: 'error',
                    title: 'Произошла ошибка',
                    text: data.errorMessage
                });
            }
        })
        .fail(function() {
            // Здесь просто сообщение об ошибке
            swal({
                type: 'error',
                title: 'Упс...',
                text: 'Что-то пошло не так'
            });
        });

});
