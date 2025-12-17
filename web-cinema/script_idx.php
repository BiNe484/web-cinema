
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">

    function needToLogin() {
        window.location.href = "view/login-gui.php";
    }

    function selectDate(selectedDate) {
        // Gửi giá trị mới lên server bằng Ajax
        $(document).ready(function() {
            $.ajax({
                type: "post",
                url: "./controller/C_SelectorDate.php",
                data: { selectedDate: selectedDate },
                success: function(result) {
                    location.reload(); // Tải lại trang sau khi đã cập nhật giá trị
                }
            });
        })
    }

</script>
