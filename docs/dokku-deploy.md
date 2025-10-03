# Deploy do Datagrad com Dokku

## Instalação do Docker e do Dokku

Se quiser subir uma VM com o Dokku de forma rápida, usando o Vagrant, veja o tutorial [Provisionamento de VM com Dokku](https://hackmd.io/@ccifusp/Hk4qbFphex)

**O Dokku só suporta Debian e Ubuntu**.

O script abaixo adiciona os repositórios e instala o Docker e o Dokku. Se não quiser executar o script sem saber ao certo o que está fazendo, pode ver a documentação da instalação do [Docker](https://docs.docker.com/engine/install/debian/) e do [Dokku](https://dokku.com/docs/getting-started/install/debian/#debian-package-installation-notes).

```bash=
wget -NP . https://dokku.com/install/v0.36.7/bootstrap.sh
sudo DOKKU_TAG=v0.36.7 bash bootstrap.sh
```

Adicione sua chave SSH pública ao dokku:

```bash=
echo 'conteudo-da-sua-chave-publica' | sudo dokku ssh-keys:add admin
```

## No servidor Dokku

### Criação e configuração do app

Variáveis de ambiente:

```bash=
export ADMIN_EMAIL="mail@example.com"
export APP_NAME="datagrad"
export APP_DOMAIN="example.com"
export MARIADB_NAME="mariadb_$APP_NAME"
```

Instalação dos plugins necessários:

```bash=
sudo dokku plugin:install https://github.com/dokku/dokku-mariadb.git --name mariadb
sudo dokku plugin:install https://github.com/dokku/dokku-maintenance.git
sudo dokku plugin:install https://github.com/dokku/dokku-letsencrypt.git
```

Criação do app:

```bash=
dokku apps:create $APP_NAME
dokku checks:disable $APP_NAME
dokku domains:set $APP_NAME $APP_DOMAIN
dokku letsencrypt:set $APP_NAME email $ADMIN_EMAIL
```

O Dokku faz o link do _service_ MariaDB com a aplicação através da variável de ambiente **DATABASE_URL**. O Laravel já tem a variável no arquivo `config/database.php`. Sendo assim, só precisamos criar o banco de dados e fazer o link com a aplicação. No ".env" basta setar **DB_CONNECTION="mariadb"**, os parâmetros da conexão já estarão na **DATABASE_URL**.

```bash=
dokku mariadb:create $MARIADB_NAME
dokku mariadb:link $MARIADB_NAME $APP_NAME
```

Criação das variáveis de ambiente para reproduzir o `.env`:

```bash=
dokku config:set --no-restart $APP_NAME \
    APP_DEBUG="true" \
    APP_ENV="production" \
    APP_KEY="base64:$(openssl rand -base64 32)" \
    APP_NAME="Datagrad" \
    APP_URL="https://$APP_DOMAIN" \
    CODHABS="0,1,2,3,4" \
    DB_CONNECTION="mariadb" \
    EVASAO_CODCUR_HAB_IGNORADOS="" \
    EVASAO_CODCUR_IGNORADOS="" \
    REPLICADO_CODUNDCLG="43,66" \
    REPLICADO_CODUNDCLGS="43,66" \
    REPLICADO_DATABASE="ifusp" \
    REPLICADO_HOST="replicado.example.com" \
    REPLICADO_PASSWORD="senhareplicado" \
    REPLICADO_PORT="5000" \
    REPLICADO_SYBASE="true" \
    REPLICADO_USERNAME="usuarioreplicado" \
    SENHAUNICA_ADMINS="5248392" \
    SENHAUNICA_CALLBACK_ID="25" \
    SENHAUNICA_CODIGO_UNIDADE="43" \
    SENHAUNICA_KEY="if" \
    SENHAUNICA_SECRET="chave_oauth_consumidor" \
    USP_THEME_SKIN="if"
```

### Volumes da aplicação

```bash=
dokku storage:ensure-directory $APP_NAME
dokku storage:mount $APP_NAME /var/lib/dokku/data/storage/$APP_NAME/storage:/var/www/html/storage
dokku storage:mount $APP_NAME /var/lib/dokku/data/storage/$APP_NAME/bootstrap-cache:/var/www/html/bootstrap/cache
```

## Na máquina de desenvolvimento

Para fazer o deploy com o Dokku, precisamos dos arquivos que estão na pasta raiz e na pasta `dokku-deploy` do projeto:

- Dockerfile
- Procfile: vai executar o release.sh e o `apachectl` durante o estágio de release.
- dokku-deploy/apache-php.conf: configurações personalizadas do PHP para o Apache. É opcional, foi adicionado para referência do que pode ser feito.
- dokku-deploy/release.sh: executa as tasks durante o estágio de release. É invocado pelo `Procfile`.

### Criação do git remote e deploy

Configuração do _git remote_ para o deploy:

```bash=
git remote add dokku dokku@hostname-ou-ip-do-servidor-dokku:datagrad
```

Deploy:

```bash=
git push dokku <seu-branch-local>:main
```

## Pós-deploy

Depois de subir a aplicação, entre no container e execute:

```bash=
dokku enter $APP_NAME
composer install --no-dev --optimize-autoloader
php artisan vendor:publish --provider="Uspdev\UspTheme\ServiceProvider" --tag=assets --force
```
