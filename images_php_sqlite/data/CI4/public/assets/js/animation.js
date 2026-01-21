let currentCounts = {
    burger: 0,
    sandwich: 0
};

function spawnFallingFood(src, className) {

    if (currentCounts[className] >= 20) {
        return; 
    }

    currentCounts[className]++;
    const el = document.createElement("img");
    el.src = src;
    el.classList.add(className);
    el.style.left = Math.random() * 100 + "vw";
    const size = 4 + Math.random() * 6;
    el.style.width = size + "vw";
    el.style.animationDuration = (4 + Math.random() * 4) + "s";
    document.body.appendChild(el);
    el.addEventListener("animationend", () => {
        if (currentCounts[className] > 0) {
            currentCounts[className]--;
        }
        el.remove();
    });
}

setInterval(() => spawnFallingFood("/CI4/public/assets/images/burger.png", "burger"), 400);
setInterval(() => spawnFallingFood("/CI4/public/assets/images/sandwich.png", "sandwich"), 400);

console.log("animation du fond");
