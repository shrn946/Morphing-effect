// morphing-text-script.js

const elements = {
    text1: document.getElementById("text1"),
    text2: document.getElementById("text2"),
};

// Controls the speed of morphing.
const morphTime = 1;
const cooldownTime = 0.25;

let textIndex = 0;
let time = new Date();
let morph = 0;
let cooldown = cooldownTime;

elements.text1.textContent = "";
elements.text2.textContent = "";

function doMorph() {
    morph -= cooldown;
    cooldown = 0;

    let fraction = morph / morphTime;

    if (fraction > 1) {
        cooldown = cooldownTime;
        fraction = 1;
    }

    setMorph(fraction);
}

function setMorph(fraction) {
    elements.text2.style.filter = `blur(${Math.min(8 / fraction - 8, 100)}px)`;
    elements.text2.style.opacity = `${Math.pow(fraction, 0.4) * 100}%`;

    fraction = 1 - fraction;
    elements.text1.style.filter = `blur(${Math.min(8 / fraction - 8, 100)}px)`;
    elements.text1.style.opacity = `${Math.pow(fraction, 0.4) * 100}%`;

    elements.text1.textContent = texts[textIndex % texts.length];
    elements.text2.textContent = texts[(textIndex + 1) % texts.length];
}

function doCooldown() {
    morph = 0;

    elements.text2.style.filter = "";
    elements.text2.style.opacity = "100%";

    elements.text1.style.filter = "";
    elements.text1.style.opacity = "0%";
}

function animate() {
    requestAnimationFrame(animate);

    let newTime = new Date();
    let shouldIncrementIndex = cooldown > 0;
    let dt = (newTime - time) / 1000;
    time = newTime;

    cooldown -= dt;

    if (cooldown <= 0) {
        if (shouldIncrementIndex) {
            textIndex++;
        }

        doMorph();
    } else {
        doCooldown();
    }
}

animate();
