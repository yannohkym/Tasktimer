$(document).ready(()=>{
    let x = window.matchMedia("(max-width: 500px)");
       $(".closebtn").click(()=>{
        document.getElementById("navbar").style.width = "0";
        document.body.style.gridTemplateColumns = "0fr 1fr";
        $(".span").css("display","block");
    })
    $("#dropdown").click(()=>{
        if(x.matches){
            document.getElementById("navbar").style.width = "60%";
        }else{
            document.getElementById("navbar").style.width = "20%";
        }
        document.body.style.gridTemplateColumns = "0fr 1fr";
        $(".span").css("display","none");
    })
    $("#addT").click(()=>{
        $("#add1").css("display","block").removeClass('animate__animated animate__fadeOutUpBig').addClass('animate__animated animate__backInDown');
        $("#add2").css("display","none");
        $("#add3").css("display","none");
        $("#view1").css("display","none");
    })
    $(".clse").click(()=>{
        $(".add").removeClass('animate__animated animate__backInDown').addClass('animate__animated animate__fadeOutUpBig');
    })
    $("#addP").click(()=>{
        $("#add2").css("display","block").removeClass('animate__animated animate__fadeOutUpBig').addClass('animate__animated animate__backInDown');
        $("#add1").css("display","none");
        $("#add3").css("display","none");
        $("#view1").css("display","none");
    })
    $("#addTm").click(()=>{
        $("#add3").css("display","block").removeClass('animate__animated animate__fadeOutUpBig').addClass('animate__animated animate__backInDown');
        $("#add1").css("display","none");
        $("#add2").css("display","none");
        $("#view1").css("display","none");
    })
    $(function (){

        $('.date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm',
            stepping: 15,
            icons: {
                date: 'far fa-calendar-alt',
                time: 'far fa-clock'
            }
        });
    })
    ajax()
    function ajax (){
        $.ajax({
            type: 'POST',
            url: './ajax/checked.php',
            success: function (response){
               if(response.length != 0){
                   $('.mytask span').css('display','initial').text(response.length);
                   $('#bell').css('display','inline-block');
               }
            },
            dataType: 'json',
            error: ()=> {console.log(error)}
        })
    };
    $("#tProject").change(function (){
        $.ajax({
            type: 'POST',
            url: './ajax/pMember.php',
            data: { pId : $('select#tProject option:selected').val()},
            success: function (response){
                $('.aOpt').remove();
              $.each(response, function (key, value){
                  if(value.hasOwnProperty('category')){
                      document.getElementById('proCat').innerHTML += "<option class='aOpt' value=" + value['id'] + ">" + value['category'] + "</option>";
                  }else {
                      document.getElementById('tAssignee').innerHTML += "<option class='aOpt' value=" + value['user_id'] + ">" + value['email'] + "</option>";
                  }
              })
            },
            dataType: 'json',
            error: ()=> {console.log(error)}
        })

    });
    $("#team").change(function (){
        $.ajax({
            type: 'POST',
            url: './ajax/tMember.php',
            data: { pId : $('select#team option:selected').val()},
            success: function (response){
                $('.aOpt').remove();
                if(response.length == 0){
                    $('#pAssignee').removeAttr('multiple','multiple');
                }else{
                    $('#teaMem div.ms-parent').remove();
                    $.each(response, function (key, value){
                        document.getElementById('pAssignee').innerHTML += "<option class='aOpt' value=" + value['user_id'] + ">" + value['email'] + "</option>";
                    })
                    $('#pAssignee').attr('multiple','multiple');

                    $(function() {
                        $('#pAssignee').multipleSelect({
                            multiple: true,
                            multipleWidth: 60
                        })
                        $('.ms-parent').removeClass('ms-offscreen').css("width", "100%");
                    })
                }
            },
            dataType: 'json',
            error: ()=> {console.log(error)}
        })
    });

    $("#vProject").change(function (){
        vedit();
    });

    function vedit(){
        $.ajax({
            type: 'POST',
            url: './ajax/pMember.php',
            data: { id : $('select#vProject option:selected').val()},
            success: function (response){
                $('.aOpt').remove();
                console.log(response);
                $.each(response, function (key, value){
                    if(value.hasOwnProperty('category')){
                        document.getElementById('vCategory').innerHTML += "<option class='aOpt' value=" + value['id'] + ">" + value['category'] + "</option>";
                    }else {
                        document.getElementById('vAssignee').innerHTML += "<option class='aOpt' value=" + value['user_id'] + ">" + value['email'] + "</option>";
                    }
                })
            },
            dataType: 'json',
            error: ()=> {console.log(error)}
        })
    }



    $('.taskName').click(function () {
        let id = $(this).attr('id');
        $("#vsubmit.vedit").css('display', 'none');

        $.ajax({
            type: 'POST',
            url: './ajax/taskDetails.php',
            data: { id : id},

            success: function (response){
                console.log(response);
                $("#view1").css("display","block").removeClass('animate__animated animate__fadeOutUpBig').addClass('animate__animated animate__backInDown');
                $("#add1").css("display","none");
                $("#add2").css("display","none");
                $("#add3").css("display","none");
                console.log($("#vsubmit.vedit"));
                $('input#vtaskId').val(id);
                $('input#vName').val(response[0]['task_name']);
                $('input#vDate').val(response[0]['due_date']);
                $('select#vAssignee').html('<option class="aOpt" value="'+response[0]['member']+'" selected>'+response[0]['name']+'</option>');
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
                            console.log(response);
                            if(response == '1'){
                                $('#complete').css({color: 'white', backgroundColor: 'green'});
                                $('#complete').html('<i class="fas fa-check-circle"></i>completed');
                            }else{
                                $('#complete').css({color: 'gray', backgroundColor: 'white'});
                                $('#complete').html('<i style="color: gray" class="far fa-check-circle"></i>Mark as complete');
                            }
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

    $('#vedit').click(function (){
        $("#vform :input").prop("disabled", false).css('background-color','white');
        $("#vsubmit").css('display', 'block');
        $("#vedit").css('display', 'none');
        $('#vAssign').css('flexDirection', 'column-reverse')
        $('#vIn').text("project");
        $('#By').remove();
        vedit();
    })

    $('#navteam').click(function (){
        if($('#teamlist').css('display') == 'none'){
            $('#teamlist').css('display','block');
            $('#navteam').html('<i class="fas fa-caret-down"></i>Teams')
        }else{
            $('#teamlist').css('display','none');
            $('#navteam').html('<i class="fas fa-caret-right"></i>Teams')
        }
    })

    $('.toast').toast('show');
    $('.close').click(()=>{
        $('.toast').toast('hide');
    })

    $('.teamG').click(function (){
        let id = $(this).attr('id');
       window.location.href = './teams.php?tid='+id;
        })
})

// $('.ms-parent').remove();
// $(function() {
//     $('#tAssignee').multipleSelect({
//         selectAll: false,
//         multiple: false,
//         multipleWidth: 60
//     })
//     $('.ms-parent').removeClass('ms-offscreen').css("width", "100%");
//  })