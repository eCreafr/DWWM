<div id="js-product-list-footer">
    {if isset($category) && $category.additional_description && $listing.pagination.items_shown_from == 1}
        <div class="card">
            <div class="card-block category-additional-description">
                {$category.additional_description nofilter}
            </div>
        </div>
    {/if}
</div>
