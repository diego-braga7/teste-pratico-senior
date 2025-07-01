# Usando a imagem oficial do MySQL versão 8.0 (estável)
FROM mysql:8.0

# COPY my.cnf /etc/mysql/conf.d/

COPY init.sql /docker-entrypoint-initdb.d/

COPY collumn_sent_leads.sql /docker-entrypoint-initdb.d/

EXPOSE 3306
