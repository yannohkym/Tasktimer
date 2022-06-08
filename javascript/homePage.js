$(document).ready(()=>{
    $('.check').click(function (){
        let id = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: './ajax/isComplete.php',
            data: { id : id},
            success: function (response){
                $('tr#' +id).css('display','none');
                $('#toast').html('<div class="toast" role="alert" aria-live="assertive" aria-atomic="true"> ' +
                    '<div class="toast-body"> ' +
                    '   <i style="color: green" id="check" class="fas fa-check-circle"></i> ' +
                    '   <span style="padding-left: 5px">Task marked as complete</span> ' +
                    '   <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close"> ' +
                    '   <span aria-hidden="true">&times;</span> ' +
                    '</button>' +
                    '</div> ' +
                    '</div>')
                $('.toast').toast('show');
                $('.close').click(()=>{
                    $('.toast').toast('hide');
                })
            },
            error: ()=> {console.log(error)}
        })
    });
    $('#vclse').click(()=>{
        location.reload();
    })


    $('.vstat').click(function (){
        let id = $(this).attr('id');
        console.log(id);
        window.location.href = './pomodoro.php?pomid='+id;
    })

    $('.projDis').click(function() {
        let id = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: './ajax/projectDetails.php',
            data: { id: id},
            success: function (response){
                window.location.href = response[0]['link'];
            },
            dataType: 'json',
            error: ()=> {console.log(error)}
        })
    })
})