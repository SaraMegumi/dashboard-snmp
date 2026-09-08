# Dashboard SNMP — MIB-2

Trabalho da disciplina **Gerência de Redes de Computadores** — Sistemas de Informação — UFGD.
Professor: Vanderson Hafemann Fragal.

## Integrantes
- Sara Megumi Sakai dos Santos — Configuração e testes do agente SNMP (Net-SNMP/snmpd) como
  serviço no Linux; definição e validação dos 5 OIDs do MIB-2 via snmpget/snmpwalk; Desenvolvimento do dashboard (view Blade, layout e estilização em CSS);
  documentação do projeto (README) 
- Artur de Oliveira Fonseca — desenvolvimento do backend em Laravel (controller e integração com a extensão
  php-snmp); Testes de integração entre frontend e backend;

## Descrição
Aplicação web em **PHP/Laravel** que consulta um agente SNMP (Net-SNMP) rodando
localmente como serviço, e exibe em um dashboard 5 informações do MIB-2.

## OIDs utilizados

| Item | OID | Descrição |
|---|---|---|
| sysDescr | `.1.3.6.1.2.1.1.1.0` | Descrição do sistema operacional |
| sysUpTime | `.1.3.6.1.2.1.1.3.0` | Tempo de atividade do sistema |
| sysName | `.1.3.6.1.2.1.1.5.0` | Nome do host |
| ifInOctets | `.1.3.6.1.2.1.2.2.1.10.2` | Bytes recebidos na interface enp63s0 |
| ifOutOctets | `.1.3.6.1.2.1.2.2.1.16.2` | Bytes enviados na interface enp63s0 |

## Tecnologias
- PHP 8.3 / Laravel 13
- Extensão `php-snmp`
- Net-SNMP (`snmpd`) como agente, rodando via systemd
- Blade + CSS puro para o dashboard

## Como rodar (Linux)

### 1. Instalar e configurar o agente SNMP
```bash
sudo apt update
sudo apt install snmpd snmp
```
No arquivo `/etc/snmp/snmpd.conf`, garanta as linhas: agentaddress 127.0.0.1,[::1]
rocommunity public 127.0.0.1

Reiniciar o serviço:
```bash
sudo systemctl restart snmpd
sudo systemctl enable snmpd
```
Testar:
```bash
snmpwalk -v2c -c public 127.0.0.1 .1.3.6.1.2.1.1
```

### 2. Instalar dependências PHP
```bash
sudo apt install php-cli php-mbstring php-xml php-zip php-snmp unzip curl composer
sudo phpenmod snmp
```

### 3. Rodar o projeto
```bash
git clone https://github.com/SEU_USUARIO/snmp-dashboard.git
cd snmp-dashboard
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

Acesse: `http://127.0.0.1:8000`

## Observação
O IP/community usados (`127.0.0.1` / `public`) são apenas para fins didáticos.
Em ambiente de produção, recomenda-se SNMPv3 com autenticação.