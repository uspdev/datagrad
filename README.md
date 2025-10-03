# Datagrad

Sistema que auxilia na elaboração do relatório de revalidação de cursos de graduação, e outras informações relacionadas à graduação.

[Apresentação na EESC](https://www.youtube.com/watch?v=18A8jRwIWIA) em 9/8/2024.

## Funcionalidades

* Gera lista de disciplinas por curso (grade curricular)
* Gera lista de turmas oferecidas por curso em determinado período
* Gera relatório de lista de nomes para relatório síntese
* Gera relatório de lista de nomes para relatório complementar
* Gera relatório de carga didática por lista de nomes e por período
* Gera relatório de grade horária por lista de nomes/codpes
* Gera relatório de evasão por curso e ano de ingresso dos alunos
* Facilita a alteração de disciplinas, principalmente no processo de tradução para inglês e cadastro de atividade extensionista (em andamento)

## Autorizações

* A variável SENHAUNICA_ADMINS do .env não é utilizada, ou seja, os admins não têm acesso aos itens do menu. Para darmos acesso, fazemos o seguinte (em run-time):
- clicar no ícone do Senhaunica-socialite > Usuários;
- na relação que for apresentada, no usuário em questão, clicar em qualquer permissão dele (por exemplo, "admin (env)" ou "Servidor");
- no pop-up que se abre, marcar os itens a serem autorizados e então clicar no botão "Salvar".

## Documentação

- [Disciplinas](docs/disciplinas.md)
- [Dokku Deploy](docs/dokku-deploy.md)
- [Starter](docs/starter.md)

## Changelog

03/2024
* iniciando gerenciamento de alteração de disicplinas
* atualizado laravel 10 / PHP 8.3

30/10/2023
* incluído relatório de evasão

01/08/2023
* incluído relatório de grade horária

13/6/2023
* incluído relatório de carga didática
* melhorado grade curricular
* implementado cache para algumas partes. A ser expandido.

## Requisitos

Aplicação laravel padrão


### Em produção

Para receber as últimas atualizações do sistema rode:

```sh
git pull
composer install --no-dev
php artisan migrate
```

## Dicas

* Copy to clipboard só funciona em conexão https


## Instalação

### Básico
```sh
git clone git@github.com:uspdev/datagrad
composer install --no-dev

# Configure o .env conforme a necessidade
cp .env.example .env

php artisan key:generate
```

### Cache (opcional)

Algumas partes podem usar cache ([https://github.com/uspdev/cache](https://github.com/uspdev/cache)). Para utilizá-lo você precisa instalar e configurar o memcached no mesmo servidor da aplicação.

```bash
apt install memcached
vim /etc/memcached.conf
    I = 5M
    -m 128

/etc/init.d/memcached restart
```

### Apache ou nginx

Deve apontar para a <pasta do projeto>/public, assim como qualquer projeto laravel.

No Apache é possivel utilizar a extensão MPM-ITK (http://mpm-itk.sesse.net/) que permite rodar seu Servidor Virtual com usuário próprio. Isso facilita rodar o sistema como um usuário comum e não precisa ajustar as permissões da pasta storage/.

```bash
sudo apt install libapache2-mpm-itk
sudo a2enmod mpm_itk
sudo service apache2 restart
```

Dentro do seu virtualhost coloque

```apache
<IfModule mpm_itk_module>
AssignUserId nome_do_usuario nome_do_grupo
</IfModule>
```

### Senha única

Cadastre uma nova URL no configurador de senha única utilizando o caminho https://seu_app/callback. Guarde o callback_id para colocar no arquivo .env.

### Banco de dados

* DEV

    `php artisan migrate:fresh --seed`

* Produção

    `php artisan migrate`

### Supervisor (opcional)

Para as filas de envio de email o sistema precisa de um gerenciador que mantenha rodando o processo que monitora as filas. O recomendado é o **Supervisor**. No Ubuntu ou Debian instale com:

    sudo apt install supervisor

Modelo de arquivo de configuração. Como **`root`**, crie o arquivo `/etc/supervisor/conf.d/chamados_queue_worker_default.conf` com o conteúdo abaixo:

    [program:chamados_queue_worker_default]
    command=/usr/bin/php /home/sistemas/chamados/artisan queue:listen --queue=default --tries=3 --timeout=60
    process_num=1
    username=www-data
    numprocs=1
    process_name=%(process_num)s
    priority=999
    autostart=true
    autorestart=unexpected
    startretries=3
    stopsignal=QUIT
    stderr_logfile=/var/log/supervisor/chamados_queue_worker_default.log

Ajustes necessários:

    command=<ajuste o caminho da aplicação>
    username=<nome do usuário do processo do chamados>
    stderr_logfile = <aplicacao>/storage/logs/<seu arquivo de log>

Reinicie o **Supervisor**

    sudo supervisorctl reread
    sudo supervisorctl update
    sudo supervisorctl restart all

## Problemas e soluções

Alguma dica de como resolver problemas comuns?

## Histórico

Registre o log das principais alterações
