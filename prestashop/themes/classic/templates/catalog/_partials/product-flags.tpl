
{block name='product_flags'}
    <ul class="product-flags js-product-flags">
        {foreach from=$product.flags item=flag}
            <li class="product-flag {$flag.type}">{$flag.label}</li>
        {/foreach}
    </ul>
{/block}
