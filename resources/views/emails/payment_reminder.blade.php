<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Pago - DYD Soluciones</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f1f5f9; padding: 30px 15px;">
        <tr>
            <td align="center">
                <!-- CONTENEDOR PRINCIPAL -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); border: 1px solid #e2e8f0;">
                    
                    <!-- ENCABEZADO CORPORATIVO -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0c2340 0%, #1e3a8a 100%); padding: 35px 30px; text-align: center;">
                            <div style="font-size: 26px; font-weight: 800; color: #ffffff; letter-spacing: 0.5px; text-transform: uppercase;">
                                DYD <span style="color: #38bdf8;">SOLUCIONES</span>
                            </div>
                            <div style="font-size: 13px; color: #93c5fd; margin-top: 4px; letter-spacing: 1.5px; text-transform: uppercase; font-weight: 600;">
                                Gestión Integral de Cobranza
                            </div>
                        </td>
                    </tr>

                    <!-- BADGE DE NOTIFICACIÓN -->
                    <tr>
                        <td align="center" style="padding-top: 25px;">
                            <div style="display: inline-block; background-color: #fef3c7; border: 1px solid #fde68a; border-radius: 20px; padding: 6px 18px; font-size: 12px; font-weight: 700; color: #92400e; text-transform: uppercase; letter-spacing: 0.5px;">
                                &#128276; Recordatorio de Pago Pendiente
                            </div>
                        </td>
                    </tr>

                    <!-- CONTENIDO PRINCIPAL -->
                    <tr>
                        <td style="padding: 20px 35px 30px 35px;">
                            <!-- SALUDO -->
                            <div style="font-size: 16px; color: #0f172a; margin-bottom: 15px;">
                                Estimado/a <strong>{{ $clientName }}</strong>,
                            </div>

                            <!-- MENSAJE PERSONALIZADO -->
                            @if(!empty($customMessage))
                            <div style="background-color: #f8fafc; border-left: 4px solid #2563eb; border-radius: 6px; padding: 14px 18px; margin-bottom: 25px; font-size: 14px; color: #334155; line-height: 1.6;">
                                {!! nl2br(e($customMessage)) !!}
                            </div>
                            @else
                            <p style="font-size: 14px; line-height: 1.6; color: #475569; margin: 0 0 20px 0;">
                                Le recordamos cordialmente que tiene un pago pendiente registrado en nuestra plataforma. Le compartimos los detalles a continuación:
                            </p>
                            @endif

                            <!-- TARJETA DESTACADA DE PAGO -->
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8fafc; border: 2px solid #e2e8f0; border-radius: 10px; margin-bottom: 25px; overflow: hidden;">
                                <tr>
                                    <td style="padding: 20px; text-align: center; border-bottom: 1px solid #e2e8f0; background-color: #ffffff;">
                                        <div style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">
                                            Monto Total a Liquidar
                                        </div>
                                        <div style="font-size: 32px; font-weight: 800; color: #0c2340; letter-spacing: -0.5px;">
                                            ${{ number_format((float) str_replace(',', '', (string)$amount), 2) }} <span style="font-size: 16px; font-weight: 600; color: #64748b;">MXN</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 20px;">
                                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13px;">
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-weight: 600; width: 45%;">
                                                    &#128197; Fecha de Vencimiento:
                                                </td>
                                                <td style="padding: 6px 0; color: #0f172a; font-weight: 700; text-align: right;">
                                                    {{ \Carbon\Carbon::parse($dueDate)->format('d/m/Y') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-weight: 600;">
                                                    &#128196; Concepto:
                                                </td>
                                                <td style="padding: 6px 0; color: #0f172a; font-weight: 600; text-align: right;">
                                                    Servicio de Cobranza
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 6px 0; color: #64748b; font-weight: 600;">
                                                    &#128260; Estado:
                                                </td>
                                                <td style="padding: 6px 0; color: #d97706; font-weight: 700; text-align: right;">
                                                    Pendiente
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- SECCIÓN DE CONTACTO Y ACLARACIONES -->
                            <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 18px; margin-bottom: 20px;">
                                <div style="font-size: 13px; font-weight: 700; color: #1e40af; margin-bottom: 8px;">
                                    &#128172; ¿Ya realizaste tu pago o necesitas coordinar una fecha?
                                </div>
                                <p style="font-size: 13px; color: #1e3a8a; line-height: 1.5; margin: 0 0 10px 0;">
                                    Si ya efectuaste tu pago, por favor haz caso omiso de este recordatorio o envíanos tu comprobante respondiendo a este correo para actualizar tu cuenta.
                                </p>
                                <div style="font-size: 13px; color: #0c2340;">
                                    &#9993; Correo de atención: <a href="mailto:cobranza@dydsolucionesintegrales.com" style="color: #2563eb; font-weight: 700; text-decoration: none;">cobranza@dydsolucionesintegrales.com</a>
                                </div>
                            </div>

                        </td>
                    </tr>

                    <!-- PIE DE PÁGINA -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 22px 30px; text-align: center;">
                            <div style="font-size: 12px; color: #64748b; margin-bottom: 6px;">
                                &copy; {{ date('Y') }} <strong>DYD Soluciones Integrales</strong>. Todos los derechos reservados.
                            </div>
                            <div style="font-size: 11px; color: #94a3b8; line-height: 1.4;">
                                Este mensaje ha sido generado automáticamente por el sistema institucional de cobranza de DYD Soluciones.
                            </div>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
