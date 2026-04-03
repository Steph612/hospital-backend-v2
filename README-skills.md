# 🎯 10 questions probables + réponses optimisées
**#classées par sujet :** Symfony ✔ Architecture ✔ API ✔ Base de données ✔ Bonnes pratiques ✔

# 🧩 Symfony

## 1. “C’est quoi le cycle de vie d’une requête Symfony ?”
**✅ Réponse optimale :**
    “Une requête arrive via le front controller (index.php), puis elle est transformée en objet Request.
    Le Kernel déclenche ensuite une série d’événements (kernel.request, controller, etc.),
    le routeur détermine le contrôleur à appeler, puis le contrôleur retourne une Response.
    Enfin, la réponse est envoyée au client.”

**👉 Bonus si tu ajoutes :** “Ce mécanisme est basé sur un système d’événements.”

## 2. “Pourquoi utiliser Symfony Messenger ?”
**✅ Réponse optimale :**
    “Messenger permet de découpler l’exécution d’une action.
    On peut traiter certaines opérations de manière asynchrone (emails, logs, intégrations),
    ce qui améliore les performances et la résilience de l’application.”

**👉 Bonus :** “Ça permet aussi de gérer des retries et des files d’attente.”

# 🏗️ Architecture

## 3. “Quelle différence entre une Entity et un DTO ?”
**✅ Réponse optimale :**
    “Une Entity représente un objet métier persistant, lié à la base de données via Doctrine.
    Un DTO est un objet de transfert de données, souvent utilisé pour découpler les couches ou structurer les entrées/sorties d’une API, sans logique métier.”

**👉 Point fort :** Entity = persistence, DTO = transport

## 4. “Pourquoi séparer Application / Domain / Infrastructure ?”
**✅ Réponse optimale :**
    “Pour isoler la logique métier du reste.
    Le Domain contient les règles métier, l’Application orchestre les cas d’usage, et l’Infrastructure gère les détails techniques (BDD, framework).
    Cela améliore la testabilité, la maintenabilité et la clarté du code.”

## 5. “C’est quoi un handler dans ton projet ?”
**✅ Réponse optimale :**
    “Un handler représente un cas d’usage.
    Il reçoit une commande ou une requête, applique la logique métier via le domaine, et coordonne les actions nécessaires.
    Ça permet d’avoir une logique claire et isolée par action.”

# 🌐 API

## 6. “C’est quoi la différence entre synchrone et asynchrone ?”
**✅ Réponse optimale :**
    “En synchrone, le traitement est exécuté immédiatement et bloque la réponse.
    En asynchrone, on délègue le traitement à un système externe (queue/worker), ce qui permet de répondre plus rapidement et de traiter les tâches en arrière-plan.”

## 7. “Quelle différence entre GET et POST ?”
**✅ Réponse optimale :**
    “GET est utilisé pour récupérer des données et doit être idempotent, sans effet de bord.
    POST est utilisé pour créer une ressource et peut modifier l’état du système.”

**👉 Bonus :** “GET peut être mis en cache, contrairement à POST.”

# 🗄️ Base de données

## 8. “C’est quoi une relation OneToMany ?”
**✅ Réponse optimale :**
    “C’est une relation où une entité peut être liée à plusieurs autres.
    Par exemple, un patient peut avoir plusieurs admissions, mais une admission appartient à un seul patient.”

👉 Clair, concret, parfait.

## 9. “Pourquoi utiliser des index en base de données ?”
**✅ Réponse optimale :**
    “Les index permettent d’accélérer les recherches en base de données, notamment sur les colonnes fréquemment utilisées dans les requêtes.
    En contrepartie, ils peuvent ralentir les opérations d’écriture.”

**👉 Très important :** montrer le compromis.

## 🧠 Bonnes pratiques

## 10. “Comment sécuriser un email ?”
**✅ Réponse optimale :**
    “Validation du format, contrainte d’unicité en base, et souvent une vérification via un token (double opt-in).
    On peut aussi limiter les tentatives pour éviter l’énumération d’emails.”

👉 Là tu corriges ton point faible 👌

# 🎯 50 questions 
**✅ réponses calibrées oral jury :** claires, précises, sans blabla

**🧠 réponses “niveau expert oral” :** structurées, précises, avec juste assez de profondeur pour impressionner sans être verbeux.

# 🧩 SYMFONY (10)
## 1. “C’est quoi un service dans Symfony ?”
**✅ calibrées :** “Un service est un objet géré par le conteneur d’injection de dépendances.
Il est instancié automatiquement et injecté là où on en a besoin, ce qui évite le couplage fort.”

**🧠 expert :** “Un service est un objet métier ou technique géré par le conteneur d’injection de dépendances.
Il est instancié automatiquement et injecté là où nécessaire, ce qui permet de réduire le couplage et d’améliorer la testabilité.”

## 2. “C’est quoi l’injection de dépendances ?”
**✅ calibrées :** “C’est le fait de fournir les dépendances d’une classe depuis l’extérieur plutôt que de les créer elle-même, ce qui améliore la testabilité et le découplage.”

**🧠 expert :** “C’est un principe qui consiste à fournir les dépendances d’une classe depuis l’extérieur plutôt que de les instancier elle-même, ce qui favorise le découplage et facilite les tests unitaires.”

## 3. “C’est quoi le container Symfony ?”
**✅ calibrées :** “C’est un conteneur qui gère l’instanciation et la configuration des services, ainsi que leurs dépendances.”

**🧠 expert :** “Le container Symfony est un registre central qui gère la création, la configuration et le cycle de vie des services ainsi que leurs dépendances.”

## 4. “C’est quoi un Event Subscriber ?”
**✅ calibrées :** “C’est une classe qui écoute des événements du Kernel et réagit à certains moments du cycle de requête.”

**🧠 expert :** “C’est une classe qui s’abonne à des événements du Kernel et permet d’exécuter du code à des moments précis du cycle de requête, comme avant le contrôleur ou après la réponse.”

## 5. “Différence entre Event Listener et Subscriber ?”
**✅ calibrées :** “Le Subscriber déclare lui-même les événements qu’il écoute, ce qui est plus structuré que le Listener.”

**🧠 expert :** “Un Listener est attaché à un événement spécifique, tandis qu’un Subscriber déclare lui-même les événements qu’il écoute, ce qui est plus structuré et maintenable.”

## 6. “C’est quoi une commande Symfony ?”
**✅ calibrées :** “Une commande permet d’exécuter des tâches en ligne de commande, souvent pour des traitements batch ou maintenance.”

**🧠 expert :** “Une commande permet d’exécuter des traitements en ligne de commande, souvent utilisés pour des tâches batch, des imports ou des opérations de maintenance.”

## 7. “C’est quoi un Bundle ?”
**✅ calibrées :** “C’est un module réutilisable dans Symfony, qui regroupe du code et des fonctionnalités.”

**🧠 expert :** “Un bundle est un module réutilisable qui encapsule une fonctionnalité complète dans Symfony, bien que les bonnes pratiques actuelles encouragent une architecture plus découplée sans dépendre fortement des bundles.”

## 8. “C’est quoi une route ?”
**✅ calibrées :** “Une route associe une URL à un contrôleur et éventuellement des paramètres.”

**🧠 expert :** “Une route associe une URL à un contrôleur et permet de définir les paramètres et contraintes d’accès à cette ressource.”

## 9. “C’est quoi un contrôleur ?”
**✅ calibrées :** “C’est une classe qui reçoit une requête HTTP et retourne une réponse.”

**🧠 expert :** “Le contrôleur reçoit la requête HTTP, délègue la logique métier à des services ou handlers, puis retourne une réponse, idéalement en restant le plus léger possible.”

## 10. “Pourquoi utiliser Doctrine ORM ?”
**✅ calibrées :** “Pour mapper des objets PHP à des tables en base de données, ce qui permet de manipuler les données de manière orientée objet.”

**🧠 expert :** “Doctrine est un ORM qui permet de mapper des objets PHP à des tables en base de données, facilitant la manipulation des données via une approche orientée objet tout en gérant la persistance.”

# 🏗️ ARCHITECTURE (10)
## 11. “C’est quoi le DDD ?”
**✅ calibrées :** “C’est une approche qui consiste à structurer le code autour du domaine métier, en mettant les règles métier au centre.”

**🧠 expert :** “Le Domain-Driven Design est une approche qui place le domaine métier au centre du code, en structurant l’application autour des règles métier plutôt que des contraintes techniques.”

## 12. “C’est quoi une couche Domain ?”
**✅ calibrées :** “Elle contient les règles métier pures, indépendantes de toute technologie.”

**🧠 expert :** “La couche Domain contient les règles métier pures, indépendantes de toute technologie, ce qui la rend hautement testable et stable.”

## 13. “C’est quoi une couche Application ?”
**✅ calibrées :** “Elle orchestre les cas d’usage et coordonne les interactions entre le domaine et l’infrastructure.”

**🧠 expert :** “La couche Application orchestre les cas d’usage en coordonnant les interactions entre le domaine et l’infrastructure, sans contenir de logique métier complexe.”

## 14. “C’est quoi l’infrastructure ?”
**✅ calibrées :** “Elle contient les détails techniques comme la base de données, les APIs externes ou le framework.”

**🧠 expert :** “Elle gère les détails techniques comme la base de données, les APIs externes ou le framework, et implémente les interfaces définies par le domaine.”

## 15. “Pourquoi éviter de mettre de la logique dans le contrôleur ?”
**✅ calibrées :** “Parce que le contrôleur doit rester léger et déléguer la logique métier pour être testable et maintenable.”

**🧠 expert :** “Mettre de la logique dans le contrôleur crée un couplage fort avec le framework et rend le code difficile à tester et maintenir, d’où l’intérêt de déléguer aux services ou handlers.”

## 16. “C’est quoi un use case ?”
**✅ calibrées :** “C’est une action métier spécifique, comme créer un utilisateur ou valider une inscription.”

**🧠 expert :** “Un use case représente une action métier précise, comme créer une commande, et est généralement implémenté via un handler ou un service applicatif.”

## 17. “C’est quoi le couplage ?”
**✅ calibrées :** “C’est le degré de dépendance entre les composants. On cherche à le réduire.”

**🧠 expert :** “Le couplage mesure le niveau de dépendance entre composants. Un faible couplage améliore la maintenabilité et la flexibilité du système.”

## 18. “C’est quoi la cohésion ?”
**✅ calibrées :** “C’est le fait qu’une classe ait une responsabilité claire et unique.”

**🧠 expert :** “La cohésion correspond au fait qu’une classe ou un module ait une responsabilité claire et bien définie, ce qui améliore la lisibilité et la qualité du code.”

## 19. “C’est quoi SOLID ?”
**✅ calibrées :** “Un ensemble de principes pour concevoir un code maintenable et extensible.”

**🧠 expert :** “SOLID est un ensemble de principes de conception orientée objet qui visent à produire un code maintenable, extensible et découplé.”

## 20. “Pourquoi utiliser des interfaces ?”
**✅ calibrées :** “Pour découpler l’implémentation de l’abstraction et faciliter les tests.”

**🧠 expert :** “Les interfaces permettent de définir des contrats sans implémentation, ce qui facilite le découplage, les tests et le remplacement des implémentations.”

# 🌐 API (10)
## 21. “C’est quoi une API REST ?”
**✅ calibrées :** “Une API basée sur HTTP qui expose des ressources manipulables via des méthodes standard comme GET, POST, PUT, DELETE.”

**🧠 expert :** “Une API REST expose des ressources via HTTP en utilisant des méthodes standard comme GET, POST, PUT ou DELETE, avec une communication stateless.”

## 22. “C’est quoi une ressource ?”
**✅ calibrées :** “Un objet manipulable via l’API, comme un utilisateur ou une commande.”

**🧠 expert :** “Une ressource est une entité accessible via une API, comme un utilisateur ou une commande, identifiée par une URL.”

## 23. “C’est quoi un code 200 ?”
**✅ calibrées :** “Succès de la requête.”

**🧠 expert :** “Indique que la requête a été traitée avec succès.”

## 24. “C’est quoi un code 201 ?”
**✅ calibrées :** “Création réussie d’une ressource.”

**🧠 expert :** “Indique qu’une ressource a été créée avec succès, généralement après un POST.”

## 25. “C’est quoi un code 400 ?”
**✅ calibrées :** “Erreur côté client, requête invalide.”

**🧠 expert :** “Erreur côté client, généralement due à une requête mal formée ou invalide.”

## 26. “C’est quoi un code 401 ?”
**✅ calibrées :** “Non authentifié.”

**🧠 expert :** “Indique que l’utilisateur n’est pas authentifié.”

## 27. “C’est quoi un code 403 ?”
**✅ calibrées :** “Accès refusé.”

**🧠 expert :** “Indique que l’accès est refusé malgré une authentification valide.”

## 28. “C’est quoi un code 404 ?”
**✅ calibrées :** “Ressource non trouvée.”

**🧠 expert :** “Indique que la ressource demandée n’existe pas.”

## 29. “C’est quoi un code 500 ?”
**✅ calibrées :** “Erreur serveur.”

**🧠 expert :** “Erreur interne du serveur, généralement inattendue.”

## 30. “C’est quoi l’idempotence ?”
**✅ calibrées :** “Une opération qui peut être répétée sans changer le résultat.”

**🧠 expert :** “Une opération est idempotente si elle peut être répétée sans modifier le résultat, comme un GET ou un PUT bien conçu.”

# 🗄️ BASE DE DONNÉES (10)
## 31. “C’est quoi une clé primaire ?”
**✅ calibrées :** “Un identifiant unique pour chaque ligne d’une table.”

**🧠 expert :** “Une clé primaire identifie de manière unique chaque ligne d’une table.”

## 32. “C’est quoi une clé étrangère ?”
**✅ calibrées :** “Une référence à une clé primaire d’une autre table.”

**🧠 expert :** “Une clé étrangère établit une relation entre deux tables en référant une clé primaire.”

## 33. “C’est quoi une relation ManyToMany ?”
**✅ calibrées :** “Une relation où plusieurs entités sont liées entre elles via une table intermédiaire.”

**🧠 expert :** “C’est une relation où plusieurs entités sont liées entre elles via une table intermédiaire.”

## 34. “C’est quoi la normalisation ?”
**✅ calibrées :** “Le fait d’organiser les données pour éviter les redondances.”

**🧠 expert :** “La normalisation consiste à structurer les données pour éviter les redondances et améliorer l’intégrité.”

## 35. “C’est quoi une contrainte unique ?”
**✅ calibrées :** “Une règle qui empêche les doublons sur une colonne.”

**🧠 expert :** “Elle garantit qu’une valeur n’apparaît qu’une seule fois dans une colonne.”

## 36. “C’est quoi un index ?”
**✅ calibrées :** “Une structure qui accélère les recherches.”

**🧠 expert :** “Un index permet d’accélérer les recherches en base de données au prix d’un coût supplémentaire en écriture.”

## 37. “C’est quoi une transaction ?”
**✅ calibrées :** “Un ensemble d’opérations exécutées de manière atomique.”

**🧠 expert :** “Une transaction regroupe plusieurs opérations exécutées de manière atomique, avec rollback possible en cas d’erreur.”

## 38. “C’est quoi ACID ?”
**💡 ACID est un acronyme signifiant :** **A**tomicité, **C**ohérence, **I**solation, **D**urabilité.

**✅ calibrées :** “Un ensemble de propriétés garantissant la fiabilité des transactions.”

**🧠 expert :** “ACID garantit la fiabilité des transactions avec atomicité, cohérence, isolation et durabilité.”

## 39. “C’est quoi un JOIN ?”
**✅ calibrées :** “Une opération pour récupérer des données liées entre plusieurs tables.”

**🧠 expert :** “Un JOIN permet de combiner des données provenant de plusieurs tables selon une relation.”

## 40. “Différence entre INNER JOIN et LEFT JOIN ?”
**✅ calibrées :** “INNER JOIN retourne uniquement les correspondances, LEFT JOIN inclut aussi les éléments sans correspondance.”

**🧠 expert :** “INNER JOIN retourne uniquement les correspondances, tandis que LEFT JOIN inclut aussi les lignes sans correspondance.”

# 🧠 BONNES PRATIQUES (10)
## 41. “Pourquoi écrire du code testable ?”
**✅ calibrées :** “Pour garantir le bon fonctionnement et faciliter les évolutions.”

**🧠 expert :** “Un code testable permet de valider le comportement, de sécuriser les évolutions et de réduire les régressions.”

## 42. “C’est quoi un test unitaire ?”
**✅ calibrées :** “Un test qui vérifie une unité de code isolée.”

**🧠 expert :** “Un test unitaire vérifie une unité de code isolée, généralement une méthode ou une classe.”

## 43. “C’est quoi un test d’intégration ?”
**✅ calibrées :** “Un test qui vérifie plusieurs composants ensemble.”

**🧠 expert :** “Il vérifie que plusieurs composants fonctionnent correctement ensemble.”

## 44. “Pourquoi éviter le code dupliqué ?”
**✅ calibrées :** “Parce qu’il augmente le risque d’erreur et la maintenance.”

**🧠 expert :** “Le code dupliqué augmente la complexité et le risque d’erreur, d’où l’importance de le factoriser.”

## 45. “C’est quoi DRY ?”
**✅ calibrées :** “**D**on’t **R**epeat **Y**ourself : éviter la duplication.”

**🧠 expert :** “Principe consistant à éviter la duplication de logique dans le code.”

## 46. “C’est quoi KISS ?”
**✅ calibrées :** “Keep It Simple : privilégier la simplicité.”

**🧠 expert :** Encourage à privilégier des solutions simples et lisibles.”

## 47. “C’est quoi YAGNI ?”
**✅ calibrées :** “**Y**ou **A**ren’t **G**onna **N**eed **I**t : ne pas anticiper inutilement.”

**🧠 expert :** “Consiste à ne pas implémenter des fonctionnalités tant qu’elles ne sont pas nécessaires.”

## 48. “Pourquoi logger ?”
**✅ calibrées :** “Pour diagnostiquer les problèmes et tracer le comportement de l’application.”

**🧠 expert :** “Permet de tracer le comportement de l’application et d’analyser les erreurs.”

## 49. “Pourquoi valider les données ?”
**✅ calibrées :** “Pour éviter les erreurs et garantir l’intégrité des données.”

**🧠 expert :** “Permet de garantir l’intégrité des données avant traitement ou persistance.”

## 50. “Pourquoi séparer lecture et écriture (CQRS) ?”
**✅ calibrées :** “Pour optimiser les performances et clarifier les responsabilités.”

**🧠 expert :** “Sépare les opérations de lecture et d’écriture pour améliorer la clarté, la performance et la scalabilité.”
