
{extends file='catalog/listing/product-list.tpl'}

{block name='product_list_header'}
    {include file='catalog/_partials/category-header.tpl' listing=$listing category=$category}
{/block}

{block name='product_list_footer'}
    {include file='catalog/_partials/category-footer.tpl' listing=$listing category=$category}
{/block}
