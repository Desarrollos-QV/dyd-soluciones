<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de Conexión SMTP - DYD Soluciones</title>
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

                    <!-- BADGE DE ESTADO -->
                    <tr>
                        <td align="center" style="padding-top: 25px;">
                            <div style="display: inline-block; background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 20px; padding: 6px 16px; font-size: 12px; font-weight: 700; color: #047857; text-transform: uppercase; letter-spacing: 0.5px;">
                                &#10004; Conexión SMTP Exitosa
                            </div>
                        </td>
                    </tr>

                    <!-- CUERPO PRINCIPAL -->
                    <tr>
                        <td style="padding: 20px 35px 30px 35px;">
                            <h2 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 12px 0; text-align: center;">
                                ¡El servidor de correo está activo!
                            </h2>
                            <p style="font-size: 14px; line-height: 1.6; color: #475569; text-align: center; margin: 0 0 25px 0;">
                                Este mensaje confirma que la integración SMTP de la plataforma ha sido configurada y verificada de manera satisfactoria.
                            </p>

                            <!-- MENSAJE PERSONALIZADO / DE PRUEBA -->
                            <div style="background-color: #f8fafc; border-left: 4px solid #2563eb; border-radius: 6px; padding: 14px 18px; margin-bottom: 25px;">
                                <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.5px;">
                                    Mensaje enviado:
                                </div>
                                <div style="font-size: 14px; color: #1e293b; line-height: 1.5; font-style: italic;">
                                    "{{ $customMessage }}"
                                </div>
                            </div>

                            <!-- TARJETA DE DETALLES TÉCNICOS -->
                            <div style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px; margin-bottom: 25px;">
                                <div style="font-size: 13px; font-weight: 700; color: #0c2340; margin-bottom: 12px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                                    &#128187; Parámetros de la Conexión
                                </div>
                                
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="font-size: 13px;">
                                    <tr>
                                        <td style="padding: 6px 0; color: #64748b; font-weight: 600; width: 45%;">Servidor SMTP:</td>
                                        <td style="padding: 6px 0; color: #0f172a; font-weight: 700;">{{ config('mail.mailers.smtp.host') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 6px 0; color: #64748b; font-weight: 600;">Puerto y Seguridad:</td>
                                        <td style="padding: 6px 0; color: #0f172a;">{{ config('mail.mailers.smtp.port') }} ({{ strtoupper(config('mail.mailers.smtp.encryption') ?: 'TLS') }})</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 6px 0; color: #64748b; font-weight: 600;">Cuenta Remitente:</td>
                                        <td style="padding: 6px 0; color: #2563eb; font-weight: 600;">{{ config('mail.from.address') }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 6px 0; color: #64748b; font-weight: 600;">Destinatario:</td>
                                        <td style="padding: 6px 0; color: #0f172a;">{{ $destination }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 6px 0; color: #64748b; font-weight: 600;">Fecha y Hora:</td>
                                        <td style="padding: 6px 0; color: #0f172a;">{{ $sentAt }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- NOTA INFORMATIVA -->
                            <div style="background-color: #eff6ff; border: 1px dashed #bfdbfe; border-radius: 6px; padding: 12px 16px; font-size: 12px; color: #1e40af; line-height: 1.5;">
                                &#128712; <strong>Listo para operar:</strong> Tus clientes recibirán recordatorios de pago automáticos y manuales con este formato institucional.
                            </div>
                        </td>
                    </tr>

                    <!-- PIE DE PÁGINA -->
                    <tr>
                        <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 30px; text-align: center;">
                            <div style="font-size: 12px; color: #64748b; margin-bottom: 6px;">
                                &copy; {{ date('Y') }} <strong>DYD Soluciones Integrales</strong>. Todos los derechos reservados.
                            </div>
                            <div style="font-size: 11px; color: #94a3b8;">
                                Este es un correo de prueba generado automáticamente por el panel de administración.
                            </div>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
