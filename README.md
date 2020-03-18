# mosly

## Como fazer funcionar API no servidor?
  - Esse api tem como Ambiente de execução DOCKER
  - para funcionar bem, é necessario ter no seu ambiente linux o docker-compose instalado, sendo assim
  acessando o diretorio do projeto e executar os comandos:
    
    - docker-compose build => isso irá buildar todos as feramentas e configurações necessarias para poder funcionar o projeto.
    - caso tenha ja de prontidão todos os recursos necessários será necessario apenas executar o doker-compose up -d ,no qual fará
    rodar a aplicação.
    - todos os arquivos necessarios para configuração de host do docker se encontra dentro do projeto.
    - O arquivos do banco tambem se encontra dentro do projeto.
    - o banco de dados tem o nome de "mosly", no qual possui 2 tabelas usuario, drinks,
    - Para acessar o banco de dados as credencias são so seguinte: database = mosly, user = root e senha = secret.
    - Tambem disponibilizei um arquivo .json com algumas orientações dos testes realizados usando a ferament Postman.
    nesse arquivo estão os requests e com os seus devidos paramentros.
    Gratidão: Tinoweb.
