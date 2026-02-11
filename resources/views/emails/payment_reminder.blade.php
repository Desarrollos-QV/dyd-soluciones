<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Pago</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }
        .message-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .payment-details {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        .payment-details h3 {
            margin-top: 0;
            color: #856404;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            color: #666;
        }
        .detail-value {
            color: #333;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .contact-info {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 Recordatorio de Pago</h1>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">DYD Soluciones</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Estimado/a <strong>{{ $clientName }}</strong>,
            </div>
            
            <div class="message-box">
                <p>{{ $customMessage }}</p>
            </div>
            
            <div class="payment-details">
                <h3>📋 Detalles del Pago</h3>
                <div class="detail-row">
                    <span class="detail-label">Monto a pagar:</span>
                    <span class="detail-value"><strong>${{ number_format($amount, 2) }}</strong></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Fecha de vencimiento:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($dueDate)->format('d/m/Y') }}</span>
                </div>
            </div>
            
            <p>Para realizar su pago o coordinar una fecha, por favor contáctenos a la brevedad posible.</p>
            
            <div class="contact-info">
                <p><strong>📞 Información de Contacto:</strong></p>
                <p>
                    Teléfono: <a href="tel:+52XXXXXXXXXX">+52 XXX XXX XXXX</a><br>
                    Email: <a href="mailto:contacto@dydsoluciones.com">contacto@dydsoluciones.com</a>
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} DYD Soluciones. Todos los derechos reservados.</p>
            <p style="margin-top: 10px; font-size: 11px; color: #999;">
                Este es un correo automático, por favor no responda a este mensaje.
            </p>
        </div>
    </div>
</body>
</html>
