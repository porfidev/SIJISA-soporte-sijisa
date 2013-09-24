INSERT INTO catusuarios (id_usuario, nombre)
SELECT usuarios.intIdUsuario, usuarios.nombre
FROM usuarios
WHERE usuarios.intIdUsuario < 100;