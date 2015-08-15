<?php

/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @package Client
 * @subpackage Html
 */


// Strings for translation
sprintf( 'summary' );


/**
 * Default implementation of checkout summary HTML client.
 *
 * @package Client
 * @subpackage Html
 */
class Client_Html_Checkout_Standard_Summary_Default
	extends Client_Html_Abstract
{
	/** client/html/checkout/standard/summary/default/subparts
	 * List of HTML sub-clients rendered within the checkout standard summary section
	 *
	 * The output of the frontend is composed of the code generated by the HTML
	 * clients. Each HTML client can consist of serveral (or none) sub-clients
	 * that are responsible for rendering certain sub-parts of the output. The
	 * sub-clients can contain HTML clients themselves and therefore a
	 * hierarchical tree of HTML clients is composed. Each HTML client creates
	 * the output that is placed inside the container of its parent.
	 *
	 * At first, always the HTML code generated by the parent is printed, then
	 * the HTML code of its sub-clients. The order of the HTML sub-clients
	 * determines the order of the output of these sub-clients inside the parent
	 * container. If the configured list of clients is
	 *
	 *  array( "subclient1", "subclient2" )
	 *
	 * you can easily change the order of the output by reordering the subparts:
	 *
	 *  client/html/<clients>/subparts = array( "subclient1", "subclient2" )
	 *
	 * You can also remove one or more parts if they shouldn't be rendered:
	 *
	 *  client/html/<clients>/subparts = array( "subclient1" )
	 *
	 * As the clients only generates structural HTML, the layout defined via CSS
	 * should support adding, removing or reordering content by a fluid like
	 * design.
	 *
	 * @param array List of sub-client names
	 * @since 2014.03
	 * @category Developer
	 */
	private $_subPartPath = 'client/html/checkout/standard/summary/default/subparts';

	/** client/html/checkout/standard/summary/address/name
	 * Name of the address part used by the checkout standard summary client implementation
	 *
	 * Use "Myname" if your class is named "Client_Html_Checkout_Standard_Summary_Address_Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */

	/** client/html/checkout/standard/summary/service/name
	 * Name of the service part used by the checkout standard summary client implementation
	 *
	 * Use "Myname" if your class is named "Client_Html_Checkout_Standard_Summary_Service_Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */

	/** client/html/checkout/standard/summary/coupon/name
	 * Name of the coupon part used by the checkout standard summary client implementation
	 *
	 * Use "Myname" if your class is named "Client_Html_Checkout_Standard_Summary_Coupon_Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */

	/** client/html/checkout/standard/summary/option/name
	 * Name of the option part used by the checkout standard summary client implementation
	 *
	 * Use "Myname" if your class is named "Client_Html_Checkout_Standard_Summary_Option_Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */

	/** client/html/checkout/standard/summary/detail/name
	 * Name of the detail part used by the checkout standard summary client implementation
	 *
	 * Use "Myname" if your class is named "Client_Html_Checkout_Standard_Summary_Detail_Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */
	private $_subPartNames = array( 'address', 'service', 'coupon', 'option', 'detail' );


	/**
	 * Returns the HTML code for insertion into the body.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @param array &$tags Result array for the list of tags that are associated to the output
	 * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
	 * @return string HTML code
	 */
	public function getBody( $uid = '', array &$tags = array(), &$expire = null )
	{
		$view = $this->getView();
		$step = $view->get( 'standardStepActive' );
		$onepage = $view->config( 'client/html/checkout/standard/onepage', array() );

		if( $step != 'summary' && !( in_array( 'summary', $onepage ) && in_array( $step, $onepage ) ) ) {
			return '';
		}

		$view = $this->_setViewParams( $view, $tags, $expire );

		$html = '';
		foreach( $this->_getSubClients() as $subclient ) {
			$html .= $subclient->setView( $view )->getBody( $uid, $tags, $expire );
		}
		$view->summaryBody = $html;

		/** client/html/checkout/standard/summary/default/template-body
		 * Relative path to the HTML body template of the checkout standard summary client.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the result shown in the body of the frontend. The
		 * configuration string is the path to the template file relative
		 * to the layouts directory (usually in client/html/layouts).
		 *
		 * You can overwrite the template file configuration in extensions and
		 * provide alternative templates. These alternative templates should be
		 * named like the default one but with the string "default" replaced by
		 * an unique name. You may use the name of your project for this. If
		 * you've implemented an alternative client class as well, "default"
		 * should be replaced by the name of the new class.
		 *
		 * @param string Relative path to the template creating code for the HTML page body
		 * @since 2014.03
		 * @category Developer
		 * @see client/html/checkout/standard/summary/default/template-header
		 */
		$tplconf = 'client/html/checkout/standard/summary/default/template-body';
		$default = 'checkout/standard/summary-body-default.html';

		return $view->render( $this->_getTemplate( $tplconf, $default ) );
	}


	/**
	 * Returns the HTML string for insertion into the header.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @param array &$tags Result array for the list of tags that are associated to the output
	 * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
	 * @return string|null String including HTML tags for the header on error
	 */
	public function getHeader( $uid = '', array &$tags = array(), &$expire = null )
	{
		$view = $this->getView();
		$step = $view->get( 'standardStepActive' );
		$onepage = $view->config( 'client/html/checkout/standard/onepage', array() );

		if( $step != 'summary' && !( in_array( 'summary', $onepage ) && in_array( $step, $onepage ) ) ) {
			return '';
		}

		$view = $this->_setViewParams( $view, $tags, $expire );

		$html = '';
		foreach( $this->_getSubClients() as $subclient ) {
			$html .= $subclient->setView( $view )->getHeader( $uid, $tags, $expire );
		}
		$view->summaryHeader = $html;

		/** client/html/checkout/standard/summary/default/template-header
		 * Relative path to the HTML header template of the checkout standard summary client.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the HTML code that is inserted into the HTML page header
		 * of the rendered page in the frontend. The configuration string is the
		 * path to the template file relative to the layouts directory (usually
		 * in client/html/layouts).
		 *
		 * You can overwrite the template file configuration in extensions and
		 * provide alternative templates. These alternative templates should be
		 * named like the default one but with the string "default" replaced by
		 * an unique name. You may use the name of your project for this. If
		 * you've implemented an alternative client class as well, "default"
		 * should be replaced by the name of the new class.
		 *
		 * @param string Relative path to the template creating code for the HTML page head
		 * @since 2014.03
		 * @category Developer
		 * @see client/html/checkout/standard/summary/default/template-body
		 */
		$tplconf = 'client/html/checkout/standard/summary/default/template-header';
		$default = 'checkout/standard/summary-header-default.html';

		return $view->render( $this->_getTemplate( $tplconf, $default ) );
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return Client_Html_Interface Sub-client object
	 */
	public function getSubClient( $type, $name = null )
	{
		return $this->_createSubClient( 'checkout/standard/summary/' . $type, $name );
	}


	/**
	 * Processes the input, e.g. store given values.
	 * A view must be available and this method doesn't generate any output
	 * besides setting view variables.
	 */
	public function process()
	{
		$view = $this->getView();

		if( $view->param( 'cs_order', null ) === null ) {
			return;
		}

		try
		{
			$controller = Controller_Frontend_Factory::createController( $this->_getContext(), 'basket' );
			$controller->get()->check( MShop_Order_Item_Base_Abstract::PARTS_ALL );

			parent::process();
		}
		catch( Exception $e )
		{
			$view->standardStepActive = 'summary';
			throw $e;
		}
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of HTML client names
	 */
	protected function _getSubClientNames()
	{
		return $this->_getContext()->getConfig()->get( $this->_subPartPath, $this->_subPartNames );
	}
}