
<script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "ItemList",
    "itemListElement": [
    {foreach from=$listing.products item=item name=productsForJsonLd}
      {
        "@type": "ListItem",
        "position": {$smarty.foreach.productsForJsonLd.iteration},
        "name": "{$item.name}",
        "url": "{$item.url}"
      }{if !$smarty.foreach.productsForJsonLd.last},{/if}
    {/foreach}
    ]
  }
</script>
