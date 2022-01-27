<?php
ini_set("default_socket_timeout", 60);

define('DS', DIRECTORY_SEPARATOR);

include '../vendor/autoload.php';

use Nfsews\Config;
use Nfsews\Certificate\Certificate;
use Nfsews\Connection;
use Nfsews\Providers\Prodam\V2\Request\PedidoConsultaNfe;
use Nfsews\Providers\Prodam\V2\Request\ConsultaNfeFragmento;



// PARA CERTIFICADO VÁLIDO
$options = [
    'soapOptions' => [
        'ssl'	=>	[
            'cafile'	=>	__dir__ . DS . 'ca_mozilla_2019.pem',

        ]
    ]
];


$config = new Config($options);
$config->setPfxCert(__dir__ . DS . 'moises.pfx');
$config->setPasswordCert('demo1');
$config->setTmpDirectory(__dir__ . DS . 'tmp');
//$config->setXmlDirectory(__dir__ . DS . 'tmp' . DS . 'xml');

$config->setWsdl('https://nfe.prefeitura.sp.gov.br/ws/lotenfe.asmx?WSDL');

$certificate = new Certificate($config);

$pedido = new PedidoConsultaNfe();
$pedido->setCpfCnpjRemetente('12457457000114');
//$pedido->setCpfCnpjContribuinte('00.0000.00/5422-45');
//$pedido->setNumeroLote('4511');

$fragmento = new ConsultaNfeFragmento();
$fragmento->setNumeroNfe('1');
$fragmento->setCodigoVerificacao('124fds75');
$fragmento->setInscricaoMunicipalPrestador('10000248');
$pedido->addConsultaNfeFragmento($fragmento);


$fragmento = new ConsultaNfeFragmento();
$fragmento->setNumeroNfe('2');
$fragmento->setCodigoVerificacao('124fds75');
$fragmento->setInscricaoMunicipalPrestador('10000248');
$pedido->addConsultaNfeFragmento($fragmento);

$fragmento = new ConsultaNfeFragmento();
$fragmento->setNumeroRps(5);
$fragmento->setSerieRps('DKG-1');
$fragmento->setInscricaoMunicipalPrestador('10000248');
$pedido->addConsultaNfeFragmento($fragmento);


// Realizar a conexao
$connection = new Connection($config, $certificate);
// Enviar o RPS
$response = $connection->dispatch($pedido, true);


var_dump( $response);


?>