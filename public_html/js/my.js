$('body').on('click', 'a[href*=deleteuser]', function(e){
    e.preventDefault();
    var el = $(this).attr('id');
    alert('Клик! ' + el.toString());

    // ajax ...


});
