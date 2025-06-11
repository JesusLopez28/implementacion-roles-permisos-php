CREATE DATABASE IF NOT EXISTS roles_permisos_facturas;
USE roles_permisos_facturas;

-- Tabla de usuarios
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de roles
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50)
);

-- Tabla de permisos
CREATE TABLE permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50)
);

-- Relación muchos a muchos: usuarios con roles
CREATE TABLE role_user (
    user_id INT,
    role_id INT,
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

-- Relación muchos a muchos: roles con permisos
CREATE TABLE permission_role (
    role_id INT,
    permission_id INT,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);

-- Tabla de facturas
CREATE TABLE facturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    descripcion TEXT,
    monto DECIMAL(10,2),
    fecha DATE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insertar roles
INSERT INTO roles (name) VALUES ('Administrador'), ('Empleado'), ('Cliente');

-- Insertar permisos
INSERT INTO permissions (name) VALUES ('create'), ('read'), ('update'), ('delete');

-- Insertar usuarios de prueba
INSERT INTO users (name, email, password) VALUES
('Admin User', 'admin@example.com', 'admin123'),
('Empleado User', 'empleado@example.com', 'empleado123'),
('Cliente User', 'cliente@example.com', 'cliente123');

-- Asignar roles a usuarios
INSERT INTO role_user (user_id, role_id) VALUES
(1, 1), -- Admin
(2, 2), -- Empleado
(3, 3); -- Cliente

-- Asignar permisos a roles
-- Admin: CRUD
INSERT INTO permission_role (role_id, permission_id) VALUES
(1, 1), (1, 2), (1, 3), (1, 4);

-- Empleado: RUD
INSERT INTO permission_role (role_id, permission_id) VALUES
(2, 2), (2, 3), (2, 4);

-- Cliente: R
INSERT INTO permission_role (role_id, permission_id) VALUES
(3, 2);

-- Facturas de prueba
INSERT INTO facturas (user_id, descripcion, monto, fecha) VALUES
(3, 'Factura Cliente 1', 500.00, '2024-06-01'),
(2, 'Factura Empleado 1', 800.00, '2024-06-05'),
(1, 'Factura Admin 1', 1200.00, '2024-06-10');

-- Eliminar el permiso de borrado (delete) para el rol Empleado
DELETE FROM permission_role 
WHERE role_id = (SELECT id FROM roles WHERE name = 'Empleado')
AND permission_id = (SELECT id FROM permissions WHERE name = 'delete');
