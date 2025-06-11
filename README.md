# Sistema de Roles y Permisos con PHP

Sistema de administración de facturas con roles y permisos implementado con PHP puro, MySQL y Bootstrap.

## Características

- Autenticación segura de usuarios
- Sistema de roles: Administrador, Empleado y Cliente
- Gestión de permisos basada en roles
- Interfaz adaptada a cada tipo de usuario
- Implementación de buenas prácticas de seguridad (OWASP, NIST, ISO/IEC 25010)

## Estructura de la Base de Datos

- Usuarios (users)
- Roles (roles)
- Permisos (permissions): create, read, update, delete
- Relaciones muchos a muchos
- Facturas con diferentes niveles de acceso

## Roles y Permisos

- **Administrador**: CRUD completo sobre facturas
- **Empleado**: Lectura y actualización de facturas (sin eliminación)
- **Cliente**: Solo lectura de sus propias facturas

## Instalación

1. Clonar el repositorio
2. Configurar XAMPP
3. Importar el script SQL
4. Acceder a través del navegador