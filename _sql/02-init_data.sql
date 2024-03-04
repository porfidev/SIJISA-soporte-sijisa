USE soporte_tickets;

INSERT INTO cattipousuario (descripcion, type)
VALUES ('Administrador','admin'), ('Usuario','user'), ('Invitado','guest');

SET @admin_id := (SELECT id FROM cattipousuario WHERE descripcion = 'admin');
INSERT INTO usuarios (nombre, username, password, email, id_tipo_usuario)
VALUES ('Administrador', 'admin', SHA1('abcd1234*'), 'admin@example.com', @admin_id);

INSERT INTO catestatus (nombre)
VALUES ('Abierto'), ('En Curso'), ('Cerrado');