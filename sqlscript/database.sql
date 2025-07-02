-- base de datos ordenada
CREATE TABLE residencia (
id INT AUTO_INCREMENT PRIMARY KEY,
ciudad VARCHAR(100),
pais VARCHAR(100),
latitud DECIMAL(10,8),
longitud DECIMAL(11,8)
);