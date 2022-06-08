$(document).ready(()=>{
    start();
    function start(){
        let id = $('.id').attr('id');
        $.ajax({
            type: 'POST',
            url: './ajax/tnotification.php',
            data: { id: id },
            success: function (response){
                $('.notification, #oops').remove();
                if(response.length !== 0){
                    $.each(response, function (key, value){
                        let day = date(value['created_at']);
                        document.getElementById('preview').innerHTML += '<div class="notification '+value['type']+'" id="'+value['id']+'">'+
                            '<div id="itime" style="display:flex; width: 100%"><p class="iname" id="'+value['type']+'">'+value['name']+'</p>'+
                            '<p>'+day+'</p></div>'+
                            '<p class="imessage">'+value['message']+'</p>'+
                            '<p class="iteam">'+value['team']+' - '+value['project']+'</p>'+
                            '</div>';
                        if(value['checked'] == 1){
                            $('#'+value['id']+'.'+value['type']).css('backgroundColor', 'gainsboro')
                            $('#'+value['id']+'.'+value['type'] + ' p.imessage').css('color','gray');
                        }
                    })
                }else{
                    document.getElementById('preview').innerHTML = '<p style="color: gray; text-align: center" id="oops" class="nothing">Oops! no notifications for you</p>'
                }
                clicked();
            },
            dataType: 'json',
            error: ()=> {console.log(error)}
        })
    }

    function clicked(){
        $('.notification').click(function () {
            let id = $(this).attr('id');
            let type = $(this).find('.iname').attr('id');
            $('#'+id+'.'+type).css('backgroundColor', 'gainsboro');
            $('.notification p.iname').css('color','darkred');
            $('#'+id+'.'+type+' p.iname').css('color','red');
            $('#'+id+'.'+type+' p.imessage').css('color','gray');

            $.ajax({
                type: 'POST',
                url: './ajax/check.php',
                data: {id : id, type : type},
                success: function (response){

                },
                error: ()=> {console.log(error)}
            })

        if(type == 0){
            $.ajax({
                type: 'POST',
                url: './ajax/taskDetails.php',
                data: { id : id, note : 1},
                success: function (response){
                    $("#iview1").css("display","block")
                    $("#add1").css("display","none");
                    $("#add2").css("display","none");
                    $("#add3").css("display","none");
                    $('.nothing').css("display","none");
                    $('#divPom, #divdat, #icomplete').css('display','block');
                    $('#itaskId').text(id);
                    $('#iName').text(response[0]['task_name']);
                    $('#iDate').text(response[0]['due_date']);
                    $('#divAss').html(' <span class="label" >Assigned To:</span><br>\n' +
                        '<span id="iAssignee"></span>')
                    $('#iAssignee').text(response[0]['name']);
                    $('#iProject').text(response[0]['team_name']+ ' - ' + response[0]['project_name']);
                    $('#iDescription').text(response[0]['description']);
                    $('#ipomodoro').text(response[0]['pomodoro']);
                    $('#divBy').html('<span class="label">By:</span><br>\n' +
                        '<span id="iAssigner"></span>')
                    $('#iAssigner').text(response[0]['assigner']);
                    $('#imess').text($('#'+id+'.'+type+' p.imessage').text());

                    if(response[0]['isComplete'] == '1'){
                        $('#icomplete').css({color: 'white', backgroundColor: 'green'});
                        $('#icomplete').html('<i class="fas fa-check-circle"></i><span>completed</span>');
                    }else{
                        $('#icomplete').css({color: 'gray', backgroundColor: 'white'});
                        $('#icomplete').html('<i style="color: gray" class="far fa-check-circle"></i><span>Incomplete</span>');
                    }
                },
                dataType: 'json',
                error: ()=> {console.log(error)}
            })
        }else if(type == 1){
            $.ajax({
                type: 'POST',
                url: './ajax/projectDetails.php',
                data: { id : id, note: 1},
                success: function (response){
                    $("#iview1").css("display","block")
                    $("#add1").css("display","none");
                    $("#add2").css("display","none");
                    $("#add3").css("display","none");
                    $('.nothing').css("display","none");
                    $('#itaskId').text(id);
                    $('#iName').text(response[0]['project_name']);
                    $('#divAss').html(' <span class="label" >Members:</span><br>\n' +
                        '<span id="iAssignee"></span>')
                    $.each(response[0]['members[]'], function (key, value){
                        document.getElementById('iAssignee').innerHTML += '<p>'+value['member']+'</p>'
                    })
                    $('#iProject').text(response[0]['team_name']);
                    $('#iDescription').text(response[0]['description']);
                    $('#divBy').html('<span class="label">Created By:</span><br>\n' +
                        '<span id="iAssigner"></span>')
                    $('#iAssigner').text(response[0]['name']);
                    $('#divPom, #divdat, #icomplete').css('display','none');
                    $('#imess').text($('#'+id+'.'+type+' p.imessage').text());

                },
                dataType: 'json',
                error: ()=> {console.log(error)}
            })
        }else if(type == 2){
            $.ajax({
                type: 'POST',
                url: './ajax/teamDetails.php',
                data: { id : id},
                success: function (response){
                    $("#iview1").css("display","block")
                    $("#add1").css("display","none");
                    $("#add2").css("display","none");
                    $("#add3").css("display","none");
                    $('.nothing').css("display","none");
                    $('#itaskId').text(id);
                    $('#iName').text(response[0]['team_name']);
                    $('#divAss').html(' <span class="label" >Members:</span><br>\n' +
                        '<span id="iAssignee"></span>')
                    $.each(response[0]['members[]'], function (key, value){
                        document.getElementById('iAssignee').innerHTML += '<p>'+value['member']+'</p>'
                    })
                    $('#iDescription').text(response[0]['description']);
                    $('#divBy').html('<span class="label">Created By:</span><br>\n' +
                        '<span id="iAssigner"></span>')
                    $('#iAssigner').text(response[0]['name']);
                    $('#divPom, #divdat, #icomplete, #ilabel').css('display','none');
                    $('#imess').text($('#'+id+'.'+type+' p.imessage').text());


                },
                dataType: 'json',
                error: ()=> {console.log(error)}
            })
        }

        })
    }
    $('#icheck').click(()=>{
        let id = $('.id').attr('id');
        if(id == 0){
            $('#icheck').css({backgroundColor: 'green', color: 'white'}).html('<i class="fas fa-check-circle id" id="1"></i><span>Unread only</span>');
            start();
        }else{
            $('#icheck').css({backgroundColor: 'white', color: 'gray'}).html('<i style="color: gray" class="far fa-check-circle id" id="0"></i><span>Unread only</span>');
            start();
        }
    })
    function date(date){
        let day;
        let created_at = new Date(date)
        let time = created_at.toLocaleTimeString([], { hour: '2-digit', minute: "2-digit" });
            let y = (created_at.setHours(0,0,0,0) - new Date().setHours(0,0,0,0) )/86400000;
        if(y === 0){
            day = '<span style="color: green;" class="inow">'+time+' <br> today</span>';
        }else if(y === -1){
            day = '<span style="color: green;" class="inow">'+time+' <br> yesterday</span>';
        }else if(y < -1){
            switch (created_at.getMonth()) {
                case 0:
                    day = '<span style="color: blue;" class="inow">'+time+' <br> Jan '+created_at.getDate()+'</span>';
                    break;
                case 1:
                    day = '<span style="color: blue;" class="inow">'+time+' <br> Feb '+created_at.getDate()+'</span>';
                    break;
                case 2:
                    day = '<span style="color: blue" class="inow">'+time+' <br> Mar '+created_at.getDate()+'</span>';
                    break;
                case 3:
                    day = '<span style="color: blue" class="inow">'+time+' <br> Apr '+created_at.getDate()+'</span>';
                    break;
                case 4:
                    day = '<span style="color: blue" class="inow">'+time+' <br> May '+created_at.getDate()+'</span>';
                    break;
                case 5:
                    day = '<span style="color: blue" class="inow">'+time+' <br> Jun '+created_at.getDate()+'</span>';
                    break;
                case 6:
                    day = '<span style="color: blue" class="inow">'+time+' <br> Jul '+created_at.getDate()+'</span>';
                    break;
                case 7:
                    day = '<span style="color: blue" class="inow">'+time+' <br> Aug '+created_at.getDate()+'</span>';
                    break;
                case 8:
                    day = '<span style="color: blue" class="inow">'+time+' <br> Sep '+created_at.getDate()+'</span>';
                    break;
                case 9:
                    day = '<span style="color: blue" class="inow">'+time+' <br> Oct '+created_at.getDate()+'</span>';
                    break;
                case 10:
                    day = '<span style="color: blue" class="inow">'+time+' <br> Nov '+created_at.getDate()+'</span>';
                    break;
                case 11:
                    day = '<span style="color: blue" class="inow">'+time+' <br> Dec '+created_at.getDate()+'</span>';
                    break;
            }
        }
        return day;
    }
})