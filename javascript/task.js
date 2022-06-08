$(document).ready(()=> {
    ajax()
    function ajax(){
        $.ajax({
            type: 'POST',
            url: './ajax/displayTasks.php',
            data: {id: $('select#tCategory option:selected').val()},
            success: function (response) {
                $('.task').remove();
                $.each(response, function (key, value) {
                    let due = new Date(value['due_date'])
                    let created = new Date(value['added_at']);
                    let x = (created.setHours(0,0,0,0) - new Date().setHours(0,0,0,0) )/86400000;
                    let y = (due.setHours(0,0,0,0) - new Date().setHours(0,0,0,0) )/86400000;
                    if(x==0 || x==-1){
                        table('recent', value);
                    }
                    if(y === 0){
                        table('today', value);
                    }else if(y <= 5 && y > 0){
                        table('upcoming', value);
                    }else if(y > 5){
                        table('later', value);
                    }else{
                        table('overdue', value);
                    }

                })

                clicked();
                taskName();
                pomodoro();
            },
            dataType: 'json',
            error: () => {
                console.log(error)
            }
        })
    }
    $("#tCategory").change(function () {
            ajax();
    });

    function pomodoro(){
        $('.vstat').click(function (){
            let id = $(this).attr('id');
            console.log(id);
            window.location.href = './pomodoro.php?pomid='+id;
        })
    }

    function table(id, value){
        $('#'+id+'1').remove();
        let pomodoro = '';
        let x = parseInt(value['pomodoro'])
        if(x<=4){
            for(let i=0;i<x;i++){
                pomodoro += '<i style="color: orangered" class="fas fa-apple-alt"></i>';
            }
        }
        let date;
        let due = new Date(value['due_date'])
        let time = due.toLocaleTimeString([], { hour: '2-digit', minute: "2-digit" });
        let tarehe = due.toLocaleDateString();
        let y = (due.setHours(0,0,0,0) - new Date().setHours(0,0,0,0) )/86400000;
        if(y === 0){
            date = '<span style="color: red">today<br>'+time+'</span>';
        }else if(y === 1){
            date = '<span style="color: red">tomorrow<br>'+time+'</span>';
        }else if(y > 1 && y<=6){
            switch (due.getDay()) {
                case 0:
                    date = '<span style="color: green">Sunday<br>'+time+'</span>';
                    break;
                case 1:
                    date = '<span style="color: green">Monday<br>'+time+'</span>';
                    break;
                case 2:
                    date = '<span style="color: green">Tuesday<br>'+time+'</span>';
                    break;
                case 3:
                    date = '<span style="color: green">Wednesday<br>'+time+'</span>';
                    break;
                case 4:
                    date = '<span style="color: green">Thursday<br>'+time+'</span>';
                    break;
                case 5:
                    date = '<span style="color: green">Friday<br>'+time+'</span>';
                    break;
                case 6:
                    date = '<span style="color: green">Saturday<br>'+time+'</span>';
            }
        }else{
            date = tarehe +'<br>'+time;
        }
        let style, icon, button;
        if(value['isComplete'] == 1){
            style = "color: gray; opacity: 0.5";
            icon = '<i style="color: green" class="fas fa-check-circle"></i>';
            button = '<button id="startask" style="background-color: lightgreen" disabled>completed</button>';
        }else{
            style = '';
            icon = '<i style="color: gray" class="far fa-check-circle"></i>';
            button = '<button id="startask">start</button>';
        }
        document.getElementById(id).innerHTML += '<tr class="task" style="'+style+'" id="'+value['id']+'"><td class="check vCheck" id="'+value['id']+'">'+icon+'</td>' +
            '            <td class="taskName vdet" id="'+value['id']+'">'
                            +value['task_name']+
            '            </td>' +
            '            <td class="vpom">' +
                        pomodoro+
            '            </td>' +
            '            <td id="d'+value['id']+'" class="vdue">' +
                                date+
            '            </td>\n' +
            '            <td class="vpro">\n'
                       +value['project_name']+
            '            </td>\n' +
                           '<td class="vstat" id="'+value['id']+'">' +  button + '</td>\n' +
            '        </tr>';
    }
    function clicked(){
        $('.check').click(function (){
            let id = $(this).attr('id');
            $.ajax({
                type: 'POST',
                url: './ajax/isComplete.php',
                data: { id : id},
                success: function (response){
                    if(response == '1'){
                        $('tr#' +id+ '.task').css({color: 'gray', opacity: 0.5});
                        $('td#' +id+ '.check').html('<i style="color: green" class="fas fa-check-circle"></i>');
                        $('td#' +id+ '.vstat').html('<button id="startask" style="background-color: lightgreen">completed</button>');
                        $('#vtoast').html('<div class="toast" role="alert" aria-live="assertive" aria-atomic="true"> ' +
                            '<div class="toast-body"> ' +
                            '   <i style="color: green" id="check" class="fas fa-check-circle"></i> ' +
                            '   <span style="padding-left: 5px">Task marked as complete</span> ' +
                            '   <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close"> ' +
                            '   <span aria-hidden="true">&times;</span> ' +
                            '</button>' +
                            '</div> ' +
                            '</div>')
                    }else{
                        $('tr#' +id+'.task').css({color: 'black', opacity: 1});
                        $('td#' +id+ '.check').html('<i style="color: gray" class="far fa-check-circle"></i>');
                        $('td#' +id+ '.vstat').html('<button id="startask">start</button>');
                        $('#vtoast').html('<div class="toast" role="alert" aria-live="assertive" aria-atomic="true"> ' +
                            '<div class="toast-body"> ' +
                            '   <i style="color: green" id="check" class="fas fa-check-circle"></i> ' +
                            '   <span style="padding-left: 5px">Task marked as incomplete</span> ' +
                            '   <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close"> ' +
                            '   <span aria-hidden="true">&times;</span> ' +
                            '</button>' +
                            '</div> ' +
                            '</div>')
                    }

                    $('.toast').toast('show');
                    $('.close').click(()=>{
                        $('.toast').toast('hide');
                    })
                },
                dataType: 'json',
                error: ()=> {console.log(error)}
            })
        });
    }
    function taskName(){
        $('.taskName').click(function () {
            let id = $(this).attr('id');
            $("#vsubmit.vedit").css('display', 'none');
            $.ajax({
                type: 'POST',
                url: './ajax/taskDetails.php',
                data: { id : id},

                success: function (response){
                    $("#view1").css("display","block").removeClass('animate__animated animate__fadeOutUpBig').addClass('animate__animated animate__backInDown');
                    $("#add1").css("display","none");
                    $("#add2").css("display","none");
                    $("#add3").css("display","none");
                    $('input#vtaskId').val(id);
                    $('input#vName').val(response[0]['task_name']);
                    $('input#vDate').val(response[0]['due_date']);
                    $('select#vAssignee').html('<option value="'+response[0]['member']+'" selected>'+response[0]['name']+'</option>');
                    $('option#select').text(response[0]['team_name']+ ' - ' + response[0]['project_name']).val(response[0]['project_id']);
                    $('input#vDescription').text(response[0]['description']);
                    $('input#vpomodoro').val(response[0]['pomodoro']);
                    $("#vform :input").prop("disabled", true).css('background-color','burlywood');
                    $('select#vCategory').html('<option class="aOpt" value="'+response[0]['id']+'" selected>'+response[0]['category']+'</option>');
                    if(response[0]['user']){
                        $('#By').css('display','none')
                        $('#vedit').css('display','block');
                    }else{
                        $('#By').css('display','block')
                        $('#vAssigner').val(response[0]['assigner']);
                    }
                    if(response[0]['isComplete'] == '1'){
                        $('#complete').css({color: 'white', backgroundColor: 'green'});
                        $('#complete').html('<i class="fas fa-check-circle"></i>completed');
                    }else{
                        $('#complete').css({color: 'gray', backgroundColor: 'white'});
                        $('#complete').html('<i style="color: gray" class="far fa-check-circle"></i>Mark as complete');
                    }
                    $('#complete').click(function (){
                        $.ajax({
                            type: 'POST',
                            url: './ajax/isComplete.php',
                            data: { id : id},
                            success: function (response){
                                if(response == '1'){
                                    $('#complete').css({color: 'white', backgroundColor: 'green'});
                                    $('#complete').html('<i class="fas fa-check-circle"></i>completed');
                                }else{
                                    $('#complete').css({color: 'gray', backgroundColor: 'white'});
                                    $('#complete').html('<i style="color: gray" class="far fa-check-circle"></i>Mark as complete');
                                }
                                ajax();
                            },
                            dataType: 'json',
                            error: ()=> {console.log(error)}
                        })

                    });
                },
                dataType: 'json',
                error: ()=> {console.log(error)}
            })
        })
    }
})
