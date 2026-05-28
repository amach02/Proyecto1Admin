function registrarProducto() {
    const marca       = document.getElementById('marca').value.trim();
    const descripcion = document.getElementById('descripcion').value.trim();
    const precio      = document.getElementById('precio').value.trim();
    let   codigo      = document.getElementById('codigo').value.trim();

    if (!marca || !descripcion || !precio) {
        mostrarAlerta('alerta', 'Los campos marca, descripción y precio son obligatorios', 'danger');
        return;
    }

    if (!codigo) {
        const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        codigo = 'P-';
        for (let i = 0; i < 4; i++) {
            codigo += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
        }
    }

    fetch('?controlador=Producto&accion=registrar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `codigo=${codigo}&marca=${marca}&descripcion=${descripcion}&precio=${precio}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.estado === 'ok') {
            mostrarAlerta('alerta', data.mensaje, 'success');
            limpiarCampos(['codigo', 'marca', 'descripcion', 'precio']);
        }
    })
    .catch(() => mostrarAlerta('alerta', 'Error al conectar con el servidor', 'danger'));
}

function mostrarAlerta(id, mensaje, tipo) {
    const alerta = document.getElementById(id);
    alerta.className = `alert alert-${tipo}`;
    alerta.textContent = mensaje;
    alerta.classList.remove('d-none');
}

function limpiarCampos(ids) {
    ids.forEach(id => document.getElementById(id).value = '');
}