<?php
//
// Param�tres de l'application
//
/* Comme le fichier des param�tres doit �tre (normalement)
 * appel� par tous les scripts, nous pouvons y placer toutes
 * les initialisations qui devraient �tre faites.
 * Nous pourrions y mettre ob_start et session_start() par
 * exemple. Elles n'y sont pas pour que vous ne perdiez pas
 * de vue la bufferisation des sorties.
 */
//ob_start();
//session_start();

/* Une des fa�ons les plus simples de d�finir des param�tres
 * est de d�finir des constantes car elles sont "superglobales"
 */

// Phase de d�veloppement (TRUE) ou de production (FALSE)
// Permet d'afficher des messages de d�buggage (TRUE)
define('IS_DEV', TRUE);

// Param�tres base de donn�es
define('BD_SERVEUR', 'localhost');
define('BD_USER', 'tuto_user');
define('BD_PASS', 'tuto_pass');
define('BD_NOM', 'php_tuto');
?>