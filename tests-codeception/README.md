# Tests Codeception - Gestion de Produits

Tests fonctionnels écrits avec Codeception pour tester l'application Spring Boot de gestion de produits.

## Installation

```bash
cd tests-codeception
composer install
```

## Configuration

Les tests utilisent Chrome en mode headless et se connectent à l'application sur `http://localhost:8080`.

## Exécution des tests

### Tous les tests
```bash
vendor/bin/codecept run functional
```

### Un test spécifique
```bash
vendor/bin/codecept run functional ListingProduitCest
```

### Avec plus de détails
```bash
vendor/bin/codecept run functional --steps
```

## Tests disponibles

1. **ListingProduitCest** - Vérifie l'affichage de la liste des produits
2. **AjoutProduitCest** - Teste la création d'un nouveau produit
3. **DeconnexionCest** - Teste la déconnexion de l'utilisateur
4. **ModificationProduitCest** - Teste la modification d'un produit existant

## Prérequis

- PHP 7.4+
- Application Spring Boot démarrée sur localhost:8080
- Chrome/Chromium installé
- ChromeDriver compatible

## CI/CD

Ces tests sont intégrés dans le workflow GitHub Actions et s'exécutent après les tests Selenium.
