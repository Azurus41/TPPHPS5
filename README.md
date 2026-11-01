# TPPHPS5 - Recettes (Symfony 6.4)

Projet du TD/TP « Découvrir Symfony en 6.4 » (BUT MMI S5). Missions 1 à 41 codées.
Ce README sert de **guide d'installation sur un nouveau PC** et de **liste de tests à faire**.

---

## 1. À installer sur un nouveau PC

| Outil | Détail |
|---|---|
| **XAMPP** (PHP 8.2+, MariaDB) | Démarrer **MySQL** dans le panneau XAMPP |
| **Extensions PHP** | Dans `php.ini`, décommenter `extension=intl` et `extension=sodium` (obligatoires : EasyAdmin et JWT) ; `openssl` doit être actif |
| **Composer** | https://getcomposer.org |
| **Git** | pour cloner le dépôt |
| **MailHog** | https://github.com/mailhog/MailHog (SMTP sur le port **1025**, interface sur http://localhost:8025) |
| *(optionnel)* Insomnia / Postman, curl | pour tester l'API et le JWT |

## 2. Mise en route

```bash
git clone https://github.com/Azurus41/TPPHPS5
cd TPPHPS5
composer install
```

1. Vérifier `DATABASE_URL` dans `.env` (par défaut : `mysql://root:@127.0.0.1:3306/recettesDB?serverVersion=mariadb-10.4.28`).
2. Créer la base, les tables et les données de test :
   ```bash
   php bin/console doctrine:database:create --if-not-exists
   php bin/console doctrine:migrations:migrate -n
   php bin/console doctrine:fixtures:load -n
   ```
3. **Clés JWT** (non versionnées, à régénérer sur chaque PC) :
   ```bash
   php bin/console lexik:jwt:generate-keypair
   ```
   Sous Windows, si l'erreur `error:80000003:system library` apparaît, définir d'abord
   `OPENSSL_CONF=C:\xampp\apache\conf\openssl.cnf` (PowerShell : `$env:OPENSSL_CONF='C:\xampp\apache\conf\openssl.cnf'`).
4. Assets : `php bin/console importmap:install`
5. Lancer **MailHog** (`MailHog.exe`), puis le serveur :
   ```bash
   symfony serve        # ou : php -S 127.0.0.1:8000 -t public
   ```
   Adresse utilisée ci-dessous : `http://127.0.0.1:8000` (noté `BASE`).

`.env` contient `MAILER_DSN=smtp://localhost:1025` (MailHog) ; sans MailHog, l'envoi d'emails échoue proprement (message d'erreur / log) mais l'appli ne plante pas.

## 3. Comptes de test (fixtures)

| Compte | Mot de passe | Rôle |
|---|---|---|
| `admin@a.com` | `1234` | ROLE_ADMIN |
| 20 utilisateurs aléatoires (voir table `user`, ex. `SELECT email FROM user LIMIT 5`) | `1234` | ROLE_USER |

## 4. Ce qu'il reste à vérifier (jamais testé dans un navigateur)

Tout a été testé en HTTP/curl, mais **rien n'a été vu à l'écran**. Cocher au fur et à mesure.

### Sécurité, inscription (Missions 23-25, 31)
- [ ] `BASE/ingredient` : liste publique, liens « Se connecter » / « S'inscrire ».
- [ ] `BASE/register` : créer un compte → un mail arrive dans http://localhost:8025 → cliquer le lien → redirection vers `/ingredient` avec le message « Votre email est vérifié ».
- [ ] `BASE/login` avec `admin@a.com` / `1234` : redirection vers `/ingredient`, email affiché + lien « Se déconnecter », mail « Connexion détectée » dans MailHog.
- [ ] Déconnexion → retour sur `/ingredient`.
- [ ] Connecté en simple utilisateur : pas de boutons Modifier/Supprimer ; `BASE/ingredient/edit/202` → accès refusé ; bouton « Créer un ingrédient » visible.
- [ ] Non connecté : `BASE/ingredient/create_and_store_v3` → redirigé vers `/login`.

### Ingrédients (Missions 26, 28, 29)
- [ ] `BASE/ingredient/create_and_store_v3` : créer un ingrédient (slug généré automatiquement).
- [ ] `BASE/ingredient/edit/202` (admin) : renommer en « Épinard de Besançon » → `updatedAt` et slug changent.
- [ ] `BASE/ingredient/by_slug/epinard-de-besancon` et `BASE/ingredient/202` : fiche ingrédient.
- [ ] `BASE/ingredient/tom` puis `var/log/dev.log` : messages « APPEL DE find_ingredient_tom » et « ERREUR DANS find_ingredient_tom ».
- [ ] `var/log/dev.log` : « AFFICHAGE DE TOUS LES INGREDIENTS » (après `/ingredient`) et « CREATION D'UN NOUVEL INGREDIENT » (après création).

### Recettes et tags (Missions 22, 41)
- [ ] `BASE/recette` : liste des recettes avec leurs ingrédients.
- [ ] `BASE/recette/create` : choisir exactement 3 ingrédients, **ajouter / supprimer des tags avec les boutons (JavaScript, jamais vu s'exécuter)**, enregistrer.

### Services, emails (Missions 30-33, 40)
- [ ] `BASE/hello` : « Pour un prix de 360 EUR HT … TVA 72,00 EUR … TTC 432,00 EUR ».
- [ ] `BASE/contact` : envoyer le formulaire (tester aussi les erreurs de validation) → mail dans MailHog à `admin@a.com`.
- [ ] Terminal : `php bin/console app:newsletter` → 21 mails dans MailHog.

### API REST + JWT (Missions 34-36)
- [ ] `BASE/api` : page Swagger UI (publique).
- [ ] Récupérer un token : `POST BASE/api/login_check` avec `{"email":"admin@a.com","password":"1234"}` (Swagger, Insomnia, Postman et curl). Le décoder sur https://jwt.io avec `config/jwt/public.pem` → « signature verified » (RS256, rôles, email).
- [ ] Swagger : bouton **Authorize** → `Bearer <token>` → tester les opérations de `/api/ingredients` (GET, POST, PUT, PATCH, DELETE).
- [ ] Filtres : `BASE/api/ingredients?nom=ingr_a` et `?prix[gte]=100`.
- [ ] `/api/recettes` : créer une recette avec un utilisateur normal, puis essayer de modifier/supprimer celle d'un autre → **403** ; l'admin peut tout modifier.
- Note : l'API n'accepte que `application/ld+json` par défaut (header `Accept: application/ld+json`).

### Posts et cache (Missions 37-38)
- [ ] `BASE/post` : ~5 s la 1ère fois, puis rapide (cache 20 s) ; recharger après 20 s → de nouveau lent. `php bin/console cache:clear` vide le cache.
- [ ] `BASE/post_with_token` : affiche les posts.
- ⚠ **freefakeapi.io n'existe plus** (domaine à vendre) : les pages affichent 3 posts d'exemple. Les URLs de l'API distante dans `src/Controller/PostController.php` sont des suppositions. Pour les tests Insomnia de la Mission 37, utiliser une autre fausse API (ex. jsonplaceholder.typicode.com) ou en discuter avec l'enseignant.

### Administration EasyAdmin (Mission 39)
- [ ] `BASE/admin` (connecté en admin ; un simple user doit être refusé) : tableau de bord personnalisé + menu.
- [ ] Chaque entrée du menu (Ingrédients, Recettes, Tags, Utilisateurs) : colonnes affichées, actions Éditer / Supprimer **visibles sans menu « 3 petits points »**, champs `createdAt` etc. cachés dans le formulaire d'édition.
- [ ] Liste des recettes : colonne ingrédients = « nom (id), nom (id)… ». Liste des ingrédients : colonne recettes = « nom (id), … ».

## 5. Rappels techniques

- Fixtures : `php bin/console doctrine:fixtures:load -n` **vide** la base avant de la remplir.
- Vérifier le schéma : `php bin/console doctrine:schema:validate`.
- Les clés `config/jwt/*.pem` ne sont jamais commitées (voir `.gitignore`).
- Le fichier PDF du TD n'est pas versionné.
