// ============================================
// SISTEMA DE LOGROS FINAL + CONTADOR
// ============================================

const logrosBase = {
    primeraVictoria: {
        nombre: "Ganaste tu primera batalla",
        desbloqueado: false
    },
    victoriaPerfecta: {
        nombre: "Ganaste sin perder ninguna pelea",
        desbloqueado: false
    },
    cincoVictorias: {
        nombre: "Ganaste 5 batallas en total",
        desbloqueado: false,
        progreso: 0
    }
};

// =======================
// STORAGE
// =======================
function cargarLogros() {
    const data = localStorage.getItem("logros");
    return data ? JSON.parse(data) : logrosBase;
}

function guardarLogros(logros) {
    localStorage.setItem("logros", JSON.stringify(logros));
}

// =======================
// VISUAL
// =======================
function mostrarLogro(texto) {
    const div = document.createElement("div");
    div.className = "logro-popup";

    div.innerHTML = `
        <div style="display:flex; align-items:center; gap:10px;">
            <span style="font-size:28px;">🏆</span>
            <div>
                <div style="font-size:12px;">LOGRO DESBLOQUEADO</div>
                <div>${texto}</div>
            </div>
        </div>
    `;

    document.body.appendChild(div);

    const rect = div.getBoundingClientRect();
    const x = rect.left + rect.width / 2;
    const y = rect.top + rect.height / 2;

    // 🔥 EFECTOS
    if (typeof crearExplosion === "function") crearExplosion(x, y);
    if (typeof crearChispas === "function") crearChispas(x, y);
    if (typeof shakeScreen === "function") shakeScreen();
    if (typeof crearEfectoCritico === "function") crearEfectoCritico(x, y);

    setTimeout(() => div.remove(), 3500);
}

// =======================
// DESBLOQUEO
// =======================
function desbloquearLogro(id, logros) {
    if (!logros[id].desbloqueado) {
        logros[id].desbloqueado = true;
        guardarLogros(logros);
        mostrarLogro(logros[id].nombre);
    }
}

// =======================
// INICIO
// =======================
window.addEventListener("load", () => {
    if (typeof resultadoFinal === "undefined") return;

    const logros = cargarLogros();

    setTimeout(() => {

        // 🥇 Primera victoria
        if (resultadoFinal === "victoria") {
            desbloquearLogro("primeraVictoria", logros);
        }

        // 💎 Victoria perfecta
        if (resultadoFinal === "victoria" && ganadasRival === 0) {
            desbloquearLogro("victoriaPerfecta", logros);
        }

        // 🔥 CONTADOR DE VICTORIAS
        if (resultadoFinal === "victoria") {
            logros.cincoVictorias.progreso++;

            if (logros.cincoVictorias.progreso >= 5) {
                desbloquearLogro("cincoVictorias", logros);
            }

            guardarLogros(logros);
        }

    }, 1200);
});