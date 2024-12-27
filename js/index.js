import "../css/style.css";

document.addEventListener("DOMContentLoaded", () => {
  const slider = document.querySelector(".img_holder");
  const images = document.querySelectorAll(".img_holder img");
  const prevBtn = document.getElementById("prev_btn");
  const nextBtn = document.getElementById("next_btn");
  let currentIndex = 0;

  function updateSlider() {
    const offset = -currentIndex * 100; // Move by 100% of the container width per image
    slider.style.transform = `translateX(${offset}%)`;
  }

  prevBtn.addEventListener("click", (e) => {
    e.preventDefault();
    if (currentIndex > 0) {
      currentIndex--;
      updateSlider();
    }
  });

  nextBtn.addEventListener("click", (e) => {
    e.preventDefault();
    if (currentIndex < images.length - 1) {
      currentIndex++;
      updateSlider();
    }
  });
});
