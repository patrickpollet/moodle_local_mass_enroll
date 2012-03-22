<?php
$string['mass_enroll'] = 'Inscriptions massives';
$string['mass_enroll_info'] = <<<EOS
Avec cette option vous allez pouvoir inscrire massivement à votre cours une liste d utilisateurs existants dans Moodle
contenue dans un fichier que vous avez préparé, un compte par ligne (les lignes vides, ou celles
contenant un identifiant de compte inconnu sont ignorées). <br/>
Ce fichier peut contenir une ou deux colonnes, séparées alors par une virgule, ou point-virgule ou une tabulation. <br/>
<b>La première doit contenir un identifiant unique : N° étudiant (idnumber Moodle), login ou email  </b> de l'utilisateur concerné. <br/>
La seconde, <b>si elle est présente, </b> indique le groupe (au sens de ce cours Moodle) ou vous voulez inscrire cet utilisateur. <br/>
Vous pouvez répeter l'opération plusieurs fois sans dommages, par exemple si vous avez oublié le groupe ou inscrire les utilisateurs.
EOS;
$string['enroll'] = 'Les inscrire à mon cours';
$string['mailreport'] = 'M\'envoyer un rapport par mail';
$string['creategroups'] = 'Créer le(s) groupe(s) si nécessaire';
$string['creategroupings'] = 'Créer le(s) groupement(s) si nécessaire';
$string['firstcolumn'] = 'La première colonne contient';
$string['roleassign'] = 'Inscrire comme';
$string['idnumber'] = 'Numéro INSA';
$string['username'] = 'Login';
$string['mail_enrolment_subject'] = 'Inscriptions massives sur {$a}';
$string['mail_enrolment']='
Bonjour,
Vous venez d\'inscrire la liste d\'utilisateurs suivants à votre cours \'$a->course\'.
Voici un rapport des opérations :
$a->report
Cordialement.
';
$string['email_sent'] = 'email envoyé à {$a}';
$string['im:opening_file'] = 'Ouverture du fichier : {$a} ';
$string['im:user_unknown'] = '{$a}  inconnu - ligne ignorée';
$string['im:already_in'] = '{$a} DÉJA inscrit ';
$string['im:enrolled_ok'] = '{$a} inscrit ';
$string['im:error_in'] = 'erreur en inscrivant {$a}';
$string['im:error_addg'] = 'erreur en ajoutant le groupe {$a->groupe}  au cours {$a->courseid} ';
$string['im:error_g_unknown'] = 'erreur groupe {$a} inconnu';
$string['im:error_add_grp'] = 'erreur en ajoutant le groupement {$a->groupe} au cours {$a->courseid}';
$string['im:error_add_g_grp'] = 'erreur en ajoutant le groupe {$a->groupe} au groupement {$a->groupe}';
$string['im:and_added_g'] = ' et ajouté au groupe Moodle {$a}';
$string['im:error_adding_u_g'] = 'impossible d\'ajouter au groupe  {$a}';
$string['im:already_in_g'] = ' DEJA dans le groupe {$a}';
$string['im:stats_i'] = '{$a} inscrits';
$string['im:stats_g'] = '{$a->nb} groupe(s) créé(s) : {$a->what}';
$string['im:stats_grp'] = '{$a->nb} groupement(s) créé(s) : {$a->what}';
$string['im:err_opening_file'] = 'ERREUR en ouvrant le fichier {$a}';
?>