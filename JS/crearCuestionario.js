let contadorPreguntas = 0;

function agregarPregunta() {
    contadorPreguntas++;
    const contenedor = document.getElementById('contenedorPreguntas');

    const divPregunta = document.createElement('div');
    divPregunta.className = 'pregunta';
    divPregunta.id = `pregunta-${contadorPreguntas}`;

    divPregunta.innerHTML = `
        <label>
            <span class="numeroPregunta">Pregunta ${contadorPreguntas}:</span>
            <input type="text" name="preguntas[${contadorPreguntas}][texto]" required>
        </label>
        <button type="button" onclick="eliminarPregunta(this)">Eliminar Pregunta</button>
        <button type="button" onclick="agregarRespuesta(${contadorPreguntas})">Agregar Respuesta</button>
        <div id="respuestas-${contadorPreguntas}" class="respuestas"></div>
    `;
    contenedor.appendChild(divPregunta);
}

function agregarRespuesta(idPregunta) {
    const contenedorRespuestas = document.getElementById(`respuestas-${idPregunta}`);

    const divRespuesta = document.createElement('div');
    divRespuesta.className = 'respuesta';

    divRespuesta.innerHTML = `
        <label>
            Respuesta:
            <input type="text" name="preguntas[${idPregunta}][respuestas][]" required>
        </label>
        <button type="button" onclick="this.parentElement.remove()">Eliminar</button>
    `;
    contenedorRespuestas.appendChild(divRespuesta);
}

function eliminarPregunta(botonEliminar) {
    const pregunta = botonEliminar.parentElement;
    pregunta.remove();
    actualizarNumerosPreguntas();
}

function actualizarNumerosPreguntas() {
    const preguntas = document.querySelectorAll('.pregunta');
    preguntas.forEach((pregunta, index) => {
        const numeroPregunta = pregunta.querySelector('.numeroPregunta');
        numeroPregunta.textContent = `Pregunta ${index + 1}:`;

        pregunta.id = `pregunta-${index + 1}`;
        const inputTexto = pregunta.querySelector('input[type="text"]');
        inputTexto.name = `preguntas[${index + 1}][texto]`;

        const botonRespuesta = pregunta.querySelector('button[onclick^="agregarRespuesta"]');
        botonRespuesta.setAttribute('onclick', `agregarRespuesta(${index + 1})`);

        const contenedorRespuestas = pregunta.querySelector('.respuestas');
        contenedorRespuestas.id = `respuestas-${index + 1}`;

        const respuestas = contenedorRespuestas.querySelectorAll('input[type="text"]');
        respuestas.forEach((respuesta) => {
            respuesta.name = `preguntas[${index + 1}][respuestas][]`;
        });
    });

    contadorPreguntas = preguntas.length;
}

function convertirACuestionarioTexto() {
    let textoCuestionario = '';
    const preguntas = document.querySelectorAll('.pregunta');
    
    preguntas.forEach((pregunta, index) => {
        const textoPregunta = pregunta.querySelector('input[type="text"]').value;
        textoCuestionario += `${index + 1}: ${textoPregunta}\n`;
        
        const respuestas = pregunta.querySelectorAll('.respuestas input[type="text"]');
        respuestas.forEach((respuesta, idx) => {
            textoCuestionario += `  ${idx + 1}: ${respuesta.value}\n`;
        });
    });

    return textoCuestionario;
}

document.getElementById('formularioCuestionario').addEventListener('submit', function (event) {
    const cuestionarioTexto = convertirACuestionarioTexto();
    document.getElementById('cuestionarioTexto').value = cuestionarioTexto;
});
