# Best Sellers

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
 
## Hook

Le module affiche les 4 meilleures ventes de votre boutique sur la page d'accueil, via le hook `home.body`

## Loop

Le module vous propose la boucle `best_selling_products`, qui étend la boucle `product`. Tous les arguments de la boucle
`product` sont donc disponibles.

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
|$SALE_RATIO | Le pourcentage du CA surla période considérée |

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
