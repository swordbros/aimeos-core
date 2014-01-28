/*!
 * Copyright (c) Metaways Infosystems GmbH, 2011
 * LGPLv3, http://www.arcavias.com/en/license
 */


/* superglobal lang stubs */

_ = function( string ) { return MShop.I18n.dt( 'client/extjs/ext', string ); };
_n = function( singular, plural, num ) {return MShop.I18n.dn( 'client/extjs/ext', singular, plural, num ); };


Ext.onReady(function() {

	Ext.ns('MShop.API');
    
    // init jsonSMD
    Ext.Direct.addProvider(Ext.apply(MShop.config.smd, {
        'type'              : 'jsonrpcprovider',
        'namespace'         : 'MShop.API',
        'url'               : MShop.config.smd.target,
        'useNamedParams'    : true
    }));
    
	// init schemas
	MShop.Schema.register(MShop.config.itemschema, MShop.config.searchschema);
    
	//init config and translations
	MShop.Config.init(MShop.config.configuration);
	MShop.I18n.init(MShop.i18n);
    
    MShop.urlManager = new MShop.UrlManager( window.location.href );
    
    // build interface
    new Ext.Viewport({
        layout: 'fit',
        items: [{
            layout: 'fit',
            border: false,
            tbar: ['->', {xtype: 'MShop.elements.site.combo'}],
            items: [{
                xtype: 'tabpanel',
                border: false,
                activeTab: MShop.urlManager.getActiveTab(),
                id: 'MShop.MainTabPanel',
                itemId: 'MShop.MainTabPanel',
                plugins: ['ux.itemregistry']
            }]
        }]
    });
});
