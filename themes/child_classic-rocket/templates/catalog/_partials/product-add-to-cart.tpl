{**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
 <div class="product-add-to-cart">
 {if !$configuration.is_catalog}

   {block name='product_quantity'}
     <div class="product-quantity row flex-column no-gutters">
      <div class="qty col-auto m-0 my-2">
        <label for="quantity_wanted" class="quantity__label col-xs-12 col-sm-3 px-0"><b>{l s='Quantity' d='Shop.Theme.Catalog'}: </b></label>
        <input
          type="number"
          name="qty"
          id="quantity_wanted"
          value="{$product.quantity_wanted}"
          class="input-group col-xs-12 col-sm-9"
          min="{$product.minimal_quantity}"
          aria-label="{l s='Quantity' d='Shop.Theme.Actions'}"
          {if isset($product.product_url)}data-update-url="{$product.product_url}"{/if}
        >
       </div>

        {block name='product_variants'}
          {include file='catalog/_partials/product-variants.tpl'}
        {/block}

        {block name='product_availability'}
          <div class="row availability-block m-0">
            <div class="col-xs-12 col-sm-3 px-0">
              <b>{l s='Availability' d='Shop.Theme.Catalog'}</b>
            </div>
            <div class="col-xs-12 col-sm-9 px-0">
              <span id="product-availability" class="{if $product.availability == 'available'}success{/if}">
                {if $product.show_availability && $product.availability_message}
                  <b>{$product.availability_message}</b>
                  {* {if $product.availability == 'available'}
                    <i class="material-icons rtl-no-flip product-available text-success">&#xf105;</i>
                  {elseif $product.availability == 'last_remaining_items'}
                    <i class="material-icons product-last-itemstext-warning">&#xE002;</i>
                  {else}
                    <i class="material-icons product-unavailable text-danger">&#xE14B;</i>
                  {/if} *}
                {/if}
              </span>
            </div>
          </div>
        {/block}   

       <div class="add col-auto">
          <button
            class="btn btn-primary add-to-cart btn-lg btn-block btn-add-to-cart js-add-to-cart d-flex align-items-center justify-content-center"
            data-button-action="add-to-cart"
            type="submit"
            {if !$product.add_to_cart_url}
              disabled
            {/if}
          >
          <div class="cart-animation"> 
            <i class="material-icons shopping_cart btn-add-to-cart__icon">shopping_cart</i>
            <i class="material-icons animated-arrow">arrow_downward</i>
          </div>
          {l s='Add to cart' d='Shop.Theme.Actions'}
         </button>
       </div>
         {hook h='displayProductActions' product=$product}
     </div>
   {/block}

   {block name='product_minimal_quantity'}
     <p class="product-minimal-quantity">
       {if $product.minimal_quantity > 1}
         {l
         s='The minimum purchase order quantity for the product is %quantity%.'
         d='Shop.Theme.Checkout'
         sprintf=['%quantity%' => $product.minimal_quantity]
         }
       {/if}
     </p>
   {/block}
 {/if}
</div>
