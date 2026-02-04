<?php
  session_start();
  include_once('header.php');

  // Si llega POST, actualiza sesión
  if (!empty($_POST['searchTerm'])) {
      $_SESSION['last_search'] = trim($_POST['searchTerm']);
      $_SESSION['last_pageSize'] = isset($_POST['pageSize']) ? (int)$_POST['pageSize'] : 20;
      $_SESSION['last_sort'] = $_POST['sort'] ?? 'popularity';
  }

  // Variables “finales” (POST o sesión)
  $search_term = $_POST['searchTerm'] ?? ($_SESSION['last_search'] ?? '');
  $search_term = trim($search_term);

  $page_size = isset($_POST['pageSize']) ? (int)$_POST['pageSize'] : ($_SESSION['last_pageSize'] ?? 20);
  $sort_by   = $_POST['sort'] ?? ($_SESSION['last_sort'] ?? 'popularity');
?>

<div class="container mb-5">
   <div class="jumbotron mt-3 text-center">
      <h1> Buscador de Alimentos</h1>
      <p class="lead">Base de Datos de Open Food Facts - Productos Españoles</p>
      <p class="text-muted">Busca información nutricional de más de 3 millones de productos</p>
      
      <!-- Formulario de búsqueda -->
      <form class="mt-4" method="POST">
          <div class="row justify-content-center">
              <div class="col-md-8">
                  <div class="input-group input-group-lg mb-3">
                      <input class="form-control"
                             value="<?php echo htmlspecialchars($search_term); ?>"
                             name="searchTerm" 
                             type="text" 
                             placeholder="Ejemplo: queso, yogur, coca-cola, nutella..." 
                             required>
                      <div class="input-group-append">
                          <button class="btn btn-primary" name="submit" type="submit">
                              <i class="fas fa-search"></i> Buscar
                          </button>
                      </div>
                  </div>
              </div>
          </div>
          
          <div class="row justify-content-center">
              <div class="col-md-3">
                  <select name="pageSize" class="form-control">
                    <option value="20"  <?php if($page_size == 20)  echo 'selected'; ?>>20 resultados</option>
                    <option value="50"  <?php if($page_size == 50)  echo 'selected'; ?>>50 resultados</option>
                    <option value="100" <?php if($page_size == 100) echo 'selected'; ?>>100 resultados</option>
                  </select>
              </div>
              
              <div class="col-md-3">
                  <select name="sort" class="form-control">
                    <option value="popularity"   <?php if($sort_by == 'popularity') echo 'selected'; ?>>Más populares</option>
                    <option value="product_name" <?php if($sort_by == 'product_name') echo 'selected'; ?>>Nombre A-Z</option>
                  </select>
              </div>
          </div>
      </form>
      
      <div class="mt-3">
          <small class="text-muted">
              <i class="fas fa-info-circle"></i> 
              Datos proporcionados por <a href="https://es.openfoodfacts.org" target="_blank">Open Food Facts</a> - 
              Base de datos colaborativa de productos alimentarios
          </small>
      </div>
  </div>

<?php
  if (!empty($search_term)):
    // Validación
    if ($search_term === '') {
        echo "<div class='alert alert-warning'><i class='fas fa-exclamation-triangle'></i> Por favor, ingresa un término de búsqueda.</div>";
    } else {

        // Construir URL de la API de Open Food Facts (versión española)
        $url = "https://es.openfoodfacts.org/cgi/search.pl";
        $params = array(
            'search_terms' => $search_term,
            'search_simple' => 1,
            'action' => 'process',
            'json' => 1,
            'page_size' => $page_size,
            'sort_by' => $sort_by,
            'fields' => 'code,product_name,brands,nutriscore_grade,nova_group,image_small_url,nutriments,categories,quantity'
        );

        $url .= '?' . http_build_query($params);

        // Realizar petición con cURL
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'AlimentosApp/1.0 (xampp-local-test)');
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);

        $result = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($curl);
        curl_close($curl);

        // Procesar respuesta
        if ($result === false):
            echo "<div class='alert alert-danger'>";
            echo "<strong><i class='fas fa-exclamation-circle'></i> Error de conexión:</strong> ";
            echo htmlspecialchars($curl_error);
            echo "</div>";
        elseif ($http_code != 200):
            echo "<div class='alert alert-danger'>";
            echo "<strong><i class='fas fa-exclamation-circle'></i> Error HTTP {$http_code}:</strong> ";
            echo "La API de Open Food Facts no respondió correctamente.";
            echo "</div>";
        else:
            $data = json_decode($result, true);

            if ($data === null):
                $json_error = json_last_error_msg();
                echo "<div class='alert alert-danger'>";
                echo "<strong><i class='fas fa-exclamation-circle'></i> Error JSON:</strong> ";
                echo htmlspecialchars($json_error);
                echo "</div>";
            elseif (isset($data['products']) && count($data['products']) > 0):
?>

           <div class="row">
            <div class="col-md-12">
              <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> Se encontraron <strong><?php echo $data['count']; ?></strong> producto(s). 
                Mostrando <strong><?php echo count($data['products']); ?></strong> resultados.
              </div>
              
              <div class="row">
                <?php foreach($data['products'] as $product): ?>
                  <?php 
                    $code = isset($product['code']) ? htmlspecialchars($product['code']) : '';
                    $name = isset($product['product_name']) ? htmlspecialchars($product['product_name']) : 'Producto sin nombre';
                    $brands = isset($product['brands']) ? htmlspecialchars($product['brands']) : 'Marca desconocida';
                    $image = isset($product['image_small_url']) ? $product['image_small_url'] : '';
                    $nutriscore = isset($product['nutriscore_grade']) ? strtoupper($product['nutriscore_grade']) : '';
                    $nova = isset($product['nova_group']) ? $product['nova_group'] : '';
                    $quantity = isset($product['quantity']) ? htmlspecialchars($product['quantity']) : '';
                    
                    // Colores para Nutriscore
                    $nutriscore_colors = array(
                        'A' => 'success',
                        'B' => 'info',
                        'C' => 'warning',
                        'D' => 'orange',
                        'E' => 'danger'
                    );
                    $nutriscore_color = isset($nutriscore_colors[$nutriscore]) ? $nutriscore_colors[$nutriscore] : 'secondary';
                  ?>
                  
                  <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                      <?php if(!empty($image)): ?>
                        <img src="<?php echo htmlspecialchars($image); ?>" 
                             class="card-img-top" 
                             alt="<?php echo $name; ?>"
                             style="height: 200px; object-fit: contain; background: #f8f9fa; padding: 10px;">
                      <?php else: ?>
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                          <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                      <?php endif; ?>
                      
                      <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo $name; ?></h5>
                        <p class="card-text">
                          <small class="text-muted">
                            <i class="fas fa-copyright"></i> <?php echo $brands; ?>
                          </small>
                          <?php if(!empty($quantity)): ?>
                            <br><small class="text-muted">
                              <i class="fas fa-weight"></i> <?php echo $quantity; ?>
                            </small>
                          <?php endif; ?>
                        </p>
                        
                        <div class="mb-2">
                          <?php if(!empty($nutriscore)): ?>
                            <span class="badge badge-<?php echo $nutriscore_color; ?> mr-1">
                              Nutri-Score: <?php echo $nutriscore; ?>
                            </span>
                          <?php endif; ?>
                          
                          <?php if(!empty($nova)): ?>
                            <span class="badge badge-secondary">
                              NOVA: <?php echo $nova; ?>
                            </span>
                          <?php endif; ?>
                        </div>
                        
                        <div class="mt-auto">
                          <a href='item.php?code=<?php echo $code; ?>&name=<?php echo urlencode($name); ?>' 
                             class='btn btn-primary btn-block'>
                            <i class='fas fa-info-circle'></i> Ver Detalles
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
           </div>
<?php
           else:
               echo "<div class='alert alert-info'>";
               echo "<i class='fas fa-info-circle'></i> No se encontraron productos para '<strong>" . htmlspecialchars($search_term) . "</strong>'.";
               echo "<br><small>Intenta con otro término de búsqueda o el nombre de una marca conocida.</small>";
               echo "</div>";
               
               // Sugerencias
               echo "<div class='card'>";
               echo "<div class='card-body'>";
               echo "<h5><i class='fas fa-lightbulb'></i> Sugerencias:</h5>";
               echo "<ul>";
               echo "<li>Prueba con nombres de marcas conocidas: 'Coca-Cola', 'Danone', 'Nestlé'</li>";
               echo "<li>Usa términos simples: 'queso', 'yogur', 'chocolate'</li>";
               echo "<li>Busca por código de barras si lo conoces</li>";
               echo "</ul>";
               echo "</div>";
               echo "</div>";
           endif;
       endif;
   }
endif;
?>

</div>

<?php include_once('footer.php'); ?>
