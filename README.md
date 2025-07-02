# Quiz & Leads API

Aplicação PHP containerizada para gerenciar quizzes (com perguntas e respostas múltipla-escolha/texto/booleano) e coletar leads com suas respostas.

---

## 📦 Arquitetura

- **Docker Compose** gerencia:
  - **MySQL 8.0** (serviço `bd`)  
    - Inicializa esquema via `bd/init.sql` e aplica migrações condicionais em `bd/collumn_sent_leads.sql`  
    - Banco de dados: `quiz_app`
  - **PHP 8.3 + Apache** (serviço `php-back-end`)  
    - Document root: `back-end/public`  
    - `php.ini` customizado e `docker-entrypoint.sh` para bootstrapping  
    - Código fonte montado via volume `back-end/`

- **Código** em `back-end/`:  
  - Repositórios, Entidades, Controllers e Services para:
    - **Usuários** (roles admin/usuário)
    - **Quizzes** → Perguntas → Alternativas
    - **Leads** → Respostas de Leads

---

## 🚀 Como executar

### Pré-requisitos

- Docker CE (v20+)
- Docker Compose

### Clonar e subir containers

```bash
git clone https://github.com/diego-braga7/teste-pratico-senior.git
cd teste-pratico-senior
docker-compose up -d
