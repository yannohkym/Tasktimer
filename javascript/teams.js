$(document).ready(()=>{
    let id = $('.teamN').attr('id');
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
    $('.trash').click(function (){
        let uid = $(this).attr('id');
        $.ajax({
            type: 'POST',
            url: './ajax/pMember.php',
            data: {id: uid, updateTeam: id},
            success: function (response) {
                location.reload();
                $('#vtoast').html('<div class="toast" role="alert" aria-live="assertive" aria-atomic="true"> ' +
                    '<div class="toast-body"> ' +
                    '   <i style="color: green" id="check" class="fas fa-check-circle"></i> ' +
                    '   <span style="padding-left: 5px">Member removed successfully</span> ' +
                    '   <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close"> ' +
                    '   <span aria-hidden="true">&times;</span> ' +
                    '</button>' +
                    '</div> ' +
                    '</div>')
;            },
            error: () => {
                console.log(error)
            }

        })
    })
    $(".Amem").click(function (){
      $('#addmem').toggle();
    })

    $('#email').keyup(()=>{
        emailVal($('#email').val());
    })

    function emailVal(email){
        let emailReg = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        if(email === ''){
            $("#emailErr").text('field required').css("display","block");
            return false;
        }else if(!emailReg.test(email)){
            $("#emailErr").text('Sorry! enter a valid email').css("display", "block");
            return false;
        }else{
            $("#emailErr").css("display","none");
            return true;
        }
    }
})