{extends file=$layout}

{block name='head_microdata_special'}
  {include file='_partials/microdata/product-list-jsonld.tpl' listing=$listing}
{/block}

{block name='content'}
  <section id="main">

    {block name='product_list_header'}
      <h1 id="js-product-list-header" class="h2">{$listing.label}</h1>
    {/block}

    {block name='subcategory_list'}
      {if isset($subcategories) && $subcategories|@count > 0}
        {include file='catalog/_partials/subcategories.tpl' subcategories=$subcategories}
      {/if}
    {/block}
    
    {hook h="displayHeaderCategory"}

    <section id="products">
      {if $listing.products|count}

        {block name='product_list_top'}
          {include file='catalog/_partials/products-top.tpl' listing=$listing}
        {/block}

        {block name='product_list_active_filters'}
          <div class="hidden-sm-down">
            {$listing.rendered_active_filters nofilter}
          </div>
        {/block}

        {block name='product_list'}
          {include file='catalog/_partials/products.tpl' listing=$listing productClass="col-sm-12 col-md-4 col-xl-3"}
        {/block}

        {block name='product_list_bottom'}
          {include file='catalog/_partials/products-bottom.tpl' listing=$listing}
        {/block}

      {else}
        <div id="js-product-list-top"></div>

        <div id="js-product-list">
          {capture assign="errorContent"}
            <h4>{l s='No products available yet' d='Shop.Theme.Catalog'}</h4>
            <p>{l s='Stay tuned! More products will be shown here as they are added.' d='Shop.Theme.Catalog'}</p>
          {/capture}

          {include file='errors/not-found.tpl' errorContent=$errorContent}
        </div>

        <div id="js-product-list-bottom"></div>
      {/if}
    </section>

    {block name='product_list_footer'}{/block}

    {hook h="displayFooterCategory"}

  </section>
{/block}
