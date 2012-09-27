<?php
$string['pluginname'] = 'Inscriptions massives';

$string['mass_enroll'] = 'Inscriptions massives';
$string['mass_enroll_info'] = <<<EOS
Avec cette option vous allez pouvoir inscrire Massivement à votre cours une liste d utilisateurs existants dans Moodle
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
$string['idnumber'] = 'Numéro d\'étudiant';
$string['username'] = 'Login';
$string['mail_enrolment_subject'] = 'Inscriptions massives sur {$a}';
$string['mail_enrolment']='
Bonjour,
Vous venez d\'inscrire la liste d\'utilisateurs suivants à votre cours \'{$a->course}\'.
Voici un rapport des opérations :
{$a->report}
Cordialement.
';
$string['email_sent'] = 'email envoyé à {$a}';
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

$string['mass_enroll_help']= <<<EOS

<h1>Inscriptions massives</h1>


<p>Avec cette option vous allez pouvoir inscrire massivement à votre cours une liste d utilisateurs existant dans Moodle
contenue dans un fichier que vous avez préparé, un utilisateur par ligne (les lignes vides, ou celles
contenant un compte inconnu sont ignorées). </p>

<p>
Ce fichier peut contenir <b>une ou deux colonnes</b>, séparées alors par une virgule, ou point-virgule ou une tabulation.
Il peut être préparé à partir de vos listes de Scolarité par une simple remise en forme et un export CSV depuis votre tableur favori (*)</p>

<p>
<b>La première colonne doit contenir un identifiant unique de l étudiant concerné</b>, par défaut son numéro interne (idnumber),
mais vous pouvez choisir aussi une liste d'adresses email ou de logins (**). </p>

<p>
La seconde, <b>si elle est présente, </b> indique le groupe (au sens de ce cours Moodle) ou vous voulez inscrire cet étudiant. <br/>
Si ce groupe n existe pas déja dans votre cours, il sera automatiquement créé, ainsi qu'un le groupement homonyme correspondant.<br/>
En effet sous Moodle vous pouvez restreindre toute activité à un groupement (un groupe de groupes) mais pas à un groupe.<br/>

Il est tout à fait possible dans un même fichier d'avoir des groupes différents (ou pas de groupes) dans certaines lignes. <br/>



Vous pouvez décocher ces options de création automatique  si le groupe/groupement visé existe déjé ;
</p>

<p>
Par défaut cette liste est censée contenir des étudiants à inscrire, mais vous pouvez aussi spécifier qu'ils auront
le rôle enseignant ou enseignant non éditeur.
</p>

<p>
Vous pouvez répeter l'opération plusieurs fois sans dommages, par exemple si vous avez oublié le groupe ou inscrire les étudiants
ou si vous l'avez mal orthographié.
</p>

<h2> Exemples de fichiers </h2>

des numéros  INSA et un groupe a créer si nécessaire dans le cours (*)
<pre>
" 2513110";" 4GEN"
" 2512334";" 4GEN"
" 2314149";" 4GEN"
" 2514854";" 4GEN"
" 2734431";" 4GEN"
" 2514934";" 4GEN"
" 2631955";" 4GEN"
" 2512459";" 4GEN"
" 2510841";" 4GEN"
</pre>

juste des numéros INSA (**)
<pre>
2513110
2512334
2314149
2514854
2734431
2514934
2631955
</pre>

juste des emails (**)
<pre>
toto@insa-lyon.fr
titi@]insa-lyon.fr
tutu@insa-lyon.fr
</pre>

des logins et des groupes (separés içi par une tabulation) :

<pre>
ppollet      groupe_de_test              sera dans ce groupe
codet        groupe_de_test              lui aussi
astorck      autre_groupe                et lui dans l'autre groupe
yjayet                                    n'a pas de groupe proposé
                                          ligne vide ignorée
inconnu                                   existe pas ligne ignorée
</pre>

<p>
<span <font color='red'>(*) </font></span>: les éventuelles apostrophes ou espaces ajoutées lors de l'export CSV depuis votre tableur favori seront écartés.
</p>

<p>
<span <font color='red'>(**) </font></span>: les comptes visés doivent exister dans Moodle, ce qui est normalement le cas après la synchronisation qui s'effectue chaque nuit
avec l'annuaire  LDAP de l'établissement.
</p>


EOS;

?>
