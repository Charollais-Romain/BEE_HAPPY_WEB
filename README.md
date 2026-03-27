# Bee Happy — Dashboard ruchers & ruches

Application web **PHP + MySQL + JavaScript** : carte des ruchers, position des ruches, page de statistiques (poids, température) avec graphiques. Thème visuel moderne « abeille / miel ».

## Prérequis

- PHP **7.4+** (extensions `pdo_mysql`)
- MySQL ou MariaDB
- (Optionnel) Node.js — l’ancien serveur `Server.js` sert surtout les assets statiques ; **les pages dashboard PHP** nécessitent un serveur avec PHP (Apache, nginx+php-fpm, ou `php -S`).

## Installation

1. **Créer la base** et un utilisateur MySQL (ex. `bee_happy`).

2. **Configurer** `config/config.php` (copie de `config/config.example.php` si besoin) :
   - `host`, `name`, `user`, `pass`

3. **Importer le schéma** (tables préfixées `bee_` pour limiter les collisions) :

   ```bash
   mysql -u root -p bee_happy < sql/schema.sql
   ```

4. **Générer des mesures de démo** (recommandé pour les graphiques) :

   ```bash
   php scripts/seed_mesures.php
   ```

5. **Pointer le document root** du serveur web vers le dossier **`public/`**  
   Exemple avec le serveur intégré PHP (depuis la racine du dépôt) :

   ```bash
   php -S localhost:8080 -t public
   ```

   Puis ouvrir : [http://localhost:8080/index.php](http://localhost:8080/index.php)

## Structure

| Élément | Rôle |
|--------|------|
| `public/index.php` | Carte de tous les ruchers |
| `public/rucher.php?id=` | Carte des ruches du rucher |
| `public/ruche.php?id=` | Statistiques + graphiques |
| `public/api/*.php` | API JSON pour le front |
| `sql/schema.sql` | Schéma + jeux de ruchers/ruches |
| `scripts/seed_mesures.php` | Données de mesures factices |

## API JSON

- `GET public/api/ruchers.php` — liste des ruchers  
- `GET public/api/rucher.php?id=` — détail rucher + ruches  
- `GET public/api/ruche.php?id=` — infos ruche + nom du rucher  
- `GET public/api/ruche-stats.php?id=&days=` — séries pour les graphiques (7–365 jours)

## Intégration capteurs

Pour enregistrer des mesures depuis un capteur, insérez dans `bee_mesures` :

- `ruche_id`, `poids_kg`, `temperature_c` (nullable), `mesure_at`

Vous pouvez réutiliser la logique de `Connexion/main.php` en l’adaptant à ces colonnes.

## Ancien projet (Node / Socket)

Le dossier `public/` contenait `index.html` + `Server.js` pour un dashboard temps réel. Le flux principal documenté ici est **PHP**. Vous pouvez conserver Node pour d’autres usages ou rediriger la connexion vers `public/index.php` après login.
