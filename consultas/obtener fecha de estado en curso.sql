SELECT todo.fecha, estado.Descripcion
FROM transiciones todo
INNER JOIN catestatus estado on todo.intIdEstatus = estado.intIdEstatus
WHERE todo.intIdEstatus = 2 AND todo.intIdTicket = 299
LIMIT 1;
