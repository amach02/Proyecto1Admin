<footer class="mt-5 py-4" style="background:#1a1a2e;color:rgba(255,255,255,.6);text-align:center;font-size:.9rem;">
    <div class="container">
        <i class="bi bi-bicycle" style="color:#e94560;"></i>
        <strong style="color:#fff;">Laboratorio Entomología</strong> &nbsp;|&nbsp;
        Universidad de Costa Rica &nbsp;|&nbsp; &copy; <?php echo date('Y'); ?>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function mostrarToast(mensaje, tipo = 'success') {
    const iconos  = { success: 'bi-check-circle-fill', danger: 'bi-x-circle-fill', warning: 'bi-exclamation-triangle-fill' };
    const colores = { success: '#2ecc71', danger: '#e74c3c', warning: '#f39c12' };

    const toast = document.createElement('div');
    toast.innerHTML = `
        <div style="background:#1a1a2e;color:#fff;padding:14px 20px;border-radius:10px;
                    box-shadow:0 4px 16px rgba(0,0,0,.3);margin-bottom:10px;
                    border-left:4px solid ${colores[tipo]};display:flex;align-items:center;gap:10px;">
            <i class="bi ${iconos[tipo]}" style="color:${colores[tipo]};font-size:1.2rem;"></i>
            <span>${mensaje}</span>
        </div>`;

    document.getElementById('toast-container').appendChild(toast);
    setTimeout(() => toast.remove(), 3500);
}
</script>

</body>
</html>