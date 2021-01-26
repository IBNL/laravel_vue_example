<b>Linguagem/Versão:</b> PHP 7.4

<b>Framework back-end/Versão:</b> Laravel 8.12

<b>Framework front-end/Versão:</b> Vue 2.5.17

<hr>

## Endpoints Disponíveis

GET /: Retornar um Status: 200 e uma Mensagem "Laravel with Vue Example"

POST /products: O endpoint irá processar o arquivo products.json que será enviado pelo Projeto Web

PUT /products/:productId: Será responsável por receber atualizações realizadas no Projeto Web

DELETE /products/:productId: Remover o produto da base

GET /products/:productId: Obter a informação somente de um produto da base de dados

GET /products: Listar todos os produtos da base de dados

POST /login: Obter token do usuário

obs: Endpoints protegidos pelo sanctum, necessário gerar um token e colocar o mesmo no authorization do header.

<hr>

## Mockup dados

O arquivo products.json possui um exemplo de como os dados devem ser enviados.

Tipo de cada campo:

- title, type, description, filename, path_file_upload = string


- height, width, price, rating = float

<hr>

## Criar banco de dados MYSQL

### Na CLI do MYSQL:

- Criar usuário

    ~# CREATE USER 'userTest'@'%' IDENTIFIED BY '1wMjSFwMjFszcMlHjJc';

- Criar banco de dados

    ~# CREATE DATABASE `laravel_vue_example` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

- Conceder permissão para o usuário na base criada.

    ~# GRANT ALL PRIVILEGES ON laravel_vue_example.* TO 'userTest'@'%';

    ~# FLUSH PRIVILEGES;
    

## Testar localmente

1. Baixar o projeto

	~# git clone https://github.com/IBNL/laravel_vue_example.git


2. Configurar

- Caminhe até a raiz do projeto

    ~# cd ~/laravel_vue_example

- Na raiz do projeto, renomei o arquivo .env.example para .env e adicione as informações do banco de dados.

- Instalar dependências
  
    ~# composer install
    
    ~# npm install

    ~# npm run dev

- Gerar tabelas
 
    ~# php artisan migrate

- Gerar chave da aplicação

    ~# php artisan key:generate

3. Gerar Token

- adicionar usuário do app com seeder

    ~# php artisan db:seed --class=UserTableSeeder

- Fazer login e pegar o token

Metodo POST: http://localhost:8000/api/login

Body(form-data):

    email: app@app.com       
    password: 1kwmsaefgsAepo


4. Adicionar token no arquivo .env

    Pegar o token gerado anteriormente e adicioná-lo no MIX_APP_API dentro do arquivo .env

- Limpar cache da aplicação
    
    ~# php artisan config:clear && php artisan config:cache && php artisan cache:clear && php artisan view:clear

- Compilar scripts

    ~# npm run dev

4. Subir o servidor de teste

Na Raiz do projeto utilize o comando:

    ~# php artisan serve


