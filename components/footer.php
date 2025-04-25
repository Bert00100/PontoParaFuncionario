</main> <!-- Fecha a tag main aberta no index.php -->

<footer class="footer py-3 bg-dark">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <span class="text-white">Sistema de Ponto &copy; <?php echo date('Y'); ?></span>
            </div>
            <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                <span class="text-white-50 small">Versão 1.0.0</span>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Todos os scripts JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Scripts personalizados podem ser adicionados aqui
    document.addEventListener('DOMContentLoaded', function() {
        // Garante que o footer sempre fique no rodapé
        function adjustFooter() {
            const body = document.body;
            const html = document.documentElement;
            const height = Math.max(
                body.scrollHeight,
                body.offsetHeight,
                html.clientHeight,
                html.scrollHeight,
                html.offsetHeight
            );
            
            if (height <= window.innerHeight) {
                document.querySelector('footer').classList.add('fixed-bottom');
            } else {
                document.querySelector('footer').classList.remove('fixed-bottom');
            }
        }
        
        window.addEventListener('load', adjustFooter);
        window.addEventListener('resize', adjustFooter);
    });
</script>
</body>
</html>