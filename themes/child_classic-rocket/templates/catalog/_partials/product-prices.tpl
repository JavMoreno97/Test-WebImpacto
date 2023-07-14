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
 {if $product.show_price}
  <div class="product-prices">


    {block name='product_price'}
      <div class="product__product-price product-price {if $product.has_discount}has-discount{/if}">
        <div class="current-price">
          <span class="current-price-display price{if $product.has_discount} current-price-discount{/if}">
            {assign var="currencyPosition" value=$currency.sign|explode:$product.price}
            {if !$product.price|strstr:"."}
              {assign var="separator" value=','}
            {elseif !$product.price|strstr:","}
              {assign var="separator" value='.'}
            {else}
              {assign var="priceSplit" value=','|explode:$product.price}
              {if $priceSplit|count > 2 || $priceSplit[1]|strstr:"."}
                {assign var="separator" value='.'}
              {else}
                {assign var="separator" value=','}
              {/if}
            {/if}
            {assign var="separatedPrice" value='.'|explode:$product.price_amount}
            {if empty($currencyPosition[0])}<span class="currency-sign">{$currency.sign}</span>{/if}
            <span class="baseprice">{$separatedPrice[0]}</span>
            <span class="cents">{$separator}{$separatedPrice[1]}</span>
            {if empty($currencyPosition[1])}<span class="currency-sign">{$currency.sign}</span>{/if}
          </span>
        </div>
        
        {block name='product_without_taxes'}
          <div class="price-without-taxes-block secondary-price">
                <span class="price-without-taxes-label price-label">{l s='Tax excl.' d='Shop.Theme.Catalog'}</span>
                <span class="price-without-taxes">{Context::getContext()->currentLocale->formatPrice($product.price_tax_exc, $currency.iso_code)}</span>
          </div>
        {/block}

        {block name='product_discount'}
          {if $product.has_discount}
            <div class="discount-price-block">
              <span class="discount-price-label price-label">{l s='DISC' d='Shop.Theme.Catalog'}.</span>
              {if $product.discount_type === 'percentage'}
                <span class="discount discount-percentage">{$product.discount_percentage_absolute}</span>
              {else}
                <span class="discount discount-amount">
                    {$product.discount_to_display}
                </span>
              {/if}
            </div>
            <span class="product-discount secondary-price">
                {hook h='displayProductPriceBlock' product=$product type="old_price"}
                <span class="regular-price-label price-label">{l s='PVP' d='Shop.Theme.Catalog'}</span>
                <span class="regular-price">{$product.regular_price}</span>
            </span>
          {/if}
        {/block}

        {* {block name='product_unit_price'}
          {if $displayUnitPrice}
              <p class="product-unit-price sub">{l s='(%unit_price%)' d='Shop.Theme.Catalog' sprintf=['%unit_price%' => $product.unit_price_full]}</p>
          {/if}
        {/block} *}
      </div>
    {/block}

    {block name='product_pack_price'}
      {if $displayPackPrice}
        <p class="product-pack-price"><span>{l s='Instead of %price%' d='Shop.Theme.Catalog' sprintf=['%price%' => $noPackPrice]}</span></p>
      {/if}
    {/block}

    {block name='product_ecotax'}
      {if $product.ecotax.amount > 0}
          <p class="price-ecotax">{l s='Including %amount% for ecotax' d='Shop.Theme.Catalog' sprintf=['%amount%' => $product.ecotax.value]}
          {if $product.has_discount}
            {l s='(not impacted by the discount)' d='Shop.Theme.Catalog'}
          {/if}
        </p>
      {/if}
    {/block}

    {hook h='displayProductPriceBlock' product=$product type="weight" hook_origin='product_sheet'}
  </div>
{/if}
