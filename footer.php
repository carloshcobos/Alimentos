
    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <p class="mb-2">
                <i class="fas fa-database"></i> 
                <strong>Buscador de Alimentos</strong> - Base de Datos Española de Composición de Alimentos
            </p>
            <p class="mb-2">
                <small>
                    Datos proporcionados por Open Food Facts
                </small>
            </p>
            <p class="mb-0">
                <small>
                    <a href="https://es.openfoodfacts.org/" target="_blank" class="text-white">
                        <i class="fas fa-link"></i> Open Food Facts España
                    </a>
                </small>
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        // Animación suave al hacer hover en las filas de la tabla
        $(document).ready(function() {
            $('.table-hover tbody tr').hover(
                function() {
                    $(this).css('transform', 'scale(1.01)');
                    $(this).css('transition', 'all 0.2s ease');
                },
                function() {
                    $(this).css('transform', 'scale(1)');
                }
            );
        });
    </script>
</body>
</html>
