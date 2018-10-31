
-- Simplificação do sistema de preços para serviço

ALTER TABLE service_values DROP FOREIGN KEY service_timelimit_fk;
ALTER TABLE service_values DROP INDEX service_timelimit_fk;

ALTER TABLE service_values DROP COLUMN repetitions;
ALTER TABLE service_values DROP COLUMN duration;
ALTER TABLE service_values DROP COLUMN timeLimit;

DROP TABLE service_timelimits;
