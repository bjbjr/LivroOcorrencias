<?php
// env-configurator.php
function generateEnvFile($config) {
    $template = <<<ENV
APP_NAME="Livro de Ocorrências"
APP_ENV=production
APP_KEY={$config['app_key']}
APP_DEBUG=false
APP_URL={$config['app_url']}

DB_CONNECTION=mysql
DB_HOST={$config['db_host']}
DB_PORT={$config['db_port']}
DB_DATABASE={$config['db_name']}
DB_USERNAME={$config['db_user']}
DB_PASSWORD="{$config['db_pass']}"

MAIL_MAILER=smtp
MAIL_HOST={$config['mail_host']}
MAIL_PORT={$config['mail_port']}
MAIL_USERNAME={$config['mail_user']}
MAIL_PASSWORD="{$config['mail_pass']}"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS={$config['mail_from']}

TELEGRAM_BOT_TOKEN={$config['telegram_token']}
WHATSAPP_API_KEY={$config['whatsapp_key']}

STORAGE_PATH=storage
ENV;

    file_put_contents(__DIR__.'/../.env', $template);
}