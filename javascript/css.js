const backgroundSelector = document.querySelector('.bg-move-hover');
const windowWidth = window.innerWidth / 5;
const windowHeight = window.innerHeight / 5;

backgroundSelector.addEventListener('mousemove', (e) => {
  const mouseX = e.clientX / windowWidth;
  const mouseY = e.clientY / windowHeight;
  
  backgroundSelector.style.transform = `translate3d(-${mouseX}%, -${mouseY}%, 0)`;
});
