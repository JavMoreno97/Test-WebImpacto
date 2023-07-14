{extends file=$layout}

{block name='content'}
 <section id="main">
    <div class="row mb-5">
      <div class="col-lg-6">
        {block name='page_content_container'}
          <section class="page-content--product" id="content">
            {block name='page_content'}
              {block name='product_flags'}
                  {include file='catalog/_partials/product-flags.tpl'}
              {/block}

              {block name='product_cover_thumbnails'}
                {include file='catalog/_partials/product-cover-thumbnails.tpl'}
              {/block}


            {/block}
          </section>
        {/block}
        </div>
        <div class="col-lg-6 py-4">
          {block name='page_header_container'}
            {block name='page_header'}
              <h1 class="h1 product-title text-left">{block name='page_title'}<strong>{$product.name}</strong>{/block}</h1>
            {/block}
          {/block}
          {block name='product_prices'}
            {include file='catalog/_partials/product-prices.tpl'}
          {/block}

          {hook h='displayProductAdditionalInfo' product=$product mod="productcomments"}

          <div class="product-information">
            {* {block name='product_description_short'}
              <div id="product-description-short-{$product.id}">{$product.description_short nofilter}</div>
            {/block} *}

            {* {if $product.specific_references == null} *}
              {assign var="partNumber" value=$product.mpn}
            {* {else}
              {assign var="partNumber" value=$product.specific_references.mpn}
            {/if} *}
            <div class="row brand-block mx-0 mb-2">
              <div class="col-xs-12 col-sm-3 px-0">
                <b>{l s='Brand' d='Shop.Theme.Catalog'}:</b>
              </div>
              <div class="col-xs-12 col-sm-8 px-0">
                <a href="{$product_brand_url}">{$product_manufacturer->name}</a> - 
                {if !empty($partNumber)}{l s='P/N: %mpn%' d='Shop.Theme.Catalog' sprintf=['%mpn%' => $partNumber]} |{/if}
                {l s='Product Code: %reference%' d='Shop.Theme.Catalog' sprintf=['%reference%' => $product.reference]}
              </div>
            </div>

            {if $product.is_customizable && count($product.customizations.fields)}
              {block name='product_customization'}
                {include file="catalog/_partials/product-customization.tpl" customizations=$product.customizations}
              {/block}
            {/if}

            <div class="product-actions">
              {block name='product_buy'}
                <form action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh">
                  <input type="hidden" name="token" value="{$static_token}">
                  <input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
                  <input type="hidden" name="id_customization" value="{$product.id_customization}" id="product_customization_id">
{* 
                  {block name='product_variants'}
                    {include file='catalog/_partials/product-variants.tpl'}
                  {/block} *}

                  {block name='product_pack'}
                    {if $packItems}
                      <section class="product-pack mb-4">
                        <p class="h4">{l s='This pack contains' d='Shop.Theme.Catalog'}</p>
                        {foreach from=$packItems item="product_pack"}
                          {block name='product_miniature'}
                            {include file='catalog/_partials/miniatures/pack-product.tpl' product=$product_pack}
                          {/block}
                        {/foreach}
                    </section>
                    {/if}
                  {/block}

                  {block name='product_discounts'}
                    {include file='catalog/_partials/product-discounts.tpl'}
                  {/block}

                  {block name='product_add_to_cart'}
                    {include file='catalog/_partials/product-add-to-cart.tpl'}
                  {/block}

                  {block name='product_additional_info'}
                    {include file='catalog/_partials/product-additional-info.tpl'}
                  {/block}

                  {block name='product_refresh'}
                      {if !isset($product.product_url)}
                      <input class="product-refresh ps-hidden-by-js" name="refresh" type="submit" value="{l s='Refresh' d='Shop.Theme.Actions'}">
                      {/if}
                  {/block}
                </form>
              {/block}

            </div>

            {block name='hook_display_reassurance'}
              {hook h='displayReassurance'}
            {/block}
          </div>
      </div>
    </div>

    {block name='product_accessories'}
      {if $accessories}
        <section class="product-accessories pt-5">
          <p class="products-section-title text-left"><b>{l s='You might also like' d='Shop.Theme.Catalog'}</b></p>
          <hr>
          <div class="products mt-5">
            {foreach from=$accessories item="product_accessory"}
              {block name='product_miniature'}
                {include file='catalog/_partials/miniatures/product-accesory.tpl' product=$product_accessory}
              {/block}
            {/foreach}
          </div>
        </section>
      {/if}
    {/block}

    {block name='product_tabs'}
      {include file='catalog/_partials/product-tabs.tpl'}
    {/block}

    {block name='product_footer'}
      {hook h='displayFooterProduct' product=$product category=$category}
    {/block}

    {block name='product_images_modal'}
      {include file='catalog/_partials/product-images-modal.tpl'}
    {/block}

    {block name='page_footer_container'}
      <footer class="page-footer">{block name='page_footer'}{/block}</footer>
    {/block}
  </section>

{/block}