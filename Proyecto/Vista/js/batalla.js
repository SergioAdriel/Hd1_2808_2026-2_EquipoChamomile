// ============================================
// JAVASCRIPT PARA LA PANTALLA DE CARGA
// ============================================

const mensajesEpicos = [
    "🔥 ¡El campo de batalla arde!",
    "⚡ ¡Relámpagos cruzan el cielo!",
    "💧 ¡Las olas del océano se levantan!",
    "🌿 ¡La naturaleza responde al llamado!",
    "👊 ¡Los entrenadores se preparan!",
    "🎯 ¡Apuntando al Pokémon rival!",
    "💪 ¡El poder Pokémon se libera!",
    "✨ ¡Brilla el espíritu de batalla!",
    "🏆 ¡Solo uno puede quedar en pie!"
];

let poderActual = 0;
let mensajeIndex = 0;
let intervaloPoder;
let intervaloMensajes;

// Función para crear explosión
function crearExplosion(x, y) {
    for (let i = 0; i < 30; i++) {
        const explosion = document.createElement('div');
        explosion.innerHTML = '💥';
        explosion.style.position = 'fixed';
        explosion.style.left = x + (Math.random() - 0.5) * 100 + 'px';
        explosion.style.top = y + (Math.random() - 0.5) * 100 + 'px';
        explosion.style.fontSize = (Math.random() * 30 + 20) + 'px';
        explosion.style.pointerEvents = 'none';
        explosion.style.zIndex = '2000';
        explosion.style.animation = `explosionFloat ${Math.random() * 0.5 + 0.5}s ease-out forwards`;
        document.body.appendChild(explosion);
        setTimeout(() => explosion.remove(), 500);
    }
}

// Función para crear chispas
function crearChispas(x, y) {
    for (let i = 0; i < 20; i++) {
        const chispa = document.createElement('div');
        chispa.innerHTML = '⚡';
        chispa.style.position = 'fixed';
        chispa.style.left = x + (Math.random() - 0.5) * 80 + 'px';
        chispa.style.top = y + (Math.random() - 0.5) * 80 + 'px';
        chispa.style.fontSize = (Math.random() * 20 + 15) + 'px';
        chispa.style.pointerEvents = 'none';
        chispa.style.zIndex = '2000';
        chispa.style.animation = `chispaFloat ${Math.random() * 0.3 + 0.3}s ease-out forwards`;
        document.body.appendChild(chispa);
        setTimeout(() => chispa.remove(), 300);
    }
}

// Función para temblar la pantalla
function shakeScreen() {
    document.body.style.animation = 'shake 0.1s ease-in-out 0s 5';
    setTimeout(() => {
        document.body.style.animation = '';
    }, 500);
}

// Agregar keyframe de shake si no existe
if (!document.querySelector('#shakeKeyframe')) {
    const style = document.createElement('style');
    style.id = 'shakeKeyframe';
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-8px); }
            75% { transform: translateX(8px); }
        }
        @keyframes explosionFloat {
            0% { opacity: 1; transform: scale(0.5); }
            100% { opacity: 0; transform: scale(2); }
        }
        @keyframes chispaFloat {
            0% { opacity: 1; transform: translateY(0) rotate(0deg); }
            100% { opacity: 0; transform: translateY(-50px) rotate(180deg); }
        }
        @keyframes criticalHit {
            0% { transform: scale(0.5); opacity: 0; }
            50% { transform: scale(1.5); opacity: 1; text-shadow: 0 0 20px red; }
            100% { transform: scale(1); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
}

function agregarMensaje(mensaje, esEspecial = false) {
    const container = document.getElementById('battleMessages');
    const msgDiv = document.createElement('div');
    msgDiv.className = 'battle-message';
    msgDiv.innerHTML = esEspecial ? `✨ <strong>${mensaje}</strong> ✨` : `⚔️ ${mensaje}`;
    container.appendChild(msgDiv);
    container.scrollTop = container.scrollHeight;
    while (container.children.length > 8) {
        container.removeChild(container.firstChild);
    }
}

// Función para crear efecto CRÍTICO
function crearEfectoCritico(x, y) {
    const critDiv = document.createElement('div');
    critDiv.innerHTML = '🔥 ¡CRÍTICO! 🔥';
    critDiv.style.position = 'fixed';
    critDiv.style.left = x - 50 + 'px';
    critDiv.style.top = y - 50 + 'px';
    critDiv.style.fontSize = '28px';
    critDiv.style.fontWeight = 'bold';
    critDiv.style.color = '#ff0000';
    critDiv.style.textShadow = '0 0 10px orange';
    critDiv.style.pointerEvents = 'none';
    critDiv.style.zIndex = '2000';
    critDiv.style.whiteSpace = 'nowrap';
    critDiv.style.animation = 'criticalHit 0.8s ease-out forwards';
    document.body.appendChild(critDiv);
    setTimeout(() => critDiv.remove(), 800);
}

// Animación de ataque EXAGERADA
function animarAtaqueAleatorio() {
    const pokemonsUser = document.querySelectorAll('#pokemonUsuario .pokemon-sprite');
    const pokemonsRival = document.querySelectorAll('#pokemonRival .pokemon-sprite');
    
    const atacante = Math.random() > 0.5 ? 'user' : 'rival';
    const pokemons = atacante === 'user' ? pokemonsUser : pokemonsRival;
    const objetivo = atacante === 'user' ? pokemonsRival : pokemonsUser;
    
    if (pokemons.length > 0 && objetivo.length > 0) {
        const pokeAtacante = pokemons[Math.floor(Math.random() * pokemons.length)];
        const pokeObjetivo = objetivo[Math.floor(Math.random() * objetivo.length)];
        
        pokeAtacante.classList.add('attacking');
        pokeObjetivo.classList.add('damage');
        
        const damage = Math.floor(Math.random() * 150) + 50;
        const esCritico = Math.random() < 0.3;
        
        const rect = pokeObjetivo.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;
        
        crearExplosion(centerX, centerY);
        crearChispas(centerX, centerY);
        shakeScreen();
        
        for (let i = 0; i < (esCritico ? 5 : 3); i++) {
            setTimeout(() => {
                const scoreDiv = document.createElement('div');
                scoreDiv.className = 'score-popup';
                const damageValue = Math.floor(damage * (0.7 + Math.random() * 0.6));
                scoreDiv.textContent = esCritico ? `💥 ${damageValue} CRÍTICO! 💥` : `-${damageValue}`;
                scoreDiv.style.left = centerX + (Math.random() - 0.5) * 60 + 'px';
                scoreDiv.style.top = centerY + (Math.random() - 0.5) * 40 + 'px';
                scoreDiv.style.fontSize = esCritico ? '28px' : '22px';
                scoreDiv.style.color = esCritico ? '#ff6600' : '#ff4444';
                scoreDiv.style.fontWeight = 'bold';
                scoreDiv.style.textShadow = esCritico ? '0 0 10px red' : 'none';
                document.body.appendChild(scoreDiv);
                setTimeout(() => scoreDiv.remove(), 800);
            }, i * 50);
        }
        
        if (esCritico) {
            crearEfectoCritico(centerX, centerY);
            agregarMensaje("💢 ¡GOLPE CRÍTICO! 💢", true);
        }
        
        setTimeout(() => {
            pokeAtacante.classList.remove('attacking');
            pokeObjetivo.classList.remove('damage');
        }, 400);
    }
}

function actualizarPoder() {
    poderActual += Math.random() * 12;
    if (poderActual >= 100) {
        poderActual = 100;
        clearInterval(intervaloPoder);
        clearInterval(intervaloMensajes);
        agregarMensaje("💥 ¡LA BATALLA ESTÁ LISTA! 💥", true);
        
        setTimeout(() => {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = window.location.pathname + '?resultado=1';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'rival';
            input.value = document.getElementById('rivalId')?.value || '';
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }, 1500);
    }
    const powerBar = document.getElementById('powerBar');
    const poderPorcentaje = document.getElementById('poderPorcentaje');
    powerBar.style.width = poderActual + '%';
    poderPorcentaje.textContent = Math.floor(poderActual) + '%';
    
    if (poderActual > 80) {
        powerBar.style.background = "linear-gradient(90deg, #ff0000, #ff6600, #ffff00)";
    } else if (poderActual > 50) {
        powerBar.style.background = "linear-gradient(90deg, #ff6600, #ffaa00, #ffff00)";
    }
    
    if (Math.random() < 0.4 && poderActual < 100) {
        animarAtaqueAleatorio();
    }
}

function cambiarMensajePeriodico() {
    const mensaje = mensajesEpicos[mensajeIndex % mensajesEpicos.length];
    agregarMensaje(mensaje);
    mensajeIndex++;
}

function iniciarBatalla() {
    intervaloPoder = setInterval(actualizarPoder, 180);
    intervaloMensajes = setInterval(cambiarMensajePeriodico, 1500);
    
    setTimeout(() => agregarMensaje(`🎯 ¡${document.getElementById('nombreUsuario').innerText} VS ${document.getElementById('nombreRival').innerText}! 🎯`, true), 500);
    setTimeout(() => agregarMensaje("📊 Analizando estadísticas de los Pokémon..."), 1500);
    setTimeout(() => agregarMensaje("💪 ¡Los equipos están listos para el combate!"), 2500);
    
    setTimeout(() => animarAtaqueAleatorio(), 800);
    setTimeout(() => animarAtaqueAleatorio(), 1800);
}

window.onload = iniciarBatalla;