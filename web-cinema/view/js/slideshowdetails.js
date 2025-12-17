let slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("picture-banner");
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
    }
    slides[slideIndex-1].style.display = "block";
  }
function trailershow(){
  let video = document.getElementsByClassName("trailer");

}

function toggle(){
  var trailer =  document.querySelector(".video")
  trailer.classList.toggle("active")
}