USE soporte_tickets;

INSERT INTO cattipousuario (descripcion)
VALUES ('admin'), ('user'), ('guest');

SET @admin_id := (SELECT id FROM cattipousuario WHERE descripcion = 'admin');
INSERT INTO usuarios (nombre, username, password, email, id_tipo_usuario)
VALUES ('Administrador', 'admin', SHA1('admin'), 'admin@example.com', @admin_id);