# 📱 GUÍA DE USO Y DESPLIEGUE - APP MÓVIL NESISTEMA 2.0 (Android & iOS)

¡La **App Móvil de Consulta de Trámites y Notificaciones en Tiempo Real para Clientes** está completamente implementada y lista para probar de inmediato en tu teléfono!

---

## 🚀 OPCIÓN 1: Acceso Web / PWA en tu Teléfono (Android / iPhone)

Tu sistema ya está preparado para tu dominio de producción con SSL:

### 1. Abrir en el navegador de tu celular
En cualquier teléfono, abre el navegador (Chrome en Android o Safari en iPhone) e ingresa:
```
https://nesistema.com/app
```
*(o también `https://nesistema.com/portal`)*

### 2. Instalar como App en el Teléfono (1 Toque)
- **En Android (Google Chrome)**: Toca el menú de 3 puntos `⋮` arriba a la derecha y selecciona **"Instalar aplicación"** o **"Agregar a la pantalla principal"**. Se instalará el ícono de NESISTEMA como una app nativa en tu cajón de aplicaciones.
- **En iPhone (Safari)**: Toca el botón de Compartir y selecciona **"Agregar al inicio"**.

---

## 🔐 Credenciales de Prueba para Clientes

Los clientes pueden ingresar con su número de **Cédula / Identificación** o **Correo Electrónico**.

| Campo | Valor de Prueba |
|---|---|
| **Cédula / Usuario** | `1310539588` *(o cualquier cédula de cliente en el sistema)* |
| **Contraseña Inicial** | `1310539588` *(por defecto su número de cédula, modificable desde la app)* |

---

## 📲 Funcionalidades de la App Móvil

1. **Dashboard de Inicio**:
   - Resumen financiero en tiempo real (Saldo pendiente por pagar, total facturado y total abonado).
   - Contadores de trámites en proceso y trámites listos para retiro.
   - Acceso rápido por categorías (Poderes, Divorcios, Impuestos).
   - Botón directo de contacto por WhatsApp con la sede asignada.

2. **Módulo Central de Trámites**:
   - Filtros por estado: `Todos`, `🟡 En Proceso`, `🟢 ¡Listos!`, `🔵 En Revisión`.
   - Filtros por categoría de trámite: `Poderes`, `Divorcios`, `Impuestos`, `Trámites Varios`, `Formularios Notariales`.
   - Barra de búsqueda predictiva en tiempo real.
   - Barra de avance porcentual visual (`35%`, `70%`, `100%`).

3. **Línea de Tiempo y Detalle del Trámite**:
   - Visualización de 4 fases: *1. Solicitud Recibida ➔ 2. En Elaboración ➔ 3. Revisión Legal ➔ 4. ¡Listo para Retiro / Firma!*
   - Desglose detallado de apoderados, cónyuges, año de impuestos, oficina y balance financiero.

4. **Centro de Notificaciones en Tiempo Real**:
   - Avisos automáticos instantáneos cuando el administrador cambia un trámite a **"Listo / Completado"**.
   - Alerta con sonido y distintivo `NUEVO`.
   - Al pulsar la notificación, abre directamente el trámite terminado.

5. **Perfil y Seguridad**:
   - Datos personales y sede asignada.
   - Cambio de contraseña de acceso seguro.

---

## 🛠️ OPCIÓN 2: Generar APK Nativo para Google Play Store / Android Studio

La carpeta `mobile-client/` incluye la configuración completa de **Capacitor 6** para generar el proyecto nativo de Android Studio y el archivo `.apk` / `.aab` para subir a Google Play Store.

### Pasos para compilar en Android Studio:
```bash
cd mobile-client
npm install
npx cap add android
npx cap copy android
npx cap open android
```
En Android Studio:
1. Ve a **Build > Generate Signed Bundle / APK**.
2. Selecciona **Android App Bundle (.aab)** para la Play Store o **APK** para instalar directamente.
3. ¡Listo para distribución y publicación!
