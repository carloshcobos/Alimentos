<?php include_once('header.php'); ?>

<div class="container mt-4 mb-5">
    
<?php
if(isset($_GET['code'])) {
    $product_code = htmlspecialchars($_GET['code']);
    $product_name = isset($_GET['name']) ? $_GET['name'] : 'Producto';
    
    // Construir URL para obtener detalles del producto
    $url = "https://es.openfoodfacts.org/api/v2/product/" . $product_code . ".json";
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 15);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, 'AlimentosApp/1.0 (xampp-local-test)');
    
    $result = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($curl);
    curl_close($curl);
    
    if ($result === false) {
        echo "<div class='alert alert-danger'>";
        echo "<strong><i class='fas fa-exclamation-circle'></i> Error de conexión:</strong> ";
        echo htmlspecialchars($curl_error);
        echo "</div>";
    } elseif ($http_code != 200) {
        echo "<div class='alert alert-danger'>";
        echo "<strong><i class='fas fa-exclamation-circle'></i> Error HTTP {$http_code}</strong>";
        echo "</div>";
    } else {
        $data = json_decode($result, true);
        
        if ($data && isset($data['product'])) {
            $product = $data['product'];
            
            // Extraer información
            $name = isset($product['product_name']) ? $product['product_name'] : 'Producto sin nombre';
            $brands = isset($product['brands']) ? $product['brands'] : 'Marca desconocida';
            $quantity = isset($product['quantity']) ? $product['quantity'] : '';
            $categories = isset($product['categories']) ? $product['categories'] : '';
            $image_url = isset($product['image_url']) ? $product['image_url'] : '';
            $nutriscore = isset($product['nutriscore_grade']) ? strtoupper($product['nutriscore_grade']) : '';
            $nova = isset($product['nova_group']) ? $product['nova_group'] : '';
            $nova_group = isset($product['nova_groups_tags'][0]) ? $product['nova_groups_tags'][0] : '';
            $nutriments = isset($product['nutriments']) ? $product['nutriments'] : array();
            $ingredients_text = isset($product['ingredients_text_es']) ? $product['ingredients_text_es'] : 
                               (isset($product['ingredients_text']) ? $product['ingredients_text'] : '');
            $allergens = isset($product['allergens']) ? $product['allergens'] : '';
            $labels = isset($product['labels']) ? $product['labels'] : '';
        
            // Función para obtener descripción NOVA
            function novaDescription($nova) {
                return [
                    1 => 'Alimento sin procesar o mínimamente procesado',
                    2 => 'Ingredientes procesados',
                    3 => 'Alimento procesados',
                    4 => 'Alimento ultraprocesado'
                ][$nova] ?? '';
            }

            ?>
            
            <!-- Encabezado del producto -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">
                        <i class="fas fa-shopping-basket"></i> 
                        <?php echo htmlspecialchars($name); ?>
                    </h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <?php if(!empty($image_url)): ?>
                                <img src="<?php echo htmlspecialchars($image_url); ?>" 
                                     class="img-fluid rounded shadow-sm mb-3" 
                                     alt="<?php echo htmlspecialchars($name); ?>">
                            <?php else: ?>
                                <div class="bg-light rounded p-5 text-center mb-3">
                                    <i class="fas fa-image fa-5x text-muted"></i>
                                    <p class="mt-3 text-muted">Sin imagen</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-8">
                            <p><strong><i class="fas fa-copyright"></i> Marca:</strong> <?php echo htmlspecialchars($brands); ?></p>
                            <?php if(!empty($quantity)): ?>
                                <p><strong><i class="fas fa-weight"></i> Cantidad:</strong> <?php echo htmlspecialchars($quantity); ?></p>
                            <?php endif; ?>
                            <?php if(!empty($categories)): ?>
                                <p><strong><i class="fas fa-tags"></i> Categorías:</strong> <?php echo htmlspecialchars($categories); ?></p>
                            <?php endif; ?>
                            
                            <p><strong><i class="fas fa-barcode"></i> Código de barras:</strong> <?php echo htmlspecialchars($product_code); ?></p>
                            
                            <div class="mt-3">
                                <?php if(!empty($nutriscore)): ?>
                                    <?php
                                    $nutriscore_colors = array('A' => 'success', 'B' => 'info', 'C' => 'warning', 'D' => 'orange', 'E' => 'danger');
                                    $color = isset($nutriscore_colors[$nutriscore]) ? $nutriscore_colors[$nutriscore] : 'secondary';
                                    ?>
                                    <span class="badge badge-<?php echo $color; ?> badge-lg mr-2">
                                        <i class="fas fa-chart-line"></i> Nutri-Score: <?php echo $nutriscore; ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if(!empty($nova)): ?>
                                    <span class="badge badge-secondary badge-lg">
                                        <i class="fas fa-industry"></i> NOVA: <?php echo $nova; ?>
                                    </span>
                                    <span class="mt-1 text-danger font-weight-bold">
                                        <?php echo novaDescription($nova) ?> 
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if(!empty($labels)): ?>
                                <p class="mt-3"><strong><i class="fas fa-certificate"></i> Etiquetas:</strong><br>
                                    <?php
                                    $labels_array = explode(',', $labels);
                                    foreach($labels_array as $label) {
                                        echo '<span class="badge badge-info mr-1">' . htmlspecialchars(trim($label)) . '</span>';
                                    }
                                    ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <a href="index.php" class="btn btn-secondary mt-3">
                        <i class="fas fa-arrow-left"></i> Volver a la búsqueda
                    </a>
                    
                    <a href="https://es.openfoodfacts.org/product/<?php echo $product_code; ?>" 
                       target="_blank" class="btn btn-outline-primary mt-3">
                        <i class="fas fa-external-link-alt"></i> Ver en Open Food Facts
                    </a>
                </div>
            </div>
            
            <!-- Ingredientes -->
            <?php if(!empty($ingredients_text)): ?>
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-list"></i> Ingredientes</h4>
                </div>
                <div class="card-body">
                    <p><?php echo nl2br(htmlspecialchars($ingredients_text)); ?></p>
                    
                    <?php if(!empty($allergens)): ?>
                        <div class="alert alert-warning mt-3">
                            <strong><i class="fas fa-exclamation-triangle"></i> Alérgenos:</strong> 
                            <?php echo htmlspecialchars($allergens); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Información Nutricional -->
            <?php if(!empty($nutriments)): ?>
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0"><i class="fas fa-chart-pie"></i> Información Nutricional (por 100g)</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Nutriente</th>
                                        <th class="text-right">Cantidad</th>
                                        <th>Unidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($nutriments['energy-kcal_100g'])): ?>
                                    <tr>
                                        <td><strong>Energía</strong></td>
                                        <td class="text-right"><strong class="text-primary"><?php echo $nutriments['energy-kcal_100g']; ?></strong></td>
                                        <td>kcal</td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <?php if(isset($nutriments['fat_100g'])): ?>
                                    <tr>
                                        <td>Grasas</td>
                                        <td class="text-right"><?php echo $nutriments['fat_100g']; ?></td>
                                        <td>g</td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <?php if(isset($nutriments['saturated-fat_100g'])): ?>
                                    <tr>
                                        <td>&nbsp;&nbsp;- Saturadas</td>
                                        <td class="text-right"><?php echo $nutriments['saturated-fat_100g']; ?></td>
                                        <td>g</td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <?php if(isset($nutriments['carbohydrates_100g'])): ?>
                                    <tr>
                                        <td>Hidratos de carbono</td>
                                        <td class="text-right"><?php echo $nutriments['carbohydrates_100g']; ?></td>
                                        <td>g</td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <?php if(isset($nutriments['sugars_100g'])): ?>
                                    <tr>
                                        <td>&nbsp;&nbsp;- Azúcares</td>
                                        <td class="text-right"><?php echo $nutriments['sugars_100g']; ?></td>
                                        <td>g</td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <?php if(isset($nutriments['fiber_100g'])): ?>
                                    <tr>
                                        <td>Fibra alimentaria</td>
                                        <td class="text-right"><?php echo $nutriments['fiber_100g']; ?></td>
                                        <td>g</td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <?php if(isset($nutriments['proteins_100g'])): ?>
                                    <tr>
                                        <td><strong>Proteínas</strong></td>
                                        <td class="text-right"><strong><?php echo $nutriments['proteins_100g']; ?></strong></td>
                                        <td>g</td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <?php if(isset($nutriments['salt_100g'])): ?>
                                    <tr>
                                        <td>Sal</td>
                                        <td class="text-right"><?php echo $nutriments['salt_100g']; ?></td>
                                        <td>g</td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <?php if(isset($nutriments['sodium_100g'])): ?>
                                    <tr>
                                        <td>Sodio</td>
                                        <td class="text-right"><?php echo $nutriments['sodium_100g']; ?></td>
                                        <td>g</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Vitaminas y Minerales</th>
                                        <th class="text-right">Cantidad</th>
                                        <th>Unidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(isset($nutriments['calcium_100g'])): ?>
                                    <tr>
                                        <td>Calcio</td>
                                        <td class="text-right"><?php echo $nutriments['calcium_100g']; ?></td>
                                        <td>mg</td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <?php if(isset($nutriments['iron_100g'])): ?>
                                    <tr>
                                        <td>Hierro</td>
                                        <td class="text-right"><?php echo $nutriments['iron_100g']; ?></td>
                                        <td>mg</td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <?php if(isset($nutriments['vitamin-a_100g'])): ?>
                                    <tr>
                                        <td>Vitamina A</td>
                                        <td class="text-right"><?php echo $nutriments['vitamin-a_100g']; ?></td>
                                        <td>µg</td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <?php if(isset($nutriments['vitamin-c_100g'])): ?>
                                    <tr>
                                        <td>Vitamina C</td>
                                        <td class="text-right"><?php echo $nutriments['vitamin-c_100g']; ?></td>
                                        <td>mg</td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <?php if(isset($nutriments['vitamin-d_100g'])): ?>
                                    <tr>
                                        <td>Vitamina D</td>
                                        <td class="text-right"><?php echo $nutriments['vitamin-d_100g']; ?></td>
                                        <td>µg</td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <?php if(count($nutriments) == 0): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            No hay información de vitaminas y minerales disponible
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> 
                    No hay información nutricional disponible para este producto.
                </div>
            <?php endif; ?>
            
            <div class="text-center mb-4">
                <a href="index.php" class="btn btn-lg btn-primary">
                    <i class="fas fa-arrow-left"></i> Volver a la búsqueda
                </a>
                
                <a href="https://es.openfoodfacts.org/product/<?php echo $product_code; ?>" 
                   target="_blank" class="btn btn-lg btn-outline-secondary">
                    <i class="fas fa-external-link-alt"></i> Ver en Open Food Facts
                </a>
            </div>
            
            <!-- Footer del producto -->
            <div class="card bg-light">
                <div class="card-body">
                    <p class="mb-0">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i> 
                            Los datos de este producto provienen de <strong>Open Food Facts</strong>, 
                            una base de datos colaborativa y abierta. 
                            Puedes contribuir añadiendo más información o fotos del producto.
                        </small>
                    </p>
                </div>
            </div>
            
            <?php
        } else {
            echo "<div class='alert alert-danger'>";
            echo "<i class='fas fa-exclamation-circle'></i> No se pudo obtener la información del producto.";
            echo "</div>";
        }
    }
} else {
    echo "<div class='alert alert-warning'>";
    echo "<i class='fas fa-exclamation-triangle'></i> No se especificó ningún producto.";
    echo "</div>";
    echo "<a href='index_openfoodfacts.php' class='btn btn-primary'>Ir a la búsqueda</a>";
}
?>

</div>

<?php include_once('footer.php'); ?>
