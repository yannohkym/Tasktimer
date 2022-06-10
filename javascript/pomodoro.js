$(document).ready(()=>{
    let id = $('.pomtaskN').attr('id');
    let pomodoro; //number of pomodoro
    if (id === 'default'){
        pomodoro = 4;
        sessionStorage.setItem('pomodoro', pomodoro);
        for(let i=0; i<4; i++){
            document.getElementById('pomNo').innerHTML += '<span class="apple" ><i style="color: darkred" class="fas fa-apple-alt"></i></span>';
        }
    }else{
        $.ajax({
            type: 'POST',
            url: './ajax/taskDetails.php',
            data: {pomodoro : 1},
            success: function (response) {
                pomodoro = response[0]['pomodoro'];
                sessionStorage.setItem('pomodoro', pomodoro);
                if(response[0]['pomodoro']<=4){
                    for(let i=0; i<response[0]['pomodoro']; i++){
                        document.getElementById('pomNo').innerHTML += '<span class="apple" ><i style="color: darkred" class="fas fa-apple-alt apple"></i></span>';
                    }
                }else {
                    document.getElementById('pomNo').innerHTML += '<span class="apple">'+response[0]['pomodoro']+'<i style="color: darkred" class="fas fa-apple-alt"></i></span>';
                }

            },
            dataType: 'json',
            error: () => {
                console.log(error)
            }
        })
    }

    $('#subtract').click(function (){
        if(pomodoro > 1){
            pomodoro --
            sessionStorage.setItem('pomodoro', pomodoro);
            pomchange(pomodoro);
        }

    })

    $('#add').click(function (){
        pomodoro ++
        sessionStorage.setItem('pomodoro', pomodoro);
       pomchange(pomodoro);
    })

    function pomchange(pomodoro){
        if(pomodoro<=4 && pomodoro > 0){
            $('.apple').remove();
            for(let i=0; i<pomodoro; i++){
                document.getElementById('pomNo').innerHTML += '<span class="apple"><i style="color: darkred" class="fas fa-apple-alt apple"></i></span>';
            }
        }else if(pomodoro > 4){
            $('.apple').remove();
            document.getElementById('pomNo').innerHTML +='<span class="apple">'+pomodoro+'<i style="color: darkred" class="fas fa-apple-alt"></i></span>';
        }
    }

    $('#config').click(function (){
        $('#pomform').toggle();
    })

    $.ajax({
        type: 'POST',
        url: './ajax/taskDetails.php',
        data: {pomodoro : 1, config : 1},
        success: function (response) {
            console.log(response);
            $('#work').val(response[0]['duration'])
            $('#short').val(response[0]['short_break']);
            $('#long').val(response[0]['long_break']);
            $('#interval').val(response[0]['intervals']);
            update(response[0]['Pomodoro']);
        },
        dataType: 'json',
        error: () => {
            console.error();
        }
    })

    function update(value){
        $('#pomsub').click(function (){
            let pom = [

                $('#work').val(),
                $('#short').val(),
                $('#long').val(),
                $('#interval').val()
            ]
            $.ajax({
                type: 'POST',
                url: './ajax/pomodoro.php',
                data: {pomodoro : value, updatepom : pom},
                success: function (response) {
                    $('#toast').html('<div class="toast" role="alert" aria-live="assertive" aria-atomic="true"> ' +
                        '<div class="toast-body"> ' +
                        '   <i style="color: green" id="check" class="fas fa-check-circle"></i> ' +
                        '   <span style="padding-left: 5px">Pomodoro updated successfully</span> ' +
                        '   <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close"> ' +
                        '   <span aria-hidden="true">&times;</span> ' +
                        '</button>' +
                        '</div> ' +
                        '</div>')

                    $('.toast').toast('show');
                    $('.close').click(()=>{
                        $('.toast').toast('hide');
                    })
                    $('#pomform').css('display','none');
                },
                error: () => {
                    console.log(error)
                }
            })
        })
    }
})