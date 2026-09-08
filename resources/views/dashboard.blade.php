<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard SNMP</title>
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: -apple-system, "Segoe UI", Roboto, sans-serif;
            background: #14161A;
            color: #EDEDEB;
            margin: 0;
            padding: 3rem 2rem;
        }

        .panel {
            max-width: 640px;
            margin: 0 auto;
        }

        header {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            border-bottom: 1px solid #2A2D33;
            padding-bottom: 1rem;
            margin-bottom: 0.5rem;
        }

        h1 {
            font-size: 1.15rem;
            font-weight: 600;
            margin: 0;
            color: #EDEDEB;
        }

        .agent-info {
            font-family: "IBM Plex Mono", "Courier New", monospace;
            font-size: 0.75rem;
            color: #8B8F97;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .agent-info .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #2FA98C;
        }

        .row {
            padding: 1rem 0;
            border-bottom: 1px solid #2A2D33;
        }

        .row:last-child {
            border-bottom: none;
        }

        .label-line {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .label {
            font-size: 0.9rem;
            font-weight: 500;
            color: #EDEDEB;
        }

        .oid {
            font-family: "IBM Plex Mono", "Courier New", monospace;
            font-size: 0.7rem;
            color: #8B8F97;
            white-space: nowrap;
        }

        .value {
            font-family: "IBM Plex Mono", "Courier New", monospace;
            font-size: 0.85rem;
            color: #D8D8D5;
            line-height: 1.5;
            border-left: 2px solid #2A2D33;
            padding-left: 0.8rem;
            word-break: break-word;
        }

        footer {
            margin-top: 1.5rem;
            font-size: 0.72rem;
            color: #8B8F97;
        }
    </style>
</head>
<body>

@php
    // Remove o prefixo de tipo (STRING:, Counter32: etc.) e formata Timeticks
    // em algo legível, para não exibir a saída "crua" do SNMP no dashboard.
    function limparValorSnmp($valor) {
        if (preg_match('/^Timeticks:\s*\((\d+)\)/', $valor, $m)) {
            $centesimos = (int) $m[1];
            $segundosTotais = intdiv($centesimos, 100);
            $dias = intdiv($segundosTotais, 86400);
            $horas = intdiv($segundosTotais % 86400, 3600);
            $minutos = intdiv($segundosTotais % 3600, 60);
            $segundos = $segundosTotais % 60;
            return sprintf('%dd %02dh %02dm %02ds', $dias, $horas, $minutos, $segundos);
        }

        $limpo = preg_replace('/^[A-Za-z0-9]+:\s*/', '', $valor);
        return trim($limpo, "\" \t\n\r\0\x0B");
    }
@endphp

    <div class="panel">
        <header>
            <h1>Dashboard SNMP — MIB-2</h1>
            <div class="agent-info">
                <span class="dot"></span>
                agente em 127.0.0.1 (SNMPv2c)
            </div>
        </header>

        <div class="row">
            <div class="label-line">
                <span class="label">Descrição do sistema</span>
                <span class="oid">.1.3.6.1.2.1.1.1.0</span>
            </div>
            <div class="value">{{ limparValorSnmp($dados['sysDescr (Descrição do sistema)']) }}</div>
        </div>

        <div class="row">
            <div class="label-line">
                <span class="label">Tempo ligado</span>
                <span class="oid">.1.3.6.1.2.1.1.3.0</span>
            </div>
            <div class="value">{{ limparValorSnmp($dados['sysUpTime (Tempo ligado)']) }}</div>
        </div>

        <div class="row">
            <div class="label-line">
                <span class="label">Nome do host</span>
                <span class="oid">.1.3.6.1.2.1.1.5.0</span>
            </div>
            <div class="value">{{ limparValorSnmp($dados['sysName (Nome do host)']) }}</div>
        </div>

        <div class="row">
            <div class="label-line">
                <span class="label">Bytes recebidos (enp63s0)</span>
                <span class="oid">.1.3.6.1.2.1.2.2.1.10.2</span>
            </div>
            <div class="value">{{ limparValorSnmp($dados['ifInOctets (Bytes recebidos - enp63s0)']) }}</div>
        </div>

        <div class="row">
            <div class="label-line">
                <span class="label">Bytes enviados (enp63s0)</span>
                <span class="oid">.1.3.6.1.2.1.2.2.1.16.2</span>
            </div>
            <div class="value">{{ limparValorSnmp($dados['ifOutOctets (Bytes enviados - enp63s0)']) }}</div>
        </div>

        <footer>
            Gerência de Redes de Computadores — Sistemas de Informação — UFGD
        </footer>
    </div>
</body>
</html>