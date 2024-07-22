<script>
    paypal.Buttons({
        style: {
            color: 'blue',
            shape: 'pill',
            label: 'pay'
        },
        createOrder: function(data, actions) {
            // Calcular el subtotal de la compra
            var subtotal = 0;
            <?php foreach ($productos_carrito as $producto): ?>
                subtotal += <?php echo $producto['precio'] * $producto['cantidad']; ?>;
            <?php endforeach; ?>
            
            // Crear la orden con el subtotal calculado
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: subtotal.toFixed(2) // Formatear el subtotal a dos decimales (opcional)
                    }
                }]
            });
        },

        onApprove: function(data, actions) {
            return actions.order.capture().then(function(detalles) {
                window.location.href="../api/gracias.php";
            });

        },
        onCancel: function(data){
            alert("Pago cancelado")
            console.log(data)
        }
    }).render('#paypal-button-container');
</script>

// Añadido en carrito.php