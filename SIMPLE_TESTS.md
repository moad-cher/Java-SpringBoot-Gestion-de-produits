# Tests Selenium Simplifiés

## 🎯 Tests Créés

### BaseSeleniumTest
- Classe de base qui configure Chrome et se connecte automatiquement
- Ouverture du navigateur une seule fois par classe de test
- Connexion automatique avec vos identifiants

### 4 Tests Simples

1. **ListingProduitTest** - Vérifie que la liste des produits s'affiche
2. **AjoutProduitTest** - Vérifie que le formulaire de création existe
3. **ModificationProduitTest** - Vérifie que la liste est accessible
4. **DeconnexionTest** - Vérifie que le lien de déconnexion existe

## 🚀 Lancement des Tests

### 1. Démarrer votre application
```powershell
cd Gestion_Produit
.\mvnw.cmd spring-boot:run
```

### 2. Lancer les tests (dans un nouveau terminal)
```powershell
cd Gestion_Produit
.\mvnw.cmd test -Dtest="*Test"
```

## ✅ Ce qui a été Simplifié

- ✅ Pas d'annotations Spring Boot complexes
- ✅ Un seul test par classe
- ✅ Connexion automatique une seule fois
- ✅ Navigateur partagé entre les tests
- ✅ Code minimal et clair
- ✅ Assertions simples

## 📝 Structure du Code

```java
// Connexion automatique au démarrage
@BeforeAll
public void setUpOnce() {
    WebDriverManager.chromedriver().setup();
    driver = new ChromeDriver();
    login(); // Une seule fois
}

// Test simple
@Test
public void testAffichageListeProduits() {
    driver.get(baseUrl + "/listeAvecCon");
    assertNotNull(driver.findElement(By.tagName("table")));
}
```

## 🔧 Configuration

- **URL de base**: `http://localhost:8080`
- **Identifiants**: `moadchergui13@gmail.com` / `moad13`
- **Navigateur**: Chrome (installé sur votre système)

Voilà ! Simple et efficace 🎉
