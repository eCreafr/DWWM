
{if !empty($subcategories)}
  {if (isset($display_subcategories) && $display_subcategories eq 1) || !isset($display_subcategories) }
    <div id="subcategories" class="card card-block">
     <!-- <h2 class="subcategory-heading">{l s='Subcategories' d='Shop.Theme.Category'}</h2> -->

      <ul class="subcategories-list">
        {foreach from=$subcategories item=subcategory}
          <li>
            <div class="subcategory-image">
              <a href="{$subcategory.url}" title="{$subcategory.name|escape:'html':'UTF-8'}" class="img">
                {if !empty($subcategory.image.large.url)}
                  <picture>
                    {if !empty($subcategory.image.large.sources.avif)}<source srcset="{$subcategory.image.large.sources.avif}" type="image/avif">{/if}
                    {if !empty($subcategory.image.large.sources.webp)}<source srcset="{$subcategory.image.large.sources.webp}" type="image/webp">{/if}
                    <img
                      class="img-fluid"
                      src="{$subcategory.image.large.url}"
                      alt="{$subcategory.name|escape:'html':'UTF-8'}"
                      loading="lazy"
                      width="{$subcategory.image.large.width}"
                      height="{$subcategory.image.large.height}"/>
                  </picture>
                {/if}
              </a>
            </div>

            <h5>
              <a class="subcategory-name" href="{$subcategory.url}">
                {$subcategory.name|truncate:25:'...'|escape:'html':'UTF-8'}
              </a>
            </h5>
            {if $subcategory.description}
              <div class="cat_desc">{$subcategory.description|unescape:'html' nofilter}</div>
            {/if}
          </li>
        {/foreach}
      </ul>
    </div>
  {/if}
{/if}
