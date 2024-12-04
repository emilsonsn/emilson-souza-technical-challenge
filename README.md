
# README

## Introdução

Este é um projeto desenvolvido para um desafio técnico. Ele consiste em uma API Laravel que permite a busca de municípios brasileiros, integrada a um front-end construído com Vue.js. O projeto utiliza Docker para containerização, garantindo um ambiente de desenvolvimento e produção padronizado.

A aplicação foi configurada na nuvem em um servidor EC2 da amazon e está disponível em:
<a target="_blank" href="http://54.89.180.101">http://54.89.180.101</a>

## Introdução

Este é um projeto desenvolvido para um desafio técnico. Ele consiste em uma API Laravel que permite a busca de municípios brasileiros, integrada a um front-end construído com Vue.js. O projeto utiliza Docker para containerização, garantindo um ambiente de desenvolvimento e produção padronizado.

## Requisitos do desafio supridos
- ✔️ Criar uma rota para pesquisar e listar os municípios de uma UF.
- ✔️ Resposta da requisição deve conter, uma lista de municípios com os seguintes
- campos:
- ✔️ name : Nome do município.
- ✔️ ibge_code: Código IBGE desse município.
- ✔️ Deve ser utilizado como providers as seguintes APIs:
- ✔️ Brasil API - https://brasilapi.com.br/api/ibge/municipios/v1/RS
- ✔️ IBGE - https://servicodados.ibge.gov.br/api/v1/localidades/estados/rs/municipios
- ✔️ O provider usado deve ser definido via variável de ambiente.
- ✔️ Deve conter testes unitários e de integração.
- Bônus:
- ✔️ Uso de Cache.
- ✔️ Tratamento de exceções.
- ✔️ Documentação do projeto (pensando na possibilidade do projeto crescer e
- possuir outros endpoints futuramente).
- ✔️ Github Actions.
- ✔️ Commits atômicos e descritivos.
- ✔️ Paginação dos resultados.
- ✔️ Criação de SPA consumindo o endpoint criado.
- ✔️ Disponibilização do projeto em algum ambiente cloud.
- ✔️ Conteinerização.

## Documentação da API
A documentação da API foi gerada usando o pacote `knuckleswtf/scribe`. Ela contém as informações detalhadas sobre as rotas disponíveis, parâmetros aceitos e exemplos de respostas.

A documentação deve ser gerada rodando o comando: 
```bash
php artisan scribe:generate
```
Documentação acessível em: <a target="_blank" href="http://54.89.180.101/docs">http://54.89.180.101/docs</a>

## Instalação

### Pré-requisitos

- Docker e Docker Compose instalados.
- Node.js e npm (opcional para rodar o front-end localmente sem Docker).

### Passos

1. Clone o repositório:
   ```bash
   git clone <link-do-repositorio>
   cd <nome-da-pasta-do-projeto>
   ```

2. Copie o arquivo `.env.example` para `.env`:
   ```bash
   cp .env.example .env
   ```

3. Configure as variáveis de ambiente no arquivo `.env` conforme necessário.

4. Instale as dependências do projeto:
   - Caso vá rodar localmente:
     ```bash
     composer install
     npm install
     npm run build
     ```

## Como rodar com Docker

1. Monte e inicie os containers:
   ```bash
   docker-compose up -d
   ```

2. Acesse a aplicação em seu navegador:
   ```
   http://localhost
   ```

3. Para verificar os logs:
   ```bash
   docker-compose logs -f
   ```

4. Caso precise recriar a imagem:
   ```bash
   docker-compose down --volumes
   docker-compose up --build
   ```

## Estrutura de Pastas

Abaixo, uma visão geral da estrutura do projeto:

```
.
├── app/                     # Código principal do back-end
├── bootstrap/               # Arquivos de bootstrap da aplicação
├── config/                  # Configurações do Laravel
├── docker/                  # Configurações do Docker
│   ├── nginx/               # Configuração do Nginx
├── public/                  # Arquivos públicos
├── resources/               # Recursos como views, assets e arquivos do Vue.js
│   ├── js/
│   └── css/
├── routes/                  # Definições de rotas da aplicação
├── storage/                 # Arquivos de cache, logs e uploads
├── tests/                   # Testes automatizados
├── vite.config.js           # Configuração do Vite
├── docker-compose.yml       # Configuração do Docker Compose
└── Dockerfile               # Configuração do Docker Compose
           
```

## Como rodar os testes

1. Execute os testes com o seguinte comando:
   ```bash
   php artisan test
   ```

2. Para rodar um teste específico:
   ```bash
   php artisan test --filter NomeDoTeste
   ```

3. Certifique-se de que o ambiente está configurado corretamente:
   - O arquivo `.env` deve estar com as configurações de teste adequadas.