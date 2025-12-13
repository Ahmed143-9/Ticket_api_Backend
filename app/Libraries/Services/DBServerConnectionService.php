<?php
namespace App\Libraries\Services;

use Illuminate\Support\Facades\DB;

class DBServerConnectionService
{
    public function processDBConnectionParams_b_on_db_server_id($db_server_id)
    {
        $db_server_credentials = DB::table('db_server_list')->where('id', $db_server_id)->first();
        $params = [
            'connection' => 'multi_db_connect_'.rand(99,1000).'_'.rand(999,10000),
            'database' => $db_server_credentials->db,
            'driver' => 'mysql',
            'host' => $db_server_credentials->server_ip ?? 'localhost',
            'port' => $db_server_credentials->port ?? '3306',
            'username' => $db_server_credentials->username,
            'password' => rsa_decrypt($db_server_credentials->password)
        ];

        return (object) $params;
    }

    public function setDBConnection($params=[])
    {
        $params = $params;
        config(['database.connections.'.$params->connection => [
            'database' => $params->database ?? '',
            'driver' => $params->driver ?? 'mysql',
            'host' => $params->host ?? '127.0.0.1',
            'port' => $params->port ?? '3306',
            'username' => $params->username ?? '',
            'password' => $params->password ?? '',
            'strict' => false,
        ]]);

        return DB::connection($params->connection);
    }
}
?>
