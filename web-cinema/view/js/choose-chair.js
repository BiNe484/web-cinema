document.addEventListener("DOMContentLoaded", function() {
    const cinemaSeats = document.querySelector(".cinema-seats");
    const tooltip = document.querySelector(".tooltip");
    const selectedSeat = document.querySelector(".selected-seat");
    let selectedSeats = []; // Mảng lưu trữ các ghế được chọn

    // Số hàng và số ghế mỗi hàng
    const rows = 8; // 8 hàng từ A đến H
    const seatsPerRow = 13; // Mỗi dãy có 12 ghế

    const alphabet = "ABCDEFGH"; // Tên của các hàng

    // Tạo ra các ghế
    for (let row = 0; row < rows; row++) {
        const rowElement = document.createElement("div");
        rowElement.classList.add("row_seat");
        for (let seat = 1; seat <= seatsPerRow; seat++) {
            const seatElement = document.createElement("div");
            seatElement.classList.add("seat");
            seatElement.setAttribute("data-row", alphabet[row]);
            seatElement.setAttribute("data-seat", seat);
            seatElement.textContent = `${alphabet[row]}${seat}`;
            seatElement.setAttribute("title", `Hàng ${alphabet[row]}, Ghế ${seat}`); // Thêm title để hiển thị thông báo khi di chuột qua
            rowElement.appendChild(seatElement);
            if(!['A','B','C'].includes(alphabet[row])) {
                seatElement.classList.add("seat_vip");;
            }
        }
        cinemaSeats.appendChild(rowElement);
    }

    // Lắng nghe sự kiện click trên ghế
    cinemaSeats.addEventListener("click", function(event) {
        if (event.target.classList.contains("occupied")) {
            return;
        }
        if (event.target.classList.contains("seat")) {
            const row = event.target.getAttribute("data-row");
            const seat = event.target.getAttribute("data-seat");
            const seatInfo = `${row}${seat}`; // Thay đổi thông tin ghế để hiển thị dưới dạng "D7"
            if (selectedSeats.includes(seatInfo)) {
                // Nếu ghế đã được chọn, bỏ chọn ghế đó
                selectedSeats = selectedSeats.filter(item => item !== seatInfo);
                event.target.classList.remove("selected");
            } else if (selectedSeats.length < 5) {
                // Nếu chưa đạt tối đa 5 ghế được chọn, thêm ghế vào mảng và đánh dấu ghế là được chọn
                selectedSeats.push(seatInfo);
                event.target.classList.add("selected");
            } else {
                // Nếu đã đạt tối đa 5 ghế được chọn, không cho phép chọn thêm ghế
                return;
            }
            // Hiển thị thông tin ghế trong phần tooltip
            selectedSeat.textContent = `${selectedSeats.map(seat => seat.replace(/([A-H])(\d+)/, "$1$2")).join(", ")}`; // Sử dụng regex để loại bỏ phần "Hàng" và "Ghế"
            tooltip.classList.add("active");
            setTimeout(() => {
                tooltip.classList.remove("active");
            }, 3000); // Hiển thị trong 3 giây và sau đó ẩn đi
        }
    });
});