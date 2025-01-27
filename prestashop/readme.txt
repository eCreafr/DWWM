 /////////////////////////////////////////////////////////////////////
1 : passer en full width la page product list
 /////////////////////////////////////////////////////////////////////

index appelle page, page appelle le layout , 
on pourrait modifier layout-both-column.tpl en vidant le contenu entre les balises left column, ça fonctionnerait, mais impacterait definitivement tout le site

donc "proprement" 
il faut aller override le controller de la section category et forcer le layout

(il existe aussi la 3eme solution du fichier yml fait pour faire de petite modification de configuration de ce genre en collant 
"
layout:
    category:
        display: full-width
        ")


 /////////////////////////////////////////////////////////////////////
2 : changer le nombre de produit par ligne
 /////////////////////////////////////////////////////////////////////

on va dans le template : catalog / listing / product-list.tpl 

et la on a comme un petit gout de bootstrap, en mettant col-xl-3 au lieu de 4 par exemple :

     {block name='product_list'}
          {include file='catalog/_partials/products.tpl' listing=$listing productClass="col-xs-12 col-sm-6 col-xl-3"}
        {/block}



 /////////////////////////////////////////////////////////////////////
 3 simplifier le header category et le subcategories
 /////////////////////////////////////////////////////////////////////



 /////////////////////////////////////////////////////////////////////
 4 faire un if else sur breadcrumb pour le masquer aux niveau 0 et 1 de profondeur
 /////////////////////////////////////////////////////////////////////


 

 /////////////////////////////////////////////////////////////////////
 5 faire un if else sur  le prix d'un produit pas cher, tres cher
 /////////////////////////////////////////////////////////////////////

 {$product.price}	Prix affiché (22,94 €)
 {$product.price_amount}	Prix TTC (float)



 /////////////////////////////////////////////////////////////////////

 faire un if {$product.quantity} > 1 alors afficher
{if $product.quantity < 1}opacity-25{/if}



 /////////////////////////////////////////////////////////////////////
 faire un decompte pour la livraison gratuite
 {$cart.totals.total.amount}
 59€ message par defaut tant que le panier est vide
 message plus que 13€ quand le panier est rempli
 felicitation on vous offre les fdp




 /////////////////////////////////////////////////////////////////////

 https://css.comonsoft.com/tutoriels/liste-des-variables-smarty-prestashop-1-7-x-et-8-x.htm

 https://www.smarty.net/docsv2/fr/language.function.if.tpl



































33.
