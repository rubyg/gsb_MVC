<?php

include("vues/v_sommaireC.php");


$action = $_REQUEST['action'];
//$idComptable = $_SESSION['idComptable'];


switch ($action) {
    case 'voirVisiteur': {
            $lesVisiteurs = $pdo->getLesVisiteurs();
            $Key = array_keys($lesVisiteurs);
            $selectionnerVisiteur = $Key[0];

            include("vues/v_voirVisiteur.php");
            break;
        }
    case 'LaFicheduVisiteur': {
            $leVisiteur = $_REQUEST['lstVisiteur'];
            $_SESSION['leVisiteur'] = $leVisiteur;
            $leMois = $_POST['lstMois'];
            $_SESSION['leMois'] = $leMois;
            
            $lesVisiteurs = $pdo->getLesVisiteurs();
            $selectionnerVisiteur = $leVisiteur;
            include("vues/v_voirVisiteur.php");
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteur, $leMois);
            $lesFraisForfait = $pdo->getLesFraisForfait($leVisiteur, $leMois);
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur, $leMois);
            $numAnnee = substr($leMois, 0, 4);
            $numMois = substr($leMois, 4, 2);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif = $lesInfosFicheFrais['dateModif'];
            $dateModif = dateAnglaisVersFrancais($dateModif);
            include("vues/v_voirFiche.php");

            break;
        }
    case 'ModifFiche': {
            $leVisiteur = $_SESSION['leVisiteur'];
            $leMois = $_SESSION['leMois'];
            $lesFrais = $_REQUEST['lesFrais'];
            $lesVisiteurs = $pdo->getLesVisiteurs();
            $selectionnerVisiteur = $leVisiteur;
            include("vues/v_voirVisiteur.php");
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteur, $leMois);
            $lesFraisForfait = $pdo->getLesFraisForfait($leVisiteur, $leMois);
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur, $leMois);
            $numAnnee = substr($leMois, 0, 4);
            $numMois = substr($leMois, 4, 2);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif = $lesInfosFicheFrais['dateModif'];
            $dateModif = dateAnglaisVersFrancais($dateModif);
            include("vues/v_voirFiche.php");
            if (lesQteFraisValides($lesFrais)) {
                $pdo->majFraisForfait($leVisiteur, $leMois, $lesFrais);

                echo ' les éléments forfaitisés on été modifiée!';
            } else {
                ajouterErreur("Les valeurs des frais doivent être numériques");
                include("vues/v_erreurs.php");
            }
            include ("vues/v_ModFicheFrais.php");
            break;
        }
    case 'supprimerFrais': {
            $idFrais = $_REQUEST['idFrais'];
            $rs = $pdo->ModifFraisHorsForfait($idFrais);

            if ($rs == 0) {
                ajouterErreur('La fiche frais a été supprimé');
                $type = 1;
                include("vues/v_erreurs.php");
            } else {
                ajouterErreur("Cette ligne a déjà été supprimé");
                include("vues/v_erreurs.php");
            }

            break;
        }
    case "reporterFrais": {

            $mois = $_SESSION['leMois'];
            $leVisiteur = $_SESSION['leVisiteur'];
            $idFrais = $_REQUEST['idFrais'];
            $rs = $pdo->reporterFrais($idFrais, $mois, $leVisiteur);
            if ($rs==0) {
                ajouterErreur("Le Frais a bien été reporter");
                $type = 1;
                include("vues/v_erreurs.php");
            } else {
                ajouterErreur("Le Frais n'a pas été reporter");
                include("vues/v_erreurs.php");
            }
            break;
        }
    case "validerFrais": {
            $leVisiteur = $_SESSION['leVisiteur'];
            $leMois = $_SESSION['leMois'];
            $nbJustificatifs = $_REQUEST['nbJustificatifs'];
            $rs = $pdo->majEtatFicheFrais($leVisiteur, $leMois, "VA", $nbJustificatifs);
            if ($rs== 0) {
                ajouterErreur('La Fiche frais a bien été validé!');
                $type = 1;
                include("vues/v_erreurs.php");
            } else {
                ajouterErreur("La Fiche frais n'a pas été validé!");
                include("vues/v_erreurs.php");
            }
            break;
        }
}
?>
