<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>Mailing WYS</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Dosis:wght@400;700&family=Raleway:wght@300;700&display=swap"
        rel="stylesheet"
    />
    <style type="text/css">
        /* === Guías de ayuda - Eliminar antes de enviar === */
        /* table td {
        border: 1px solid cyan;
        } */
        /* === Guías de ayuda - Eliminar antes de enviar === */
        /* Reset de elementos propios de los servicios de correo */
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        /* Reset de estilos */
        img {
            border: 0;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            font-family: "Raleway", Thaoma, sans-serif;
        }

        /* iOS Links azules */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* ANDROID Fix centrado */
        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }

        /* Media Queries */
        @media all and (max-width: 639px) {
            .wrapper {
                width: 320px !important;
                padding: 0 !important;
            }

            .container {
                width: 300px !important;
                padding: 0 !important;
            }

            .mobile {
                width: 300px !important;
                display: block !important;
                padding: 0 !important;
            }

            .img {
                width: 100% !important;
                height: auto !important;
            }

            *[class="mobileOff"] {
                width: 0px !important;
                display: none !important;
            }

            *[class*="mobileOn"] {
                display: block !important;
                max-height: none !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f7f7f7;">
      <span
          style="
         display: block;
         width: 640px !important;
         max-width: 640px;
         height: 1px;
         "
          class="mobileOff"
      ></span>
      <center>
          <!-- Contenedor Principal -->
          <table
              width="100%"
              border="0"
              cellpadding="0"
              cellspacing="0"
              bgcolor="#F2F2F2"
          >
              <tr>
                  <td align="center" valign="top">
                      <!-- Tabla para Cabecera -->
                  {{ $header ?? '' }}
                  <!-- / Tabla para Cabecera -->
                      <!-- Tabla para título correo -->
                      <!-- / Tabla para título correo -->
                      <!-- Tabla para título correo -->
                      <!-- / Tabla para título correo -->
                      <!-- Contenido correo -->
                      <table
                          width="640"
                          cellpadding="0"
                          cellspacing="0"
                          border="0"
                          class="wrapper"
                          bgcolor="#ffffff"
                      >
                          <tr>
                              <td
                                  align="center"
                                  valign="top"
                                  style="padding: 0rem 0;"
                              >
                                  <table
                                      width="500"
                                      cellpadding="0"
                                      cellspacing="0"
                                      border="0"
                                      class="container"
                                  >
                                      <!-- Fila nombre destinatario -->
                                      <!-- / Fila nombre destinatario -->
                                      <!-- Fila contenidos simples -->
                                      <tr>
                                          <td
                                              align="left"
                                              valign="center"
                                              style="padding: 1rem 0rem;"
                                          >
                                              {{ Illuminate\Mail\Markdown::parse($slot) }}
                                              {{ $subcopy ?? '' }}
                                          </td>
                                      </tr>
                                      <!-- / Fila contenidos simples -->
                                      <!-- Fila cierre firma -->
                                      <!-- Fila cierre firma -->
                                  </table>
                              </td>
                          </tr>
                      </table>
                      <!-- / Contenido correo -->
                      <!-- Pie de correo -->
                  {{ $footer ?? '' }}
                  <!-- / Pie de Correo -->
                  </td>
              </tr>
          </table>
          <!-- / Contenedor Principal -->
      </center>
</body>
</html>
