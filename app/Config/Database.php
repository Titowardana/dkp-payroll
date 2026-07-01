<?php namespace Config;

class Database extends \CodeIgniter\Database\Config
{
    public $filesPath = APPPATH . 'Database/';
    public $defaultGroup = 'default';
    public $default = [
        'DSN'      => '',
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'dkp_payroll',
        'DBDriver' => 'MySQLi',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug'  => true,
        'cacheOn'  => false,
        'cacheDir' => '',
        'charset'  => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre'  => '',
        'encrypt'  => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port'     => 3306,
    ];
    public $tests = [
        'DSN'      => '',
        'hostname' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'database' => ':memory:',
        'DBDriver' => 'SQLite3',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug'  => true,
        'cacheOn'  => false,
        'cacheDir' => '',
        'charset'  => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre'  => '',
        'encrypt'  => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port'     => 3306,
    ];

    public function __construct()
    {
        parent::__construct();

        $this->default['hostname'] = env('database.default.hostname', 'localhost');
        $this->default['username'] = env('database.default.username', 'root');
        $this->default['password'] = env('database.default.password', '');
        $this->default['database'] = env('database.default.database', 'dkp_payroll');
        $this->default['DBDriver'] = env('database.default.DBDriver', 'MySQLi');
        $this->default['port'] = (int) env('database.default.port', 3306);
        $this->default['DBDebug'] = (ENVIRONMENT !== 'production');

        $this->tests['username'] = env('database.default.username', 'root');
        $this->tests['password'] = env('database.default.password', '');
        $this->tests['port'] = (int) env('database.default.port', 3306);
        $this->tests['DBDebug'] = (ENVIRONMENT !== 'production');

        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
        }
    }
}
