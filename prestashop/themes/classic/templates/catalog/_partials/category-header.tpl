<div class="col-12"><h1 class="h1 col-12 text-center">{$category.name}</h1></div>
<!-- <div id="js-product-list-header">
    {if $listing.pagination.items_shown_from == 1}
        <div class="block-category card card-block">
            
           <div class="block-category-inner">
                {if $category.description}
                    <div id="category-description" class="text-muted">{$category.description nofilter}</div>
                {/if}
                {if !empty($category.image.large.url)}
                    <div class="category-cover">
                        <picture>
                            {if !empty($category.image.large.sources.avif)}<source srcset="{$category.image.large.sources.avif}" type="image/avif">{/if}
                            {if !empty($category.image.large.sources.webp)}<source srcset="{$category.image.large.sources.webp}" type="image/webp">{/if}
                            <img src="{$category.image.large.url}" alt="{if !empty($category.image.legend)}{$category.image.legend}{else}{$category.name}{/if}" loading="lazy" width="141" height="180">
                        </picture>
                    </div>
                {/if}
            </div>
        </div>
    {/if}
</div> -->
