<?php
header("Content-type: text/css; charset: UTF-8");
?>

/* Styles généraux */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    color: #333;
}

/* Conteneur principal pour la page */
.container {
    width: 80%;
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Lien vers le dossier partagé */
.btn-blue {
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-weight: bold;
    display: inline-block;
    transition: background-color 0.3s ease, transform 0.3s ease;
    margin-bottom: 20px;
}

.btn-blue:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

/* Styles pour les boutons */
button {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

button:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

button.btn-red {
    background-color: #dc3545;
}

button.btn-red:hover {
    background-color: #c82333;
}

button.btn-green {
    background-color: #28a745;
}

button.btn-green:hover {
    background-color: #218838;
}

/* Formulaires */
form {
    margin-bottom: 20px;
}

input[type="file"],
input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

input[type="text"]:focus,
input[type="password"]:focus {
    border-color: #007bff;
    outline: none;
}

/* Barre de recherche */
form input[type="text"] {
    display: inline-block;
    width: calc(100% - 120px);
    margin-right: 10px;
}

/* Liste des fichiers */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th,
table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}

table th {
    background-color: #007bff;
    color: #fff;
}

table td a {
    color: #007bff;
    text-decoration: none;
}

table td a:hover {
    text-decoration: underline;
}

/* Messages d'alerte */
.popup-message {
    padding: 15px;
    background-color: #f8d7da;
    color: #721c24;
    border-radius: 5px;
    margin-bottom: 20px;
    border: 1px solid #f5c6cb;
    display: inline-block;
}

/* Animation de chargement */
@keyframes loading {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

button:disabled {
    background-color: #ddd;
    cursor: not-allowed;
    animation: loading 1s infinite;
}
