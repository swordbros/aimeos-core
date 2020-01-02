<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2015-2018
 * @package MShop
 * @subpackage Coupon
 */


namespace Aimeos\MShop\Coupon\Provider\Decorator;


/**
 * Example decorator for coupon provider.
 *
 * @package MShop
 * @subpackage Coupon
 */
class Example
	extends \Aimeos\MShop\Coupon\Provider\Decorator\Base
	implements \Aimeos\MShop\Coupon\Provider\Decorator\Iface
{
	/**
	 * Updates the result of a coupon to the order instance.
	 *
	 * @param \Aimeos\MShop\Order\Item\Iface $order Basic order of the customer
	 * @return \Aimeos\MShop\Coupon\Provider\Iface Provider object for method chaining
	 */
	public function update( \Aimeos\MShop\Order\Item\Iface $order )
	{
		$this->getProvider()->update( $order );
		return $this;
	}


	/**
	 * Tests if a coupon should be granted
	 *
	 * @param \Aimeos\MShop\Order\Item\Iface $order
	 */
	public function isAvailable( \Aimeos\MShop\Order\Item\Iface $order )
	{
		return true;
	}
}
