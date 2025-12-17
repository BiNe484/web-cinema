//Banner
const banner = document.getElementById("banner");
const input = document.getElementById("banner-ip");
input.addEventListener("change", () => {
  banner.src = URL.createObjectURL(input.files[0]);
  
});
//Pic1
const banner1 = document.getElementById("pic1");
const input1 = document.getElementById("mini-picture1");
input1.addEventListener("change", () => {
  banner1.src = URL.createObjectURL(input1.files[0])
});
//Pic2
const banner2 = document.getElementById("pic2");
const input2 = document.getElementById("mini-picture2");
input2.addEventListener("change", () => {
  banner2.src = URL.createObjectURL(input2.files[0])
});
//Pic3
const banner3 = document.getElementById("pic3");
const input3 = document.getElementById("mini-picture3");
input3.addEventListener("change", () => {
  banner3.src = URL.createObjectURL(input3.files[0])
});
//Pic4
const banner4 = document.getElementById("pic4");
const input4 = document.getElementById("mini-picture4");
input4.addEventListener("change", () => {
  banner4.src = URL.createObjectURL(input4.files[0])
});

