# Best Sellers

# en_US

This modules provides a loop which return the best (or the worst) sales.

## Installation

Manually, or with composer :

```
composer require cqfdev/best-sellers-module:~1.0
```

## Usage

This module shows the 4 best sales of your shop on the front page via the `home.body` hook.

You can also add where you want in your template (front or back-office), a loop `best_selling_products` to show your best or your worst sales. 

In the back-office, you can see your best sales in the "Tools" menu.

Finally, the total number of sales of a product appears on the product sheet. 

Update 1.2.0 : You can now choose which order statuses are taken into account to calculate your best sellers. A configuration page has been added to the module. Access it from the modules page.

## Hook

This module shows the 4 best sales of your shop on the front page via the `home.body` hook.

## Loop

The module provide the loop `best_selling_product`, which extend the loop `product`. All the arguments of the `product` loop are therefore available.

`best_selling_products` loop

### Input parameters

All the arguments of the loop `product` are available.

The loop offers two new values for the parameter `order` of the loop `product``
- sold_count_reverse : sort by number of sales in decreasing order
- sold_count : sort by number of sales in increasing order

|Argument |Description |
|---      |--- |
|**start-date** | The period start date to be consider. By default, january 1st 1970. |
|**end-date** | The period end date to be consider. By default, today's date. |

### Output variables

All the variables of the loop `product`are available. 

|Variable   |Description |
|---        |--- |
|$SOLD_QUANTITY | The quantity of sold product on the considered period |
|$SOLD_AMOUNT | The total amount untaxed of sales on the considered period |
|$SALE_RATIO | The percentage of sales on the considered period |

### Example

To get your 10 best sales of all time:

    <ul>
        {loop type="best_selling_products" name="best-sellers" limit=10 order='sold_count_reverse'}
            <li>{$REF} : {$TITLE} : {$SOLD_QUANTITY}</li>
        {/loop}²²
    </ul>

To get your 5 best sales of the month :

    <ul>
        {loop type="best_selling_products" name="best-sellers-this-month" order='sold_count_reverse' start_date={$smarty.now|date_format:'%Y-%m-01'} limit=5}
            <li>{$REF} : {$TITLE} : {$SOLD_QUANTITY}</li>
        {/loop}
    </ul>

To get your 10 worst sales of all time :     

    <ul>
        {loop type="best_selling_products" name="best-sellers" limit=10 order='sold_count'}
            <li>{$REF} : {$TITLE} : {$SOLD_QUANTITY}</li>
        {/loop}
    </ul>


# fr_FR

Ce module vous fournit une boucle qui retourne vos meilleures (ou vos pires) ventes.

## Installation

Manuellement, ou avec composer :

```
composer require cqfdev/best-sellers-module:~1.0
```

## Usage

Ce module affiche les 4 meilleures ventes de votre boutique sur la page d'accueil, via le hook 'home.body'

Vous pouvez aussi ajouter où vous voulez dans votre template front office ou back-office une boucle `best_selling_products` pour afficher vos meilleures ou pires ventes.

Dans le back-office, vous pouvez voir vos meilleures ventes dans le menu "Outil".

Enfin, le nombre de ventes total d'un produit apparaît sur la fiche produit.

Update 1.2.0 : Le module permet désormais de choisir quels status de commande utiliser pour calculer vos best sellers. Une page de configuration à été ajoutée, accessible depuis la page "modules".
 
## Hook

Le module affiche les 4 meilleures ventes de votre boutique sur la page d'accueil, via le hook `home.body`

## Loop

Le module vous propose la boucle `best_selling_products`, qui étend la boucle `product`. Tous les arguments de la boucle `product` sont donc disponibles.

`best_selling_products` loop

### Paramètres en entrée

Tous les arguments de la boucle `product` sont disponibles.

La boucle propose deux valeurs supplémentaires pour le paramètre `order` de la boucle `product`:
- sold_count_reverse : trier par nombre de ventes décroissantes
- sold_count : trier par nombre de ventes croissantes

|Argument |Description |
|---      |--- |
|**start-date** | la date de début de période à prendre en compte. Par défaut, le 1er janvier 1970. |
|**end-date** | la date de fin de période à prendre en compte. Par défaut, la date du jour. |

### Variables en sortie

Toutes les variables de la boucle `product` sont disponibles.

|Variable   |Description |
|---        |--- |
|$SOLD_QUANTITY | La quantité de produit vendue sur la période considérée |
|$SOLD_AMOUNT | Le montant total HT des ventes sur la période considérée |
|$SALE_RATIO | Le pourcentage du CA sur la période considérée |

### Exemple

Pour obtenir vos 10 meilleures ventes de tous les temps :

    <ul>
        {loop type="best_selling_products" name="best-sellers" limit=10 order='sold_count_reverse'}
            <li>{$REF} : {$TITLE} : {$SOLD_QUANTITY}</li>
        {/loop}
    </ul>

Pour obtenir les 5 meilleures ventes du mois :

    <ul>
        {loop type="best_selling_products" name="best-sellers-this-month" order='sold_count_reverse' start_date={$smarty.now|date_format:'%Y-%m-01'} limit=5}
            <li>{$REF} : {$TITLE} : {$SOLD_QUANTITY}</li>
        {/loop}
    </ul>
    
Pour obtenir vos 10 pires ventes de tous les temps :

    <ul>
        {loop type="best_selling_products" name="best-sellers" limit=10 order='sold_count'}
            <li>{$REF} : {$TITLE} : {$SOLD_QUANTITY}</li>
        {/loop}
    </ul>
