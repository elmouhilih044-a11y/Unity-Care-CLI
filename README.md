🏥 Unity Care Clinic - CLI Version
📋 Description
Unity Care Clinic CLI est une application console orientée objet développée en PHP 8 pour la gestion interne d'une clinique médicale. Ce système permet de gérer efficacement les patients, les médecins et les départements via une interface en ligne de commande interactive.
✨ Fonctionnalités principales

Gestion des Patients : Créer, lister, rechercher, modifier et supprimer des patients
Gestion des Médecins : Gérer le personnel médical avec informations détaillées
Gestion des Départements : Administrer les différents services de la clinique
Statistiques : Consulter des analyses en temps réel (âge moyen, ancienneté, répartition)
Validation des données : Vérification automatique des entrées utilisateur
Affichage ASCII : Tables formatées pour une lecture claire des données

🛠️ Technologies utilisées

PHP 8.x (Programmation orientée objet)
MySQL (Base de données)
MySQLi (Connexion OOP à la base de données)

📁 Structure du projet
unity-care-cli/
│
├── src/
│   ├── Models/
│   │   ├── BaseModel.php
│   │   ├── Personne.php
│   │   ├── Patient.php
│   │   ├── Doctor.php
│   │   └── Department.php
│   │
│   ├── Database/
│   │   └── Database.php (Singleton)
│   │
│   ├── Utils/
│   │   ├── Validator.php
│   │   └── ConsoleTable.php
│   │
│   └── Interfaces/
│       └── Displayable.php
│
├── config/
│   └── config.php
│
├── database/
│   └── schema.sql
│
├── index.php (Point d'entrée)
├── README.md
└── .gitignore

🎯 Utilisation
Au lancement, le menu principal s'affiche avec 5 options pour gérer les patients, médecins, départements, consulter les statistiques ou quitter. Chaque section propose un sous-menu avec les opérations CRUD complètes. Les données sont affichées dans des tableaux ASCII clairs et formatés.
🏗️ Architecture OOP
Le projet utilise une architecture orientée objet moderne avec BaseModel comme classe parente abstraite, Personne comme classe mère pour Patient et Doctor, et l'interface Displayable pour uniformiser l'affichage. Le pattern Singleton gère la connexion à la base de données, tandis que la classe Validator fournit des méthodes statiques pour valider les emails, téléphones, dates et nettoyer les entrées utilisateur.
📊 Statistiques
L'application calcule automatiquement l'âge moyen des patients, l'ancienneté moyenne des médecins, identifie le département le plus peuplé et affiche la répartition des patients par département.
🔒 Sécurité
Toutes les requêtes utilisent des prepared statements pour prévenir les injections SQL. Les données sont validées et nettoyées avant traitement, les erreurs sont gérées avec try/catch, et la configuration est externalisée pour plus de sécurité.
Date de création : 22/12/2025
