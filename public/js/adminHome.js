$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#logout').click(function(){
        $.ajax({
            url:'../logout',
            type:'get',
            success:function(){
                window.location = "/";
            },
            error:function(){
                console.log('error');
            }
        });
    });
});