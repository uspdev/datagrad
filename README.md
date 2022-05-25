# Starter

Descrição da aplicação a ser criada nesse starter

## Funcionalidades

* Descreva suas funcionalidades aqui
* Pode colocar prints de tela

## Requisitos

O que é necessário para rodar esta aplicação

## Atualização

[Se houver instruções específicas sobre atualizações, descreva aqui.]

### Em produção

Para receber as últimas atualizações do sistema rode:

    git pull
    composer install --no-dev
    php artisan migrate



## Instalação

[Descreva como instalar a aplicação]

### Básico

    git clone git@github.com:uspdev/chamados
    composer install
    cp .env.example .env
    php artisan key:generate

Configure o .env conforme a necessidade

### Cache (opcional)

Algumas partes podem usar cache ([https://github.com/uspdev/cache](https://github.com/uspdev/cache)). Para utilizá-lo você precisa instalar e configurar o memcached no mesmo servidor da aplicação.

    apt install memcached
    vim /etc/memcached.conf
        I = 5M
        -m 128

    /etc/init.d/memcached restart

### Email

O gmail utiliza senhas de app (https://support.google.com/accounts/answer/185833?hl=pt-BR) desde maio/2022. Siga os passos para gerar uma senha de app para sua aplicação.

### Apache ou nginx

Deve apontar para a <pasta do projeto>/public, assim como qualquer projeto laravel.

No Apache é possivel utilizar a extensão MPM-ITK (http://mpm-itk.sesse.net/) que permite rodar seu Servidor Virtual com usuário próprio. Isso facilita rodar o sistema como um usuário comum e não precisa ajustar as permissões da pasta storage/.

sudo apt install libapache2-mpm-itk
sudo a2enmod mpm_itk
sudo service apache2 restart
Dentro do seu virtualhost coloque

<IfModule mpm_itk_module>
AssignUserId nome_do_usuario nome_do_grupo
</IfModule>

### Senha única

Cadastre uma nova URL no configurador de senha única utilizando o caminho https://seu_app/callback. Guarde o callback_id para colocar no arquivo .env.

### Banco de dados

* DEV

    php artisan migrate:fresh --seed

* Produção

    php artisan migrate

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

