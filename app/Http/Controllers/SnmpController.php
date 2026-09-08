<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SnmpController extends Controller
{
    public function index()
    {
        $host = '127.0.0.1';
        $community = 'public';

        $oids = [
            'sysDescr (Descrição do sistema)'      => '.1.3.6.1.2.1.1.1.0',
            'sysUpTime (Tempo ligado)'              => '.1.3.6.1.2.1.1.3.0',
            'sysName (Nome do host)'                => '.1.3.6.1.2.1.1.5.0',
            'ifInOctets (Bytes recebidos - enp63s0)' => '.1.3.6.1.2.1.2.2.1.10.2',
            'ifOutOctets (Bytes enviados - enp63s0)' => '.1.3.6.1.2.1.2.2.1.16.2',
        ];

        $dados = [];
        foreach ($oids as $nome => $oid) {
            try {
                $valor = snmpget($host, $community, $oid);
            } catch (\Exception $e) {
                $valor = 'erro: ' . $e->getMessage();
            }
            $dados[$nome] = $valor;
        }

        return view('dashboard', compact('dados'));
    }
}
