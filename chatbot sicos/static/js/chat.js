window.onload = function () {
    cargarHistorial();
};

/* ── helpers de globos ── */
function horaActual() {
    return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

function burbuja(texto, tipo) {
    // tipo: "usuario" | "ia"
    const esUser = tipo === "usuario";
    return `
        <div class="mensaje ${esUser ? 'user' : 'bot'}">
            <div class="avatar">${esUser ? '👤' : '🤖'}</div>
            <div class="burbuja">
                ${texto}
                <span class="tiempo">${horaActual()}</span>
            </div>
        </div>
    `;
}

function burbujaTyping() {
    return `
        <div class="mensaje bot" id="typing-indicator">
            <div class="avatar">🤖</div>
            <div class="burbuja">
                <div class="typing">
                    <span></span><span></span><span></span>
                </div>
            </div>
        </div>
    `;
}
/* ────────────────────── */

async function enviar() {

    let input = document.getElementById("mensaje");
    let texto = input.value.trim();

    if (texto === "") return;

    let chat = document.getElementById("chat");

    // Globo del usuario
    chat.innerHTML += burbuja(texto, "usuario");
    scrollChat();
    input.value = "";

    // Indicador "escribiendo..."
    chat.innerHTML += burbujaTyping();
    scrollChat();

    try {
        let response = await fetch("/preguntar", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ mensaje: texto })
        });

        if (!response.ok) throw new Error("Error HTTP: " + response.status);

        let datos = await response.json();

        // Quitar typing y mostrar respuesta
        document.getElementById("typing-indicator")?.remove();
        chat.innerHTML += burbuja(datos.respuesta, "ia");

        Prism.highlightAll();
        agregarBotonesCopiar();
        scrollChat();

    } catch (error) {
        console.error(error);
        document.getElementById("typing-indicator")?.remove();
        chat.innerHTML += burbuja("Error al obtener respuesta.", "ia");
        scrollChat();
    }
}

async function cargarHistorial() {
    try {
        let response = await fetch("/historial");
        let mensajes = await response.json();
        let chat = document.getElementById("chat");

        chat.innerHTML = "";

        mensajes.forEach(m => {
            chat.innerHTML += burbuja(m.mensaje, m.remitente);
        });

        Prism.highlightAll();
        agregarBotonesCopiar();
        scrollChat();

    } catch (error) {
        console.error("Error cargando historial:", error);
    }
}

function scrollChat() {
    let chat = document.getElementById("chat");
    chat.scrollTop = chat.scrollHeight;
}

document.addEventListener("DOMContentLoaded", function () {
    let input = document.getElementById("mensaje");
    input.addEventListener("keypress", function (e) {
        if (e.key === "Enter") enviar();
    });
});

function agregarBotonesCopiar() {
    document.querySelectorAll("pre").forEach(pre => {

        if (pre.parentElement.classList.contains("code-container")) return;

        let contenedor = document.createElement("div");
        contenedor.className = "code-container";
        pre.parentNode.insertBefore(contenedor, pre);
        contenedor.appendChild(pre);

        let boton = document.createElement("button");
        boton.className = "btn-copiar";
        boton.innerText = "Copiar";
        boton.onclick = () => {
            navigator.clipboard.writeText(pre.innerText);
            boton.innerText = "Copiado ✓";
            setTimeout(() => { boton.innerText = "Copiar"; }, 2000);
        };

        contenedor.appendChild(boton);
    });
}