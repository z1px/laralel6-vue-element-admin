<?php

namespace App\Console\Commands;


use Doctrine\DBAL\Driver\PDOException;
use Illuminate\Console\Command;
use Illuminate\Database\Connectors\Connector;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create database if not exists';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 如果数据库不存在，则创建数据库
        try {
            $driver = app('db')->getConfig('driver') ?: 'mysql';
            $unix_socket = app('db')->getConfig('unix_socket');
            $host = app('db')->getConfig('host') ?: 'localhost';
            $port = app('db')->getConfig('port') ?: 3306;
            $username = app('db')->getConfig('username') ?: 'root';
            $password = app('db')->getConfig('password') ?: '';
            $database = app('db')->getConfig('database') ?: '';
            $charset = app('db')->getConfig('charset') ?: 'utf8mb4';
            $collation = app('db')->getConfig('collation') ?: 'utf8mb4_unicode_ci';

            $options = app(Connector::class)->getOptions(app('db')->getConfig());

            switch ($driver){
                case 'mysql':
                    $dns = $unix_socket ? "{$driver}:unix_socket={$unix_socket}" : "{$driver}:host={$host};port={$port}";
                    break;
                case 'sqlite':
                    if(':memory:' !== $database){
                        $database = realpath($database);
                    }
                    $dns = ':memory:' === "{$driver}:{$database}";
                    break;
            }
            $dbh = new \PDO($dns, $username, $password, $options);
            $dbh->query("CREATE DATABASE IF NOT EXISTS `{$database}` DEFAULT CHARACTER SET `{$charset}` DEFAULT COLLATE `{$collation}`");
            $this->info("数据库{$database}创建成功！！！");
            unset($driver, $unix_socket, $host, $port, $username, $password, $database, $charset, $collation, $options, $dns);
            $dbh = null;
        } catch (\PDOException $exception) {
            $this->error("数据库{$database}创建失败！！！");
            throw new PDOException($exception);
        }
    }
}
