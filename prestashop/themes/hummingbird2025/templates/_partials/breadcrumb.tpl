
{$componentName = 'breadcrumb'}

{if $breadcrumb.count <=2 }

{else}

<nav data-depth="{$breadcrumb.count}" class="{$componentName}__wrapper" aria-label="{$componentName}">
  <div class="container">
    <ol class="{$componentName}">
      {block name='breadcrumb'}
        {foreach from=$breadcrumb.links item=path name=breadcrumb}
          {block name='breadcrumb_item'}
            <li class="{$componentName}-item">
              {if not $smarty.foreach.breadcrumb.last}
                <a href="{$path.url}" class="{$componentName}-link"><span>{$path.title}</span></a>
              {else}
                <span>{$path.title}</span>
              {/if}
            </li>
          {/block}
        {/foreach}
      {/block}
    </ol>
  </div>
</nav>


{/if}