function openTab(tabId) {
  // Ẩn tất cả các phần tử với class 'main-content'
  var tabs = document.getElementsByClassName('main-content');
  for (var i = 0; i < tabs.length; i++) {
    tabs[i].style.display = 'none';
  }

  // Gỡ bỏ class 'active' khỏi tất cả các nút
  var buttons = document.getElementsByTagName('button');
  for (var i = 0; i < buttons.length; i++) {
    buttons[i].classList.remove('active');
  }

  // Hiển thị tab hiện tại và thêm class 'active' vào nút tương ứng
  document.getElementById(tabId).style.display = 'block';
  document.getElementById('btn-' + tabId).classList.add('active');
}

