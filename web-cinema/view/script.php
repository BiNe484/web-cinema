<?php 
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
    // Xác thực đăng kí đăng nhập
    function submitData() {
        event.preventDefault();
        $(document).ready(function() {
            let data = {
                name: $('#name').val(),
                birthday: $('#birthday').val(),
                username: $("#username").val(),
                password: $("#password").val(),
                email: $("#email").val(),
                phonenumber: $("#phonenumber").val(),
                confirm_password: $("#confirmpassword").val(),
                action: $("#action").val(),
            }
            $.ajax({
                url: '../controller/function.php',
                type: 'post',
                data: data,
                success: function(response) {
                    alert(response);
                    if(response == "Đăng nhập thành công") {
                        window.location.href = "../index.php"
                    }
                    if(response == "Đăng kí thành công") {
                        window.location.href = "./login-gui.php"
                    }
                }
            })
        }) 
    }

    //Đến trang chọn ghế
    function goToChooseChair(showTimeCode) {
        $(document).ready(function() {
            $.ajax({
                url: './controller/C_Chair.php',
                type: 'post',
                data: {showTimeCode: showTimeCode},
                success: function(response) {
                    if(response.trim() != "GoToChair") {
                        alert(response);
                    }else {
                        window.location.href = "./view/user/choose-Chair-gui.php"
                    }
                }
            })
        })
    }

    // Đi đến đăng nhập
    function goToLogin() {
        window.location.href = "./login-gui.php";
    }

    //Xử lí trang chọn ghế khi bấm next
    function chooseChairs() {
        let chairsSelected = Object.values(document.querySelectorAll(".selected"));
        let chairsName = [];
        if(chairsSelected) {
            chairsName = chairsSelected.map(function(selector) {
                return selector.innerText;
            })
        }
        let data = {
            chairs: chairsName
        }
        $(document).ready(function() {
            $.ajax({
                url: '../../controller/C_ChairConfirm.php',
                type: 'post',
                data: data,
                success: function(response) {
                    if(response.trim() == "GoToIndex") {
                        window.location.href = "../../index.php"
                    }else if(response.trim() == "GoToBookFood"){
                        window.location.href = "./choose-F&B-gui.php"
                    }else {
                        alert(response);
                    }
                }
            })
        })
    }

    //Gửi mã xác thực qua email
    function sendCode(event) {
        event.preventDefault(); // Prevent the default form submission behavior
        $(document).ready(function() {
            let data = {
                fname: $('#fname').val(), // Assuming #username represents the username field
                toemail: $('#toemail').val(),   // Correcting to match the ID of the email input field
            }
            $.ajax({
                url: '../PHPMailer/index.php',
                type: 'post',
                data: data,
                success: function(response) {
                    if(response =='Đã xảy ra lỗi. Vui lòng thử lại sau' || response =='Email không trùng khớp' ){
                        alert(message); // Display the success message
                    }
                    else{
                        var data = JSON.parse(response); // Parse the JSON response
                        var message = data.message; // Extract success message
                        var code = data.code; // Extract verification code
                        alert(message); // Display the success message
                        // Pass the verification code to the HTML for comparison with user input
                        $('#verification_code').val(code); // Assuming #verification_code represents a hidden input field
                    }
                }
            })
        })
    }
    //Kiểm tra mã xác thực
    function checkCode(event) {
        event.preventDefault();
        $(document).ready(function() {
            let data = {
                fname: $('#fname').val(), // Assuming #username represents the username field
                toemail: $('#toemail').val(),   // Correcting to match the ID of the email input field
                npassword: $('#npassword').val(),
                code: $('#code').val(),
                verification_code: $('#verification_code').val()
            }
            $.ajax({
                url: '../controller/C_User.php',
                type: 'post',
                data: data,
                success: function(response) {
                    if(response == "Đã đổi mật khẩu"){
                        alert(response);
                        window.location.href = "./login-gui.php"
                    }
                    else{
                        alert(response);
                    }
                }
            })
        })
    }

    // Chọn bắp nước
    function chooseFAndB() {
        let bapNuocs = {}
        for(var i = 0; i<document.querySelectorAll(".combo1b1n-parent").length;i++){
            let maBapNuoc = document.querySelectorAll(".combo1b1n-parent")[i].querySelector("input.maBapNuoc").value;
            let quantity = document.querySelectorAll(".combo1b1n-parent")[i].querySelector("input.combo1b1nselection").value;
            if(quantity != 0) {
                bapNuocs[maBapNuoc] = quantity;
            }
        }
        $(document).ready(function() {
            $.ajax({
                url: '../../controller/C_BapNuoc.php',
                type: 'post',
                data: bapNuocs,
                success: function(response) {
                    if(response.trim() == "GoToIndex") {
                        window.location.href = "../../index.php"
                    }else {
                        // alert(response);
                        window.location.href = "./my-Ticket-gui.php"
                    }
                }
            })
        })
    }

    function addFilm(event) {
        event.preventDefault();
        //dữ liệu không liên quan đến file 

        let data = {
            name: $('#name').val(),
            born: $('#born').val(),
            release: $('#release').val(),
            category: $('#category').val(),
            director: $('#director').val(),
            nation: $('#nation').val(),
            language: $('#language').val(),
            sub: $('#sub').val(),
            actors: $('#actors').val(),
            sum: $('#sum').val(),
            duration: $('#duration').val(),
            banner: $('#banner-ip').prop('files')?.[0]?.['name'],
            trailer: $('#trailer').val(),
            miniPictures: {
                pic1: $('#mini-picture1').prop('files')?.[0]?.['name'],
                pic2: $('#mini-picture2').prop('files')?.[0]?.['name'],
                pic3: $('#mini-picture3').prop('files')?.[0]?.['name'],
                pic4: $('#mini-picture4').prop('files')?.[0]?.['name']
            }
        };

        
        $.ajax({
            url: '../../controller/C_Film.php',
            type: 'post',
            data: data,
            success: function(response) {
                if (response == "Thêm thành công") {
                    alert(response);
                    //dữ liệu liên quan đến file 
                    let fileDatas = {
                        banner: $('#banner-ip').prop('files')?.[0],
                        miniPictures1: $('#mini-picture1').prop('files')?.[0],
                        miniPictures2: $('#mini-picture2').prop('files')?.[0],
                        miniPictures3: $('#mini-picture3').prop('files')?.[0],
                        miniPictures4: $('#mini-picture4').prop('files')?.[0]
                    }
            
                    // 2. FormData with Blobs (more complex)
                    var formData = new FormData();
                    let filePromises = [];
                    
                    for (let key in fileDatas) {
                        if (fileDatas.hasOwnProperty(key)) {
                            let file = fileDatas[key]; 
                            if(file instanceof File) {
                                filePromises.push(new Promise((resolve, reject) => {
                                    resolve({file: file});
                                }))
                            }
                        }
                    }
            
                    Promise.all(filePromises)
                        .then(processedFiles => {
                            for (let processedFile of processedFiles) {
                                formData.append("files[]", processedFile.file);
                            }
                            return formData;            
                        })
                        .then(function(formData) {
                            $.ajax({
                                url: '../../controller/C_UploadFilm.php',
                                type: 'POST',
                                data: formData,
                                processData: false, // Don't process data automatically (for FormData)
                                contentType: false, // Set content type manually (for FormData)
                                success: function(response) {
                                    location.reload();
                                }
                            });
                        })
                        .catch(error => {
                            console.error("Error processing files:", error); // Handle errors
                        });
                } else {
                    alert(response);
                }
            }
        })


    }

    function addSuat(event) {
        event.preventDefault();
        $(document).ready(function() {
            let data = {
                movieId: $('#movieid').val(),
                roomId: $('#roomid').val(),
                release: $('#rl').val(),
                time: $('#time').val(),
                timeend: $('#timeend').val()
            };
            $.ajax({
                url: '../../controller/C_Suat.php',
                type: 'post',
                data: data,
                success: function(response) {
                    if (response.trim() == "Thêm thành công") {
                        alert(response);
                        location.reload();
                    } else {
                        event.preventDefault()
                        alert(response);
                    }
                }
            })
        })
    }

    function GoToPay() {
        event.preventDefault();
        data = {
            maVe: $(event.target).closest(".payment_form").find(".maVeInput").val(),
            tongTien: $(event.target).closest(".payment_form").find(".tongTienInput").val(),
            kindPayment: $(event.target).closest(".payment_form").find(".kindPayment").val(),
        }
        $.ajax({
            url: '../../controller/C_Ticket.php',
            type: 'post',
            data: data,
            success: function(response) {
                if(response.trim() == "momo_qr") {
                    window.location.href = "./payment/xulithanhtoanmomo.php";
                }else if(response.trim() == "momo_atm") {
                    window.location.href = "./payment/xulithanhtoanmomo_atm.php";
                }else if(response.trim() == "vnpay") {
                    window.location.href = "./payment/xulithanhtoanvnpay.php";
                }
            }
        })
    }
</script>
