<?php

use RomanMiranda\RsrGroup\RsrDirectConnect;

$rsr = new RsrDirectConnect( $_ENV['RSR_USERNAME'], $_ENV['RSR_PASSWORD']);


test('RsrDirectConnect object', function()use($rsr){
	$this->assertInstanceOf(RsrDirectConnect::class,$rsr);
});


test('get-items', function()use($rsr){

	$resp = $rsr->getItems(['Departments' => ['24'] ]);

	$this->assertTrue($resp['Response']['HTTPStatusCode'] == 200);

	$this->assertTrue(count($resp['Items']) == $resp['RowCount'] );
});


test('check-catalog', function()use($rsr){

	$params = [
		'Storename' => 'Police and Fire',
		'ShipAddress' => '4405 Metric Dr',
		'ShipCity' => 'Winter Park',
		'ShipState' => 'FL',
		'ShipZip' => '32792',
		'LookupBy' => 'S', // 
		'Items' => [
			'AAC17-22G4',
			'AAC17-22G5',
		],
	];

	$resp = $rsr->checkCatalog($params);

	$this->assertEquals($resp['Status'],'OK');

});

test('place-order', function()use($rsr){

	$params = [
		'Storename' => 'Police & Fire',
		'ShipAddress' => '4405 Metric Dr',
		'ShipCity' => 'Winter Park',
		'ShipState' => 'FL',
		'ShipZip' => '32792',
		'ShipToStore' => 'F',
		'ShipAcccount' => '',
		'ShipFFL' => '',
		'Email' => '',
		'Items' => [
			'PartNum' => 'AAC17-22G5',
			'WishQty' => '1',
		],
		'PONum' => 'PO-' . rand(),
		'FillOrKill' => 'Y',
	];

	$resp = $rsr->placeOrder($params);

	$this->assertEquals($resp['Status'],'OK');

});