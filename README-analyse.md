# Le point clé à retenir

Quand tu vois un code mal conçu en examen, pose-toi cette question :

Qu’est-ce qui casserait en production ou donnerait un mauvais comportement métier ?

## Ordre recommandé :
- erreurs métier
- concurrence
- validation
- HTTP
- sécurité / SQL
- structure du code
- architecture plus avancée

**C’est cet ordre qui te fera marquer le plus.**

# Exemple de réponse “jury-compatible” pour un code PHP (version pragmatiques)

## 1. Problèmes dans le code
- Les codes HTTP sont incorrects : 500 ne convient ni pour des données invalides ni pour une session complète.
- Les entrées ne sont pas validées.
- Le contrôleur contient trop de logique métier.
- La requête SQL est construite par concaténation, ce qui est risqué et peu maintenable.
- La vérification du quota est incomplète car elle ne tient pas compte du statut des inscriptions.
- Le code ne vérifie pas qu’un stagiaire n’est pas déjà inscrit à la session.
- Il n’y a pas de transaction, donc le quota peut être dépassé en cas d’accès concurrents.
- La création retourne 200 au lieu de 201.

## 2. Problèmes les plus graves
- Absence de contrôle de double inscription.
- Absence de transaction et de gestion de concurrence.
- Mauvaise gestion des erreurs HTTP.
- SQL brut non paramétré.

## 3. Améliorations pragmatiques
- Valider les données d’entrée.
- Vérifier l’existence du stagiaire et de la session.
- Vérifier l’unicité de l’inscription.
- Compter correctement les inscriptions actives.
- Encapsuler la création dans une transaction.
- Déplacer la logique métier vers un service applicatif.
- Retourner des codes HTTP adaptés : 201, 400, 404, 409.

# Exemple de réponse “DDD”

❓ Tes questions
## 1. Qu’est-ce qui ne va pas dans ce code ?
### Repère un maximum de problèmes.
- L'erreur "Invalid data" n'est pas une Exception ! Le code 400 ou 404 devrait être utilisé avec un message explicite dans une Exception. 
- L'erreur "Session full" n'est pas une Exception ! Le code 409 devrait être utilisé avec un message explicite dans une Exception.
- Pour l'encapsulation des attributs et masquer l'état interne de l'entité Registration, les propriétés doivent être sécurisés en private readonly.
- L'entité Registration ne devrait pas utiliser des méthodes d'accès pour modifier son état ! Il devrait utiliser des méthodes intrinsèques qui modifie l'état de manière cohérante.
- Le controller ne devrait pas avoir des appels à la base de données. De plus, l'appel au repository doit se faire via une Interface. Cela permet de tester les méthodes.
- Le nom du controller n'a pas une sémantique Métier (Intentionnelle). Il faut utiliser un verbes (CreateSessionController) qui décrit une action réelle du domaine métier.
- L'utilisation de la méthode magique __invoke() n'a pas été utilisé ! Cela permet de transformer un contrôleur en un contrôleur à action unique (Single Action Controller).
- Il manque un port entrant qui fait le lien avec le monde extérieur. Par ex. CreateSessionRequest, qui gère la forme des données reçues JSON et la validation.
- Il manque un/plusieurs EventSubscriber pour gérer les erreurs proprements avec des Exceptions.
- Créer un Handler par ex. CreateSessionHandler : C'est le Service Applicatif. Il contient la logique de coordination (appel au Domaine, au Repository, etc.).
- Dans le cas d'une réponse 200, la création de l'inscription devrait être retrounée par l'intermédiaire d'un port sortant. Par ex. CreateSessionResponse.


## 2. Quels sont les plus graves ?
### Classe-les si possible.
- La vérification de la double inscription n'est pas gérée !
- Lors de la création d’une inscription, il faut exécuter la vérification et l’insertion dans une transaction. La ligne ou l’ensemble des inscriptions doivent être verrouillés pour éviter qu’une autre transaction insère une inscription concurrente sur le même créneau. Si un conflit est détecté au moment du commit, l'inscription est refusée avec un message clair.

## 3. Comment améliorer sans tout réécrire ?
### Reste pragmatique.
- Vérifier la double inscription.
- Lors de la création d’une inscription, il faut exécuter les vérifications et l’insertion dans une transaction. La ligne ou l’ensemble des inscriptions doivent être verrouillés pour éviter qu’une autre transaction insère une inscription concurrente sur le même créneau. Si un conflit est détecté au moment du commit, l'inscription est refusée avec un message clair.
- Utiliser des Exceptions pour gérer les erreur !


# Version attendue “copie forte”, très synthétique
## Modélisation
- intern(id, name, email)
- training(id, name, quota, start_date)
- registration(id, registered_at, status, intern_id, training_id)

## Relations :
- un stagiaire possède plusieurs inscriptions
- une formation possède plusieurs inscriptions
- une inscription lie un stagiaire à une formation

## Contraintes :
- clés étrangères sur intern_id et training_id
- unicité sur (intern_id, training_id)

## Gestion du quota :
Avant de créer une inscription confirmée, on compte les inscriptions confirmées de la formation.
Si le nombre atteint le quota, on refuse l’inscription.

## API
POST /api/registrations
body JSON : intern_id, training_id

### Réponses :
201 inscription créée
400 données invalides
404 stagiaire ou formation introuvable
409 quota atteint ou déjà inscrit

## Concurrence
La vérification du quota et l’insertion doivent être faites dans une transaction avec verrouillage, pour éviter que deux personnes prennent la dernière place en même temps.


# Version attendue “copie forte”, DDD
EXERCICE 1 — Analyse experte (45%)

## 1.1 Modélisation complète

users
- id           int (primary)
- name         varchar(255)

vehicles
- id           int (primary)
- model        varchar(100)
- plate_number varchar(10)

bookings
- id           int int (primary)
- user_id      int (étrangère)
- vehicle_id   int (étrangère)
- start_at     datetime
- end_at       datetime

relations
- Un utilisateur peut faire plusieurs réservations 1-N
- Une réservation peut avoir un seul utilisateur et un seul véhicule 1-1
- Un véhicule peut avoir plusieurs réservations et plusieurs utilisateurs 1-N

contraintes (IMPORTANT)
- Valider start_at < end_at
- Sauver les demandes dans une transaction et si se n'est pas valider, tout annuler et afficher un message clair.
- Vérifier si il n'y a pas de chevauchement dans la réservation du même véhicule
- Vérifier si il n'y a pas de chevauchement dans la réservation d'un autre véhicule

## 1.2 Logique métier complète
- Vérifier si un utilisateur exist / logué
- Utiliateur connecté
- Un utilisateur sélectionne un véhicule pour le réserver
- Valider les dates de sélection start_at < end_at
- Vérifier en sauvant les requêtes SQL dans une transaction SQL. Et si le même ou un autre véhicule est déjà réservé ou en chevauchements. Si c'est le cas, tout stoper !
- Afficher un message clair en cas de conflit
- Sauver la réservation après validation avec un message clair et envoyer un e-mail à l'utilisateur et à un responsable pour la préparation du véhicule

## 1.3 Concurrence (très important)
Vérifier en sauvant les requêtes SQL dans une transaction SQL. Et si le même véhicule est déjà réservé, tout stoper et afficher un message clair !

EXERCICE 2 — PHP expert (30%)

## 2.1 Critique experte : Donne 5 problèmes importants

- Pour l'encapsulation des propriétés et masquer l'état de celle-ci, passer les propriéter en private readonly.
- Ajouter un linter de niveau maximum pour la qualité du code
- Type Hinting toutes les propriétés de la classes
- Ajouter un type immutable au dates et vérifier si start_at < end_at, sinon retourner une exception
- Ajouter une méthode de vérification pour les chevauchements d'un même véhicule a réserver

## 2.2 Refactoring propre & 3. Logique métier

    <?php

    declare(strict_types=1);

    namespace App\Model;

    final class Reservation
    {
        public function __contruct(
            private readonly \DateTimeImmutable $start,
            private readonly \DateTimeImmutable $end,
        ) {
            if ($start >= $end) {
                throw new \InvalidArgumentException('La date de fin doit être postérieure à la date de début.');
            }
        }
        
        public function conflictsWith(self $other): bool
        {
            return $this->start < $other->getStart() && $this->end > $other->getEnd();
        }
    }

## EXERCICE 3 — SQL expert (25%)
Tables :
- vehicles(id, model, plate_number)
- users(id, name)
- bookings(id, user_id, vehicle_id, start_at, end_at)

### 1. 👉 récupérer les véhicules disponibles sur une période donnée
    SELECT v.id, v.model, v.plate_number
    FROM vehicles v
    LEFT JOIN bookings b ON (
        b.vehicle_id = v.id
        AND b.start_at < '2026-04-01 11:00:00'
        AND b.end_at > '2026-04-01 10:00:00'
    )
    WHERE b.id = NULL

### 2. 👉 récupérer les utilisateurs ayant au moins un conflit de réservation
    SELECT DISTINCT u.id, u.name
    FROM users u
    INNER JOIN bookings b1 ON (b1.user_id = u.id)
    INNER JOIN bookings b2 ON (
        b2.user_id = u.id
        AND b1.start_at < b2.end_at
        AND b1.end_at > b2.start_at
    );

🟢 Niveau confortable (assurer réussite)\
🟠 Niveau réaliste (comme examen)\
🔴 Niveau difficile (te pousser) <-

# EXERCICE 1 — Analyse avancée (45%)
## 1.1 Modélisation avancée
users
- id       int primaire
- name     varchar(255)
- email    varchar(100)

rooms
- id       int primaire
- name     varchar(255)
- capacity int

reservations
- id       int (primaire)
- user_id  int (étrangère)
- room_id  int (étrangère)
- start_at datetime
- end_at   datetime
    
## 1.2 Relations
- Un utilisateur peut faire plusieurs réservations : 1-N
- Une salle peut avoir plusieurs réservations : 1-N
- Une réservation appartient à un utilisateur et à une salle : 1-N

### Contraintes :
- start_at < end_at
- pas de chevauchement pour une même salle
- pas de chevauchement pour un même utilisateur sur deux réservations distinctes

## 2. Logique métier
- vérifier que l’utilisateur existe / est authentifié
- utilisateur connecté 
- vérifier qu’aucune réservation de la même salle ne chevauche le créneau
- vérifier que l’utilisateur n’a pas déjà une autre réservation au même moment
- affichage d’un message clair en cas de validation ou d'erreur
- enregistrer la réservation
- Lors de la création d’une réservation, il faut exécuter la vérification et l’insertion dans une transaction.
- réservation validé, un email est envoyé à l'utilisateur et à un responsable 

## 3. Cas limite (très important)
- Reponse 1 : Lors de la création d’une réservation, il faut exécuter la vérification et l’insertion dans une transaction. Si un conflit est détecté (autre réservation au même moment ou chevauchement) au moment du commit, la réservation est refusée avec un message clair.
- Réponse 2 (mieux) : Lors de la création d’une réservation, il faut exécuter la vérification et l’insertion dans une transaction. La ligne ou l’ensemble des réservations de la salle concernée doivent être verrouillés pour éviter qu’une autre transaction insère une réservation concurrente sur le même créneau. Si un conflit est détecté au moment du commit, la réservation est refusée avec un message clair.

# EXERCICE 2 — PHP avancé (30%)
## 1. Critique avancée : Donne 5 problèmes ou améliorations importantes
- Pour la sécurité et la robustèsse du code, le Type Hinting doit être présent dans les attributs et les paramètres du constructeur
- Pour l'encapsulation des attributs et masquer l'état interne d'un objet, les propriétés doivent être sécurisés en private readonly
- Les dates passées en paramètre doivent être typées avec la classe DateTimeImmutable
- Ajouter des validations sur les dates
- Ajouter des méthodes d’accès ou lecture
- validation que start < end
- éventuellement classe immutable avec propriétés private readonly
- absence de logique métier de chevauchement
- absence de namespace ou declare(strict_types=1) dans la version d’origine

## 2. Refactoring propre
Réécris la classe avec : typage, validation, immutabilité (si possible)

    <?php

    declare(strict_types=1);

    namespace App\Model;

    final class Reservation
    {
        public function __construct(
            private readonly \DateTimeImmutable $start,
            private readonly \DateTimeImmutable $end,
        ) {
            if ($start >= $end) {
                throw new \InvalidArgumentException('La date de fin doit être postérieure à la date de début.');
            }
        }

        public function getStart(): \DateTimeImmutable
        {
            return $this->start;
        }

        public function getEnd(): \DateTimeImmutable
        {
            return $this->end;
        }
    }

## 3. Logique métier - Ajoute une méthode : 👉 vérifier si deux réservations se chevauchent

    <?php

    public function overlapsWith(self $other): bool
    {
        return $this->start < $other->getEnd()
            && $this->end > $other->getStart();
    }

### 🧠 Astuce mentale ultra simple
#### Chevauchement
    start1 <  end2   AND   end1 >  start2
#### Pas de chevauchement
    end1   <= start2 OR  start1 >= end2

# EXERCICE 3 — SQL avancé (25%)
## 1. 👉 récupérer les salles disponibles sur un créneau donné
(ex : 2026-04-01 10:00 → 12:00)

**Donc une requête correcte serait par exemple :**

    SELECT ro.id, ro.name, ro.capacity
    FROM rooms ro
    WHERE ro.id NOT IN (
        SELECT r.room_id
        FROM reservations r
        WHERE r.start_at < '2026-04-01 12:00:00'
        AND r.end_at > '2026-04-01 10:00:00'
    );

**ou avec LEFT JOIN :**

    SELECT ro.id, ro.name, ro.capacity
    FROM rooms ro
    LEFT JOIN reservations r
        ON r.room_id = ro.id
    AND r.start_at < '2026-04-01 12:00:00'
    AND r.end_at > '2026-04-01 10:00:00'
    WHERE r.id IS NULL;

## 2. 👉 récupérer les utilisateurs qui ont au moins 2 réservations qui se chevauchent
    SELECT DISTINCT u.id, u.name
    FROM users u
    INNER JOIN reservations r1 ON r1.user_id = u.id
    INNER JOIN reservations r2 ON r2.user_id = u.id
        AND r1.id < r2.id
        AND r1.start_at < r2.end_at
        AND r1.end_at > r2.start_at;
