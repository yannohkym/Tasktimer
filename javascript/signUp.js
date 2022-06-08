$(document).ready(()=> {
    let status;
    $('#fName').keyup(()=>{
        name('#fName', '#fNameErr');
    })

    $('#lName').keyup(()=>{
        name('#lName', '#lNameErr');
    })

    $('#email').keyup(()=>{
        emailVal($('#email').val());
    })

    $("#pass").keyup(()=>{
        passVal($('#pass').val());
    })

    $("#cpass").keyup(()=>{
        confirmPass($('#pass').val(), $('#cpass').val());
    })

    $('form').on('submit', function (e){
       status = name('#fName', '#fNameErr') &&
           name('#lName', '#lNameErr') &&
           emailVal($('#email').val()) &&
           passVal($('#pass').val()) &&
           confirmPass($('#pass').val(), $('#cpass').val());

        if(!(status)){
            e.preventDefault();
            e.stopPropagation();
            $("#err").css("display","none");
            $("#alert1").css("display","block");
        }
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

    function passVal(pass){
        if(pass === ''){
            $("#passErr").text('field required').css("display","block");
            return false;
        }else if(!(pass.length >=4)){
            $('#passErr').text('Sorry! minimum length = 4').css("display", "block");
            return false;
        }else{
            $("#passErr").css("display","none");
            return true;
        }
    }

    function confirmPass(pass, confirm_pass){
        if(pass !== confirm_pass){
            $('#cpassErr').text('Sorry! password should match!').css("display", "block");
            return false
        }else{
            $("#cpassErr").css("display","none");
            return true;
        }
    }



    function name(id, iderr){
        let regex = /^[a-zA-Z]+$/gi;
        let string = $(id).val();
        if(string === ''){
            $(iderr).text('field required').css("display","block");
            return false;
        }else if(!regex.test(string)) {
            $(iderr).text('Sorry! letters only').css("display", "block");
            return false;
        }else if(!(string.length >= 2)) {
            $(iderr).text('Sorry! minimum length = 2').css("display", "block");
            return false
        }else {
            $(iderr).css("display","none");
            return true
        }
    }
})