-- 1. Crear la base de datos
CREATE DATABASE IF NOT EXISTS hotel_db;
USE hotel_db;

-- 2. Crear tabla Usuario (Independiente)
CREATE TABLE Usuario (
    idUsuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL, -- Se guarda encriptada (Hash)
    rol VARCHAR(50) NOT NULL
);

-- 3. Crear tabla Huesped (Independiente)
CREATE TABLE Huesped (
    idHuesped INT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(15) NOT NULL UNIQUE,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    correo VARCHAR(100)
);

-- 4. Crear tabla Habitacion (Independiente)
CREATE TABLE Habitacion (
    idHabitacion INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(10) NOT NULL UNIQUE,
    tipo VARCHAR(50) NOT NULL,
    capacidad INT NOT NULL,
    precioNoche DECIMAL(10,2) NOT NULL,
    estado VARCHAR(20) NOT NULL DEFAULT 'Disponible' -- Disponible, Ocupada, Mantenimiento
);

-- 5. Crear tabla Reserva (Depende de Usuario, Huesped y Habitacion)
CREATE TABLE Reserva (
    idReserva INT AUTO_INCREMENT PRIMARY KEY,
    fechaReserva DATE NOT NULL,
    fechaIngreso DATE NOT NULL,
    fechaSalida DATE NOT NULL,
    estado VARCHAR(20) NOT NULL DEFAULT 'Pendiente', -- Pendiente, Confirmada, Cancelada
    idUsuario INT NOT NULL,
    idHuesped INT NOT NULL,
    idHabitacion INT NOT NULL,
    FOREIGN KEY (idUsuario) REFERENCES Usuario(idUsuario),
    FOREIGN KEY (idHuesped) REFERENCES Huesped(idHuesped),
    FOREIGN KEY (idHabitacion) REFERENCES Habitacion(idHabitacion)
);

-- 6. Crear tabla CheckIn (Depende de Reserva)
CREATE TABLE CheckIn (
    idCheckIn INT AUTO_INCREMENT PRIMARY KEY,
    fechaIngreso DATE NOT NULL,
    horaIngreso TIME NOT NULL,
    idReserva INT NOT NULL UNIQUE, -- Relación 1 a 1
    FOREIGN KEY (idReserva) REFERENCES Reserva(idReserva)
);

-- 7. Crear tabla CheckOut (Depende de Reserva)
CREATE TABLE CheckOut (
    idCheckOut INT AUTO_INCREMENT PRIMARY KEY,
    fechaSalida DATE NOT NULL,
    horaSalida TIME NOT NULL,
    idReserva INT NOT NULL UNIQUE, -- Relación 1 a 1
    FOREIGN KEY (idReserva) REFERENCES Reserva(idReserva)
);

-- 8. Crear tabla Pago (Depende de CheckOut)
CREATE TABLE Pago (
    idPago INT AUTO_INCREMENT PRIMARY KEY,
    monto DECIMAL(10,2) NOT NULL,
    metodoPago VARCHAR(50) NOT NULL,
    fechaPago DATETIME NOT NULL,
    idCheckOut INT NOT NULL UNIQUE, -- Relación 1 a 1
    FOREIGN KEY (idCheckOut) REFERENCES CheckOut(idCheckOut)
);

-- 9. Crear tabla Comprobante (Depende de Pago)
CREATE TABLE Comprobante (
    idComprobante INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(20) NOT NULL UNIQUE,
    fechaEmision DATETIME NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    idPago INT NOT NULL UNIQUE, -- Relación 1 a 1
    FOREIGN KEY (idPago) REFERENCES Pago(idPago)
);

-- Insertar un usuario administrador por defecto para poder iniciar sesión
INSERT INTO Usuario (nombre, usuario, contrasena, rol) 
VALUES ('Marco Antonio', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador');
-- Nota: La contraseña para este usuario es 'password' (está encriptada usando bcrypt)