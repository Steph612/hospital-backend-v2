# ⚙️ Commandes Symfony
🚀 Créer un projet
```bash
composer create-project symfony/skeleton hospital-backend
```

🚀 Se connecter
```bash
ssh symfony-vm
cd hospital-backend
```

⚙️ Install new dependencies Symfony
```bash
composer require webapp
composer require orm maker validator serializer security doctrine/doctrine-migrations-bundle
composer require symfony/uid symfony/property-access symfony/property-info
composer require nelmio/cors-bundle
composer require --dev symfony/test-pack phpunit/phpunit
```

# ➕ Pour créer toute cette structure en une seule fois sur Ubuntu
```bash
mkdir -p \
src/PatientAdmission/Application/Create/{Command,Handler,DTO,Event,EventHandler} \
src/PatientAdmission/Domain/{Entity,ValueObject,Repository,Exception} \
src/PatientAdmission/Infrastructure/Persistence/Doctrine \
src/PatientAdmission/UI/Http \
src/IdentityAccess/Application \
src/IdentityAccess/Domain/{Entity,Repository,Exception} \
src/IdentityAccess/Infrastructure/Persistence/Doctrine \
src/IdentityAccess/UI/Http \
src/Shared/Infrastructure/Http/{Exception,EventSubscriber} \
tests/Integration/PatientAdmission/UI/Http \
tests/Unit/PatientAdmission/Application/Create
```

## ✅ composer.json propre pour l’autoload
```json
"autoload": {
    "psr-4": {
        "App\\": "src/"
    }
},
"autoload-dev": {
    "psr-4": {
        "App\\Tests\\": "tests/"
    }
}
```
Puis :
```bash
composer dump-autoload
```

▶️ Lancer le projet
```bash
symfony serve --allow-all-ip

https://10.13.165.72:8000/
```

▶️ ou en arrière plan & affiche les logs
```bash
symfony serve -d --allow-all-ip
symfony serve:log
```

💡 Autres commandes
```bash
symfony serve -d --allow-all-ip
symfony server:status
symfony serve:stop
symfony serve:log
```

# ➕ Créer l’utilisateur et la base
```bash
php bin/console doctrine:database:create
```

Ou ouvre PostgreSQL :
```bash
sudo -u postgres psql;
```
Puis exécute :
```bash
#CREATE USER app WITH PASSWORD 'app';
CREATE DATABASE hospital_backend OWNER app;
```
Tu peux tester la connexion :
```bash
psql -U app -h 127.0.0.1 -d hospital_backend;
```

# ✅ La base de données test
```bash
Configure ton .env.test :
APP_ENV=test
APP_SECRET='test_secret'
DATABASE_URL="postgresql://app:app@127.0.0.1:5432/hospital_backend?serverVersion=16&charset=utf8"
```
```bash
sudo -u postgres psql -c "DROP DATABASE IF EXISTS hospital_backend_test;"
sudo -u postgres psql -c "CREATE DATABASE hospital_backend_test OWNER app;"

APP_ENV=test php bin/console doctrine:migrations:migrate -n
```

```bash
# Se connecter
sudo -u postgres psql;

# Listing databases & Switching databases
postgres=# \l
postgres=# \c hospital_backend

# Listing tables
hospital_backend=# \dt

# Mode étendu pour lister une table
hospital_backend_test=# \x
hospital_backend_test=# \x off
hospital_backend_test=# \x auto
```

💡 Aligner proprement les colonnes
```bash
\pset border 2
\pset format aligned
\pset linestyle unicode
\x auto
```
👉 Ça donne un tableau bien structuré avec des bordures

# 🚀 Utilisations CURL
## endpoint REST : /api/patient-admissions
```bash
~$ curl -k -i -X POST https://10.13.165.72:8000/api/patient-admissions \
  -H "Content-Type: application/json" \
  -d '{
    "patientId": "33333333-3333-4333-8333-333333333333",
    "hospitalUnitCode": "CARDIO",
    "admittedAt": "2026-03-28T10:30:00+01:00",
    "reason": "Chest pain assessment",
    "createdBy": "22222222-2222-4222-8222-222222222222"
  }'
```
```json
HTTP/2 201

{
   "id":"019d35f0-e21e-798c-b85a-170eff3cfd19",
   "status":"ADMITTED",
   "patientId":"33333333-3333-4333-8333-333333333333",
   "hospitalUnitCode":"CARDIO",
   "admittedAt":"2026-03-28T10:30:00+01:00",
   "reason":"Chest pain assessment",
   "createdBy":"22222222-2222-4222-8222-222222222222",
   "createdAt":"2026-03-28T19:34:32+00:00"
}
```
```json
HTTP/2 409

{
   "message":"Patient \"33333333-3333-4333-8333-333333333333\" already has an active admission."
}
```

# 🚀 Utilisations Test
## Test unitaire
```bash
~/hospital-backend$ php bin/phpunit tests/Unit

PHPUnit 11.5.55 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.2.30
Configuration: /home/ubuntu/hospital-backend/phpunit.dist.xml

........                                                            8 / 8 (100%)

Time: 00:00.008, Memory: 10.00 MB

OK (8 tests, 26 assertions)
```
## Test d'intégration
```bash
~/hospital-backend$ php bin/phpunit tests/Integration

PHPUnit 11.5.55 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.2.30
Configuration: /home/ubuntu/hospital-backend/phpunit.dist.xml

...                                                                 3 / 3 (100%)

Time: 00:00.269, Memory: 36.50 MB

OK (3 tests, 14 assertions)
```
