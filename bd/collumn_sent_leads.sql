USE `quiz_app`;

-- 1) Conta quantas vezes a coluna 'sent' já existe em 'leads'
SET @col_count = (
  SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
   WHERE TABLE_SCHEMA = DATABASE()
     AND TABLE_NAME   = 'leads'
     AND COLUMN_NAME  = 'sent'
);

-- 2) Monta dinamicamente o comando: ou altera a tabela, ou só informa que já existe
SET @sql = IF(
  @col_count = 0,
  'ALTER TABLE `leads` ADD COLUMN `sent` TINYINT(1) NOT NULL DEFAULT 0 AFTER `created_at`;',
  'SELECT "Coluna `sent` já existe em `leads`.";'
);

-- 3) Executa
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
