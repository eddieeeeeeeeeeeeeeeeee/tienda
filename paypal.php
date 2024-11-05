<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title> 

    <script src="https://www.paypal.com/sdk/js?client-id=Ab24onYlaGEM-tAdyLR-fHBOE34ucJ5PTJ7r3KFeQefCbim8_dYz7069DS9MguTkrHlPU05rR8D1mMHl&currency=MXN">

    </script>

</head>
<body>
    
    <div id="paypal-button-container"></div>

    <script>
        paypal.Buttons({
           style:{ layout: 'vertical',
    color:  'blue',
    shape:  'pill',
    label:  'pay'
           },
           createOrder: function(data, actions){
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: 100
                        }
                    }]
                });
           },

           onApprove: function(data, actions){
                actions.order.capture().then(function (detalles){
                    window.location.href="completado.html"
                });
           },

           onCancel: function(data) {
                alert("Pago cancelado");
           }
        }).render('#paypal-button-container');
    </script>

</body>
</html>