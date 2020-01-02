<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2011
 * @copyright Aimeos (aimeos.org), 2015-2018
 */


namespace Aimeos\MShop\Plugin\Provider\Order;


class ShippingTest extends \PHPUnit\Framework\TestCase
{
	private $context;
	private $object;
	private $plugin;


	protected function setUp()
	{
		$this->context = \TestHelperMShop::getContext();
		$this->plugin = \Aimeos\MShop::create( $this->context, 'plugin' )->createItem();

		$this->object = new \Aimeos\MShop\Plugin\Provider\Order\Shipping( $this->context, $this->plugin );
	}


	protected function tearDown()
	{
		unset( $this->object, $this->plugin, $this->context );
	}


	public function testCheckConfigBE()
	{
		$attributes = array(
			'threshold' => ['EUR' => '50.00'],
		);

		$result = $this->object->checkConfigBE( $attributes );

		$this->assertEquals( 1, count( $result ) );
		$this->assertEquals( null, $result['threshold'] );
	}


	public function testGetConfigBE()
	{
		$list = $this->object->getConfigBE();

		$this->assertEquals( 1, count( $list ) );
		$this->assertArrayHasKey( 'threshold', $list );

		foreach( $list as $entry ) {
			$this->assertInstanceOf( \Aimeos\MW\Criteria\Attribute\Iface::class, $entry );
		}
	}


	public function testRegister()
	{
		$this->object->register( \Aimeos\MShop::create( $this->context, 'order' )->createItem() );
	}


	public function testUpdate()
	{
		$this->plugin = $this->plugin->setProvider( 'Shipping' )
			->setConfig( ['threshold' => ['EUR' => '34.00']] );

		$manager = \Aimeos\MShop::create( $this->context, 'product' );
		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'product.code', ['CNE', 'CNC', 'IJKL'] ) );

		$products = [];
		foreach( $manager->searchItems( $search, ['price'] ) as $prod ) {
			$products[$prod->getCode()] = $prod;
		}

		if( count( $products ) !== 3 ) {
			throw new \RuntimeException( 'Wrong number of products' );
		}

		if( ( $price = current( $products['IJKL']->getRefItems( 'price' ) ) ) === false ) {
			throw new \RuntimeException( 'No price item found' );
		}
		$price = $price->setValue( 10.00 );

		$orderProductManager = \Aimeos\MShop::create( $this->context, 'order/product' );
		$product = $orderProductManager->createItem()->copyFrom( $products['CNE'] )->setPrice( $price );
		$product2 = $orderProductManager->createItem()->copyFrom( $products['CNC'] )->setPrice( $price );
		$product3 = $orderProductManager->createItem()->copyFrom( $products['IJKL'] )->setPrice( $price );

		$orderServiceManager = \Aimeos\MShop::create( $this->context, 'order/service' );
		$serviceSearch = $orderServiceManager->createSearch();
		$exp = array(
			$serviceSearch->compare( '==', 'order.service.type', 'delivery' ),
			$serviceSearch->compare( '==', 'order.service.costs', '5.00' )
		);
		$serviceSearch->setConditions( $serviceSearch->combine( '&&', $exp ) );
		$results = $orderServiceManager->searchItems( $serviceSearch );

		if( ( $delivery = reset( $results ) ) === false ) {
			throw new \RuntimeException( 'No order service item found' );
		}

		$order = \Aimeos\MShop::create( $this->context, 'order' )->createItem()->off(); // remove event listeners

		$order = $order->addService( $delivery, 'delivery' )
			->addProduct( $product )->addProduct( $product2 )->addProduct( $product3 );


		$this->assertEquals( 5.00, $order->getPrice()->getCosts() );
		$this->assertEquals( null, $this->object->update( $order, 'addProduct' ) );

		$order->addProduct( $product );
		$this->assertEquals( null, $this->object->update( $order, 'addProduct' ) );

		$this->assertEquals( 0.00, $order->getPrice()->getCosts() );
	}
}
