# api_symfony

📘 Documentation API Symfony - Authentification JWT
Ce projet est une API REST sécurisée basée sur Symfony 7.2. Elle utilise LexikJWTAuthenticationBundle pour gérer l'authentification par token (Stateless).

🛠 Stack Technique
Framework : Symfony 7.2

Langage : PHP 8.2+

Base de données : MySQL 8.0.40 (via MAMP)

Sécurité : JWT (JSON Web Token) via lexik/jwt-authentication-bundle

⚙️ Configuration Spécifique (MAMP)
Pour assurer la compatibilité entre Doctrine et le serveur MySQL de MAMP, la version du serveur doit être explicitement définie dans le fichier .env.

Fichier .env :

# Exemple pour MAMP avec MySQL 8.0.40 (sans suffixe MariaDB)

DATABASE_URL="mysql://root:root@127.0.0.1:8889/api_symfony?serverVersion=8.0.40&charset=utf8mb4"

🔐 Guide d'Authentification & Tests
Le système repose sur l'échange de jetons. L'utilisateur s'identifie une fois, reçoit un token, et doit fournir ce token pour chaque requête suivante.

1. Création d'un utilisateur (Backend)

Pour l'instant, l'inscription se fait manuellement en base de données. Le mot de passe ne doit jamais être stocké en clair.

    Générer le hash du mot de passe via la commande Symfony :

    php bin/console security:hash-password

    Insérer l'utilisateur via SQL :

    INSERT INTO user (email, roles, password)
    VALUES ('user@gmail.com', '["ROLE_USER"]', '$2y$13$...'); -- Coller le hash ici

2. Connexion (Récupération du Token)

Cette route est publique (PUBLIC_ACCESS). Elle permet d'échanger ses identifiants contre un JWT.

Méthode : POST

Endpoint : /api/login

Format : JSON

Commande de test (cURL) :
curl -X POST http://127.0.0.1:8000/api/login \
-H "Content-Type: application/json" \
-d '{"email": "user@gmail.com", "password": "Azerty"}'

Réponse (Succès 200) :

{
"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3Nj..."
}

3. Accès à une route protégée (Utilisation du Token)

Une fois le token obtenu, il doit être envoyé dans les en-têtes (Headers) de la requête pour accéder aux routes sécurisées (commençant par /api).

Méthode : GET

Endpoint : /api/me (Renvoie les infos de l'utilisateur connecté)

Header Obligatoire : Authorization: Bearer <VOTRE_TOKEN>

Commande de test (cURL) : (Remplacer la longue chaîne par le token obtenu à l'étape précédente)

curl -X GET http://127.0.0.1:8000/api/me \
-H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."

Réponse (Succès 200) :
{
"message": "Connexion réussie !",
"email": "user@gmail.com",
"roles": [
"ROLE_USER"
]
}

Réponse (Erreur 401 - Token invalide ou absent) :

{
"code": 401,
"message": "JWT Token not found"
}

📂 Structure des fichiers clés
s
config/packages/security.yaml : Configure les firewalls. Définit que /api/login est géré par json_login et que le reste de /api est protégé par jwt.

src/Entity/User.php : L'entité qui représente l'utilisateur en base de données.

src/Controller/MeController.php : Contrôleur de test pour vérifier que le token est bien décodé et que l'utilisateur est reconnu.
