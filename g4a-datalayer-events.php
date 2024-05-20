<?php
/**
 * Plugin Name: G4A Datalayer Events
 * Description: Google Analytics 4 es un servicio de analíticas que te permite medir el tráfico y la interacción en tus sitios web y aplicaciones. Este plugin incluye los eventos principales de interaccion de los usuario con los productos, hasta el final de la compra.
 * Version: 1.0
 * Author: Fer Catalano
 * Author URI: https://www.linkedin.com/in/fernando-catalano-5394a35b/
 * 
 * Requires Plugins: woocommerce
 * Requires at least: 6.2
 **/

defined( 'ABSPATH' ) || exit;

class Datalayer {

    public function __construct() {
        add_action( 'wp_head', [ $this, 'datalayer_scripts_hook' ] );
    }

    public function datalayer_scripts_hook() {
        
        // Homepage
        if ( is_front_page() || is_page( 'homepage' ) ) : ?>

            <script type="text/javascript">
                document.addEventListener( 'DOMContentLoaded', function() {

                    let dataLayer   = window.dataLayer || [];
                    let module      = document.querySelector('#block-18');
                    let moduleName  = module ? module.querySelector('h2.wp-block-heading').innerHTML : "";
                    let products    = module ? module.querySelectorAll('.wp-block-button__link') : [];

                    // On Load Home
                    dataLayer.push({ ecommerce: null });
                    dataLayer.push({
                        event: "view_item_list",
                        ecommerce: {
                            currency: "EUR",
                            item_list_name: moduleName,
                            value: null,
                            items: getIdsRecursive(products, moduleName)
                        }
                    });

                    // On click Item
                    products.forEach(product => {
                        product.addEventListener('click', function() {
                            dataLayer.push({ ecommerce: null });
                            dataLayer.push({
                                event: "select_item",
                                ecommerce: {
                                    currency: "EUR",
                                    item_list_name: moduleName,
                                    value: null,
                                    items: getIdsRecursive([product], moduleName)
                                }
                            });
                        });
                    });

                    let module2      = document.querySelector('#custom_html-11');
                    let moduleName2  = module2 ? module2.querySelector('h2').innerHTML : "";
                    let products2    = module2 ? module2.querySelectorAll('.wp-block-button__link') : [];

                    // On Load Home 2
                    dataLayer.push({ ecommerce: null });
                    dataLayer.push({
                        event: "view_item_list",
                        ecommerce: {
                            currency: "EUR",
                            item_list_name: moduleName2,
                            value: null,
                            items: getIdsRecursive(products2, moduleName2)
                        }
                    });

                    // On click Item
                    products2.forEach(product => {
                        product.addEventListener('click', function() {
                            dataLayer.push({ ecommerce: null });
                            dataLayer.push({
                                event: "select_item",
                                ecommerce: {
                                    currency: "EUR",
                                    item_list_name: moduleName2,
                                    value: null,
                                    items: getIdsRecursive([product], moduleName2)
                                }
                            });
                        });
                    });
                    

                    // Push into DataLayer Items
                    function getIdsRecursive(products, moduleName, items = []) {
                        if(products.length > 0){
                            for (let product of products) {
                                items.push( {
                                    item_id: null,
                                    item_sku: null,
                                    item_name: product.innerHTML,
                                    currency: "EUR",
                                    index: jQuery(product).index(),
                                    item_brand: "noddum",
                                    item_category: product.innerHTML,
                                    item_list_name: moduleName,
                                    price: null,
                                    item_variant: null,
                                    quantity: 1
                                });
                            }
                            return items;
                        }
                    }
                    
                });
            </script>

        <?php endif;

        // Product Page
        if ( is_singular( 'product' ) ) : ?>

            <?php $product = wc_get_product( get_the_ID() ); ?>

            <script type="text/javascript">
                document.addEventListener( 'DOMContentLoaded', function() {

                    let dataLayer   = window.dataLayer || [];
                    let product     = document.querySelector('.product');
                    let itemId      = <?= $product->get_id() ?>;
                    let itemSku     = <?= '"' . $product->get_sku()  . '"'; ?>;
                    let itemName    = <?= '"' . $product->get_name()  . '"'; ?>;
                    let price = <?= $product->get_price(); ?>;
                    let cats = product.classList;
                    let item_category = [];
                    
                    for (let i = 0; i < cats.length; i++) {
                        let cat = cats[i];
                        if (cat.startsWith('product_cat-')) {
                            item_category.push(cat.replace('product_cat-', ''));
                        }
                    }

                    // View Product
                    dataLayer.push({ ecommerce: null });
                    dataLayer.push({
                        event: "view_item",
                        ecommerce: {
                            currency: "EUR",
                            value: price,
                            items: [{
                                item_id: itemId,
                                item_sku: itemSku,
                                item_name: itemName,
                                currency: "EUR",
                                item_brand: "noddum",
                                item_category: item_category[0],
                                item_category2: item_category[1],
                                item_category3: item_category[2],
                                item_category4: item_category[3],
                                item_category5: item_category[4],
                                index: 0,
                                item_list_id: item_category[0].replace('-',' ') + ' ' + item_category[1],
                                item_list_name: item_category[0].replace('-',' ') + ' ' + item_category[1],
                                item_variant: null,
                                price: price,
                                quantity: 1
                            }]
                        }
                    });

                    // On add to cart
                    var btnAddToCart = document.querySelector(".single_add_to_cart_button");
                    btnAddToCart.addEventListener("click", function(e) {

                        let product     = document.querySelector('.product');
                        let itemId      = <?= $product->get_id() ?>;
                        let itemSku     = <?= '"' . $product->get_sku()  . '"'; ?>;
                        let itemName    = <?= '"' . $product->get_name()  . '"'; ?>;
                        let price = parseFloat(document.querySelector(".price").firstChild.innerText.replace('€', '').replace(',', ''));
                        let input = document.querySelector("input[name='quantity']");
                        let woodType1 = document.getElementById("uni_cpo_madera_kaffi-field");
                        let woodType2 = document.getElementById("uni_cpo_madera_plain-field");
                        let woodType3 = document.getElementById("uni_cpo_madera_roda-field");
                        let woodType4 = document.getElementById("uni_cpo_madera_berri-field");
                        let woodType5 = document.getElementById("uni_cpo_madera_duarte-field");
                        let woodType6 = document.getElementById("uni_cpo_madera_banco_pura-field");
                        let woodType7 = document.getElementById("uni_cpo_madera_banco_indie-field");
                        let woodType8 = document.getElementById("uni_cpo_madera_hera-field");
                        let woodType9 = document.getElementById("uni_cpo_madera_pedestal-field");
                        let woodType10 = document.getElementById("uni_cpo_madera_teodoro_2-field");
                        let woodType11 = document.getElementById("uni_cpo_madera_cm-field");
                        let woodType12 = document.getElementById("uni_cpo_madera_estanteria_indie-field");
                        let hight1    = document.getElementById("uni_cpo_alto_kaffi-field");
                        let hight2   = document.getElementById("uni_cpo_alto_roda-field");
                        let hight3   = document.getElementById("uni_cpo_alto_espejo-field");
                        let texture  = document.getElementById("uni_cpo_textura-field");
                        let finish   = document.getElementById("uni_cpo_anclaje-field");
                        let finish2  = document.getElementById("uni_cpo_acabado_base-field");
                        let finish3 = document.getElementById("uni_cpo_acabado_metal_lu-field");
                        let finish4 = document.getElementById("uni_cpo_metal-field");
                        let finish5 = document.getElementById("uni_cpo_acabado_patas_cm-field");
                        let fixation = document.getElementById("uni_cpo_anclaje_balda-field");
                        let background = document.getElementById("uni_cpo_fondo-field");
                        let long    = document.getElementById("uni_cpo_largo-field");
                        let width   = document.getElementById("uni_cpo_ancho-field");
                        let textureMirror = document.getElementById("uni_cpo_textura_espejo-field");
                        let woodThickness = document.getElementById("uni_cpo_grosor_madera_balda-field");
                        let woodEdge = document.getElementById("uni_cpo_canto_muebletv_waterfall-field");
                        let woodEdge2 = document.getElementById("uni_cpo_canto-field");
                        let diameter = document.getElementById("uni_cpo_diametro_cross_redonda-field");
                        let shelves = document.getElementById("uni_cpo_baldas-field");
                        let shelvesType = document.getElementById("uni_cpo_baldas_material-field");

                        if (input.value > 0) {
                            dataLayer.push({
                                ecommerce: null
                            });
                            dataLayer.push({
                                event: "add_to_cart",
                                ecommerce: {
                                    currency: "EUR",
                                    item_list_name: null,
                                    value: price,
                                    items: [{
                                        index: 0,
                                        item_id: itemId,
                                        item_sku: itemSku,
                                        item_name: itemName,
                                        currency: "EUR",
                                        item_brand: "noddum",
                                        item_category:  item_category[0],
                                        item_category2: item_category[1],
                                        item_category3: item_category[2],
                                        item_category4: item_category[3],
                                        item_category5: item_category[4],
                                        item_list_id:   item_category[0].replace('-',' ') + ' ' + item_category[1],
                                        item_list_name: item_category[0].replace('-',' ') + ' ' + item_category[1],
                                        grosor: "",
                                        baldas: shelves ? shelves.value : null,
                                        baldas_material: shelvesType ? shelvesType.value : null,
                                        fijacion: fixation ? fixation.value : null,
                                        alto_consola: hight3 ? hight3.value : null, 
                                        numero_baldas: "",
                                        ancho: "",
                                        diametro: diameter ? diameter.value : null,
                                        grosor_madera: woodThickness ? woodThickness.value : null,
                                        canto: woodEdge ? woodEdge.value : woodEdge2 ? woodEdge2.value : null,
                                        alto: hight1 ? hight1.value : hight2 ? hight2.value : null,
                                        fondo: background ? background.value : null,
                                        largo: long ? long.value : null,
                                        textura: texture ? texture.value : null,
                                        tipo_pared: finish ? finish.value : null,
                                        tipo_madera: woodType1 ? woodType1.value : woodType2 ? woodType2.value : woodType3 ? woodType3.value : woodType4 ? woodType4.value : woodType5 ? woodType5.value : woodType6 ? woodType6.value : woodType7 ? woodType7.value : woodType8 ? woodType8.value : woodType9 ? woodType9.value : woodType10 ? woodType10.value : woodType11 ? woodType11.value : woodType12 ? woodType12.value : null,
                                        acabado_madera: textureMirror ? textureMirror.value : null,
                                        lado: "",
                                        acabado_base: finish ? finish.value : finish2 ? finish2.value : null,
                                        acabado_patas: finish2 ? finish2.value : finish5 ? finish5.value : null, 
                                        acabado_metal: finish3 ? finish3.value : finish4 ? finish4.value : null,
                                        item_variant: null,
                                        price: price,
                                        quantity: parseInt(input.value),
                                    }]
                                }
                            });
                        }
                    });
                });
            </script>

        <?php endif;

        // Shop Page
        if ( is_shop() || is_product_category() || is_archive() ) : ?>

            <script type="text/javascript">
                document.addEventListener( 'DOMContentLoaded', function() {

                    let dataLayer   = window.dataLayer || [];
                    let moduleName  = document.querySelector('h1.archive-title') ? document.querySelector('h1.archive-title').innerText : "";
                    let products    = document.querySelectorAll('.product');

                    // On Load Home
                    dataLayer.push({ ecommerce: null });
                    dataLayer.push({
                        event: "view_item_list",
                        ecommerce: {
                            item_list_id: moduleName,
                            item_list_name: moduleName,
                            currency: "EUR",
                            items: getIdsRecursive(products)
                        }
                    });

                    // On click Item
                    products.forEach(product => {
                        product.addEventListener('click', function() {
                            dataLayer.push({ ecommerce: null });
                            dataLayer.push({
                                event: "select_item",
                                ecommerce: {
                                    item_list_id: moduleName,
                                    item_list_name: moduleName,
                                    currency: "EUR",
                                    items: getIdsRecursive([product])
                                }
                            });
                        });
                    });

                    // Push into DataLayer Items
                    function getIdsRecursive(products, items = []) {    
                        for (let product of products) {

                            let cats = product.classList;
                            let item_category = [];
                            for (let i = 0; i < cats.length; i++) {
                                let cat = cats[i];
                                if (cat.startsWith('product_cat-')) {
                                    item_category.push(cat.replace('product_cat-', ''));
                                }
                            }

                            items.push( {
                                item_id: parseFloat(product.querySelector('a.button').getAttribute('data-product_id')),
                                item_sku: product.querySelector('a.button').getAttribute('data-product_sku'),
                                index: jQuery(product).index(),
                                item_name: product.querySelector('h2.woocommerce-loop-product__title').innerHTML,
                                item_brand: "noddum",
                                item_category: item_category[0],
                                item_category2: item_category[1],
                                item_category3: item_category[2],
                                item_category4: item_category[3],
                                item_category5: item_category[4],
                                item_variant: null,
                                price: parseFloat(product.querySelector('.price').innerText.replace('€', '').replace(',', '')),
                                quantity: 1
                            });
                        }
                        return items;
                    }
                });
            </script>

        <?php endif;

        // Cart
        if ( is_cart() ) : ?>
        
            <?php if ( !WC()->cart->is_empty() ) { ?>
            
                <script type="text/javascript">
                    document.addEventListener( 'DOMContentLoaded', function() {

                        let dataLayer = window.dataLayer || [];
                        let products = document.querySelectorAll('.cart_item');

                         /* Temp data to pass to next datalayer products */
                         <?php foreach ( WC()->cart->get_cart() as $key => $cart_item ) : 
                            $products[]  = [ 
                                'product_id' => $cart_item['product_id'],
                                'product_name' => $cart_item['data']->get_title(),
                                'product_category' => explode(',', strip_tags($cart_item['data']->get_categories())),
                            ];
                        endforeach; ?>

                        let prod_list = <?php echo json_encode($products); ?>;

                        // view cart
                        dataLayer.push({ ecommerce: null });
                        dataLayer.push({
                            event: "view_cart",
                            ecommerce: {
                                currency: "EUR",
                                items: getIdsRecursive(products),
                                coupon:   <?= '"' . (!empty(WC()->cart->get_applied_coupons()) ? WC()->cart->get_applied_coupons() : null) . '"'; ?>,
                                discount: <?= (!empty(WC()->cart->get_discount_total()) ? WC()->cart->get_discount_total() : 0); ?>,
                                value:    <?= WC()->cart->total; ?>,
                            }
                        });

                        // on load cart
                        jQuery(document).ready(listenersQuantity());
                        // on update cart
                        jQuery(document).on('updated_wc_div', listenersQuantity());

                        function listenersQuantity() {
                            return function() {
                                // get items from cart
                                var itemsCart = document.querySelectorAll('.cart_item');
                                itemsCart.forEach(item => {
                                    let old_qty    = item.querySelector('.qty').value;
                                    let input_qty  = item.querySelector('.qty');
                                    if(input_qty) {
                                        input_qty.addEventListener( "change", function(e) {
                                            let subtotal = document.querySelector(".cart-subtotal bdi").innerText.replace('€', '').replace(',', '');
                                            let price  = item.querySelector('.product-subtotal .amount bdi').innerText.replace('€', '').replace(',', '');
                                            if (old_qty < input_qty.value) {
                                                // btn add to cart
                                                dataLayer.push({ ecommerce: null });
                                                dataLayer.push({
                                                    event: "add_to_cart",
                                                    ecommerce: {
                                                        currency: "EUR",
                                                        value: parseFloat(subtotal) + parseFloat(price),
                                                        items: getIdsRecursive([item])
                                                    }
                                                });
                                            } else {
                                                // btn rest to cart            
                                                dataLayer.push({ ecommerce: null });
                                                dataLayer.push({
                                                    event: "remove_from_cart",
                                                    ecommerce: {
                                                        currency: "EUR",
                                                        value: parseFloat(subtotal) - parseFloat(price),
                                                        items: getIdsRecursive([item])
                                                    }
                                                });
                                            }
                                        });
                                    }

                                    // on remove item from X
                                    let remove = item.querySelector('.remove');
                                    remove.addEventListener('click', function(e) {
                                        let subtotal = document.querySelector(".cart-subtotal bdi").innerText.replace('€', '').replace(',', '');
                                        let price  = item.querySelector('.product-subtotal .amount bdi').innerText.replace('€', '').replace(',', '');
                                        dataLayer.push({ ecommerce: null });
                                        dataLayer.push({
                                            event: "remove_from_cart",
                                            ecommerce: {
                                                currency: "EUR",
                                                value: parseFloat(subtotal) - parseFloat(price),
                                                items: getIdsRecursive([item]),
                                            }
                                        });
                                    });
                                });
                            }
                        }

                        // Push into DataLayer Items
                        function getIdsRecursive(products, items = []) {   
                            
                            let i = 0;
                            for (let product of products) {

                                let itemId = product.querySelector('.remove').getAttribute('data-product_id');
                                let itemName = product.querySelector('.product-name a');
                                let woodType = product.querySelector('dd.variation-Tipodemadera');
                                let long = product.querySelector('dd.variation-Largo');
                                let price = product.querySelector('.amount bdi');
                                let quantity = product.querySelector('.qty');
                                let background = product.querySelector('dd.variation-Fondo');
                                let grosor = product.querySelector('dd.variation-Grosor');
                                let texture = product.querySelector('dd.variation-Texturadelamadera');
                                let fijacion = product.querySelector('dd.variation-Sistemadefijacin');
                                let canto = product.querySelector('dd.variation-Canto');
                                let cantoM = product.querySelector('dd.variation-Cantodelamesa');
                                let hight = product.querySelector('dd.variation-Alto');
                                let ancho = product.querySelector('dd.variation-Ancho');
                                let diameter = product.querySelector('dd.variation-Dimetro');
                                let finishBase = product.querySelector('dd.variation-Acabadodelabase');
                                let consoleHight = product.querySelector('dd.variation-Altodelaconsola');
                                let blandas = product.querySelector('dd.variation-Nmerodebaldas');
                                let item_category = prod_list[i]['product_category'][0];
                                let item_category2 = prod_list[i]['product_category'][1];
                                let item_category3 = prod_list[i]['product_category'][2];
                                let item_category4 = prod_list[i]['product_category'][3];
                                let item_category5 = prod_list[i]['product_category'][4];

                                items.push( {
                                    index: i++,
                                    item_id: itemId,
                                    item_sku: null,
                                    item_name: itemName.innerText,
                                    currency: "EUR",
                                    item_brand: "noddum",
                                    item_category:  item_category,
                                    item_category2: item_category2,
                                    item_category3: item_category3,
                                    item_category4: item_category4,
                                    item_category5: item_category5,
                                    item_list_id:   item_category + ' ' + item_category2,
                                    item_list_name: item_category + ' ' + item_category2,
                                    grosor: grosor ? grosor.innerText : null,
                                    baldas: blandas ? blandas.innerText : null,
                                    baldas_material: texture ? texture.innerText : null,
                                    fijacion: fijacion ? fijacion.innerText : null,
                                    alto_consola: consoleHight ? consoleHight.innerText : null,
                                    numero_baldas: "",
                                    ancho: ancho ? ancho.innerText : null,
                                    diametro: diameter ? diameter.innerText : null,
                                    grosor_madera: "",
                                    canto: canto ? canto.innerText : cantoM ? cantoM.innerText : null,
                                    alto: hight ? hight.innerText : null,
                                    fondo: background ? background.innerText : null,
                                    largo: long ? long.innerText : null,
                                    textura: texture ? texture.innerText : null,
                                    tipo_pared: "",
                                    tipo_madera: woodType ? woodType.innerText : null,
                                    acabado_madera: "",
                                    lado: "",
                                    acabado_base: finishBase ? finishBase.innerText : null,
                                    acabado_patas: "",
                                    acabado_metal: "",
                                    item_variant: null,
                                    price: parseFloat(price.innerText.replace('€','').replace(',','').trim()),
                                    quantity: parseInt(quantity.value),
                                });
                            }
                            return items;
                        }

                    });
                </script>
            
            <?php } ?>

        <?php endif;

        // Begin Checkout
		if ( is_checkout() && !is_wc_endpoint_url( 'order-received' ) ) : ?>
			
			<?php if ( !WC()->cart->is_empty() ) { ?>

				<script type="text/javascript">

					document.addEventListener( 'DOMContentLoaded', function() {

                        let dataLayer   = window.dataLayer || [];
                        let products    = document.querySelectorAll('.cart_item');
                        let subtotal    = document.querySelector(".cart-subtotal bdi").innerText.replace('€', '').replace(',', '');

                        /* Temp data to pass to next datalayer products */
                        <?php foreach ( WC()->cart->get_cart() as $key => $cart_item ) : 
                            $products[]  = [ 
                                'product_id' => $cart_item['product_id'],
                                'product_name' => $cart_item['data']->get_title(),
                                'product_category' => explode(',', strip_tags($cart_item['data']->get_categories())),
                            ];
                        endforeach; ?>

                        let prod_list = <?php echo json_encode($products); ?>;

                        // view checkout
						dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
						dataLayer.push({
							event: "begin_checkout",
							ecommerce: {
						    	items: getIdsRecursive(products),
                                shipping: 0,
                                currency: "EUR",
                                coupon:   <?= '"' . (!empty(WC()->cart->get_applied_coupons()) ? WC()->cart->get_applied_coupons() : null) . '"'; ?>,
                                discount: <?= (!empty(WC()->cart->get_discount_total()) ? WC()->cart->get_discount_total() : 0); ?>,
                                value:    parseFloat(subtotal),
							}
						});

                        // view checkout
						dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
						dataLayer.push({
							event: "add_shipping_info",
							ecommerce: {
						    	items: getIdsRecursive(products),
                                shipping: 0,
                                currency: "EUR",
                                coupon:   <?= '"' . (!empty(WC()->cart->get_applied_coupons()) ? WC()->cart->get_applied_coupons() : null) . '"'; ?>,
                                discount: <?= (!empty(WC()->cart->get_discount_total()) ? WC()->cart->get_discount_total() : 0); ?>,
                                value:    parseFloat(subtotal),
							}
						});

                        // view checkout
						dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
						dataLayer.push({
							event: "add_payment_info",
							ecommerce: {
						    	items: getIdsRecursive(products),
                                shipping: 0,
                                currency: "EUR",
                                coupon:   <?= '"' . (!empty(WC()->cart->get_applied_coupons()) ? WC()->cart->get_applied_coupons() : null) . '"'; ?>,
                                discount: <?= (!empty(WC()->cart->get_discount_total()) ? WC()->cart->get_discount_total() : 0); ?>,
                                value:    parseFloat(subtotal),
							}
						});

                        // Push into DataLayer Items
                        function getIdsRecursive(products, items = []) {    
                            let i = 0;
                            for (let product of products) {

                                let itemId = prod_list[i]['product_id'];
                                let itemName = prod_list[i]['product_name'];
                                let woodType = product.querySelector('dd.variation-Tipodemadera');
                                let long = product.querySelector('dd.variation-Largo');
                                let price = product.querySelector('.amount bdi');
                                let quantity = product.querySelector('.product-quantity');
                                let background = product.querySelector('dd.variation-Fondo');
                                let grosor = product.querySelector('dd.variation-Grosor');
                                let texture = product.querySelector('dd.variation-Texturadelamadera');
                                let fijacion = product.querySelector('dd.variation-Sistemadefijacin');
                                let canto = product.querySelector('dd.variation-Canto');
                                let cantoM = product.querySelector('dd.variation-Cantodelamesa');
                                let hight = product.querySelector('dd.variation-Alto');
                                let ancho = product.querySelector('dd.variation-Ancho');
                                let diameter = product.querySelector('dd.variation-Dimetro');
                                let finishBase = product.querySelector('dd.variation-Acabadodelabase');
                                let consoleHight = product.querySelector('dd.variation-Altodelaconsola');
                                let blandas = product.querySelector('dd.variation-Nmerodebaldas');
                                let item_category = prod_list[i]['product_category'][0];
                                let item_category2 = prod_list[i]['product_category'][1];
                                let item_category3 = prod_list[i]['product_category'][2];
                                let item_category4 = prod_list[i]['product_category'][3];
                                let item_category5 = prod_list[i]['product_category'][4];

                                items.push( {
                                    index: i++,
                                    item_id: itemId,
                                    item_sku: null,
                                    item_name: itemName,
                                    currency: "EUR",
                                    item_brand: "noddum",
                                    item_category:  item_category,
                                    item_category2: item_category2,
                                    item_category3: item_category3,
                                    item_category4: item_category4,
                                    item_category5: item_category5,
                                    item_list_id:   item_category + ' ' + item_category2,
                                    item_list_name: item_category + ' ' + item_category2,
                                    grosor: grosor ? grosor.innerText : null,
                                    baldas: blandas ? blandas.innerText : null,
                                    baldas_material: texture ? texture.innerText : null,
                                    fijacion: fijacion ? fijacion.innerText : null,
                                    alto_consola: consoleHight ? consoleHight.innerText : null,
                                    numero_baldas: "",
                                    ancho: ancho ? ancho.innerText : null,
                                    diametro: diameter ? diameter.innerText : null,
                                    grosor_madera: "",
                                    canto: canto ? canto.innerText : cantoM ? cantoM.innerText : null,
                                    alto: hight ? hight.innerText : null,
                                    fondo: background ? background.innerText : null,
                                    largo: long ? long.innerText : null,
                                    textura: texture ? texture.innerText : null,
                                    tipo_pared: "",
                                    tipo_madera: woodType ? woodType.innerText : null,
                                    acabado_madera: "",
                                    lado: "",
                                    acabado_base: finishBase ? finishBase.innerText : null,
                                    acabado_patas: "",
                                    acabado_metal: "",
                                    item_variant: null,
                                    price: parseFloat(price.innerText.replace('€','').replace(',','').trim()),
                                    quantity: parseInt(quantity.innerHTML.replace('×', '').replace('&nbsp;', '')),
                                });
                            }
                            return items;
                        }
					});
				</script>

	        <?php }

        endif;

        // Purchase
        if ( is_checkout() && is_wc_endpoint_url( 'order-received' ) ) : 
            
            global $wp;
            $order_id = absint($wp->query_vars['order-received']); // The order ID
            $order    = wc_get_order( $order_id ); // The WC_Order object ?>
			
            <script type="text/javascript">

                document.addEventListener( 'DOMContentLoaded', function() {

                    let dataLayer   = window.dataLayer || [];
                    let products    = document.querySelectorAll('.order_item');

                    <?php 
                    foreach( $order->get_items() as $key => $item ) : 
                        $product = wc_get_product( $item->get_product_id() ); 
                        $products[]  = [ 
                            'product_id' => $product->get_id(),
                            'product_name' => $product->get_title(),
                            'product_category' => explode(',', strip_tags(wc_get_product_category_list($product->get_id()))),
                        ];
                    endforeach; ?>
                    
                    let prod_list = <?php echo json_encode($products); ?>;

                    // purchase
                    dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
                    dataLayer.push({
                        event: "purchase",
                        ecommerce: {
                            order_id: <?= $order_id; ?>,
                            transaction_id: <?= $order->get_order_number(); ?>,
                            currency: "EUR",
                            item_list_name: null,
                            value: <?= $order->get_total(); ?>,
                            coupon:   <?= '"' . (!empty($order->get_coupon_codes())    ? $order->get_coupon_codes()    : null) . '"'; ?>,
                            discount: <?= (!empty($order->get_discount_total())  ? $order->get_discount_total()  : 0); ?>,
                            shipping: <?= $order->get_shipping_total(); ?>,
                            tax:      <?= $order->get_total_tax(); ?>,
                            items: getIdsRecursive(products)
                        }
                    });

                    // enhanced_conversion
                    dataLayer.push({ ecommerce: null });  // Clear the previous ecommerce object.
                    dataLayer.push({
                        event: "enhanced_conversion",
                        ecommerce: {
                            order_id: <?= $order_id; ?>,
                            transaction_id: <?= $order->get_order_number(); ?>,
                            currency: "EUR",
                            item_list_name: null,
                            value: <?= $order->get_total(); ?>,
                            coupon: <?= '"' . (!empty($order->get_coupon_codes())    ? $order->get_coupon_codes()    : null) . '"'; ?>,
                            discount: <?= (!empty($order->get_discount_total())  ? $order->get_discount_total()  : 0); ?>,
                            shipping: <?= $order->get_shipping_total(); ?>,
                            tax:      <?= $order->get_total_tax(); ?>,
                            enhanced_conversion_data: {
                                email: <?= '"' . $order->get_billing_email() . '"'; ?>,
                                phone: <?= '"' . $order->get_billing_phone() . '"'; ?>
                            }
                        }
                    });

                    // Push into DataLayer Items
                    function getIdsRecursive(products, items = []) {    
                            let i = 0;
                            for (let product of products) {

                                let itemId = prod_list[i]['product_id'];
                                let itemName = prod_list[i]['product_name'];
                                let woodType = product.querySelector('dd.variation-Tipodemadera');
                                let long = product.querySelector('dd.variation-Largo');
                                let price = product.querySelector('.amount bdi');
                                let quantity = product.querySelector('.product-quantity');
                                let background = product.querySelector('dd.variation-Fondo');
                                let grosor = product.querySelector('dd.variation-Grosor');
                                let texture = product.querySelector('dd.variation-Texturadelamadera');
                                let fijacion = product.querySelector('dd.variation-Sistemadefijacin');
                                let canto = product.querySelector('dd.variation-Canto');
                                let cantoM = product.querySelector('dd.variation-Cantodelamesa');
                                let hight = product.querySelector('dd.variation-Alto');
                                let ancho = product.querySelector('dd.variation-Ancho');
                                let diameter = product.querySelector('dd.variation-Dimetro');
                                let finishBase = product.querySelector('dd.variation-Acabadodelabase');
                                let consoleHight = product.querySelector('dd.variation-Altodelaconsola');
                                let blandas = product.querySelector('dd.variation-Nmerodebaldas');
                                let item_category = prod_list[i]['product_category'][0];
                                let item_category2 = prod_list[i]['product_category'][1];
                                let item_category3 = prod_list[i]['product_category'][2];
                                let item_category4 = prod_list[i]['product_category'][3];
                                let item_category5 = prod_list[i]['product_category'][4];

                                items.push( {
                                    index: i++,
                                    item_id: itemId,
                                    item_sku: null,
                                    item_name: itemName,
                                    currency: "EUR",
                                    item_brand: "noddum",
                                    item_category:  item_category,
                                    item_category2: item_category2,
                                    item_category3: item_category3,
                                    item_category4: item_category4,
                                    item_category5: item_category5,
                                    item_list_id:   item_category + ' ' + item_category2,
                                    item_list_name: item_category + ' ' + item_category2,
                                    grosor: grosor ? grosor.innerText : null,
                                    baldas: blandas ? blandas.innerText : null,
                                    baldas_material: texture ? texture.innerText : null,
                                    fijacion: fijacion ? fijacion.innerText : null,
                                    alto_consola: consoleHight ? consoleHight.innerText : null,
                                    numero_baldas: "",
                                    ancho: ancho ? ancho.innerText : null,
                                    diametro: diameter ? diameter.innerText : null,
                                    grosor_madera: "",
                                    canto: canto ? canto.innerText : cantoM ? cantoM.innerText : null,
                                    alto: hight ? hight.innerText : null,
                                    fondo: background ? background.innerText : null,
                                    largo: long ? long.innerText : null,
                                    textura: texture ? texture.innerText : null,
                                    tipo_pared: "",
                                    tipo_madera: woodType ? woodType.innerText : null,
                                    acabado_madera: "",
                                    lado: "",
                                    acabado_base: finishBase ? finishBase.innerText : null,
                                    acabado_patas: "",
                                    acabado_metal: "",
                                    item_variant: null,
                                    price: parseFloat(price.innerText.replace('€','').replace(',','').trim()),
                                    quantity: parseInt(quantity.innerHTML.replace('×', '').replace('&nbsp;', '')),
                                });
                            }
                            return items;
                        }

                });

            </script>

        <?php endif;

    }
    
}


add_action( 'plugins_loaded', 'g4a_datalayer_events_init' );

function g4a_datalayer_events_init() {

	if ( ! class_exists( 'WooCommerce' ) ) {
        
		add_action( 'admin_notices', function () {
            $admin_notice_content = sprintf(
                esc_html__( '%1$sG4A Datalayer Events esta inactivo.%2$s El %3$sWooCommerce plugin%4$s debe estar activo para que este plugin funcione. Por favor instale y active WooCommerce &raquo;', 'g4a-datalayer-events' ),
                '<strong>',
                '</strong>',
                '<a href="http://wordpress.org/extend/plugins/woocommerce/">',
                '</a>'
            );
        
            echo '<div class="error">';
            echo '<p>' . wp_kses_post( $admin_notice_content ) . '</p>';
            echo '</div>';
        });

		return;
	}

    new Datalayer();
}
