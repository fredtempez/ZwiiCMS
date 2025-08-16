<?php

/**
 * Ce fichier fait partie de Zwii.
 *
 * Pour les informations complètes sur les droits d'auteur et la licence, veuillez consulter le fichier LICENSE
 * qui a été distribué avec ce code source.
 *
 * @author Michel Tuboeuf <divers@lemimi.fr>
 * @copyright Copyright (C) 2018-2025, Michel Tuboeuf
 * @license CC Attribution-NonCommercial-NoDerivatives 4.0 International
 * @link https://zwiicms.fr/
 */

/**
 * La classe journal étend la classe common et gère les fonctionnalités liées au journal des éditions.
 */
class journal extends common
{
    /**
     * Actions disponibles et les groupes autorisés à les exécuter.
     * @var array
     */
    public static $actions = [
        'index' => self::GROUP_VISITOR, // Accessible par les visiteurs
        'config' => self::GROUP_ADMIN   // Accessible uniquement par les administrateurs
    ];

    /**
     * Répertoires utilisés par le module.
     * @var array
     */
    public static $directories = [];

    /**
     * Version du module.
     * @const string
     */
    const VERSION = '2.03';

    /**
     * Nom réel du module.
     * @const string
     */
    const REALNAME = 'Journal des éditions';

    /**
     * Indique si le module peut être supprimé.
     * @const bool
     */
    const DELETE = true;

    /**
     * Version minimale requise pour la mise à jour.
     * @const string
     */
    const UPDATE = '0.0';

    /**
     * Méthode pour afficher l'index du journal.
     * @return void
     */
    public function index()
    {

        
        $this->addOutput([
            'showBarEditButton' => true, // Affiche le bouton d'édition dans la barre
            'showPageContent' => true, // Affiche le contenu de la page
            'title' => 'Journal des éditions', // Titre de la page
            'view' => 'index' // Vue à afficher
        ]);
    }

    /**
     * Méthode pour configurer le journal.
     * @return void
     */
    public function config()
    {
        $logPath = self::DATA_DIR . 'journal.log'; // Chemin vers le fichier journal.log

        // Vérifie si la requête est de type POST
        if ($this->isPost()) {
            $action = $this->getInput('action');

            // Si l'action est de réinitialiser le journal
            if ($action === 'resetLog') {
                if (file_exists($logPath)) {
                    file_put_contents($logPath, ''); // Vide le fichier journal.log
                    $notification = 'Fichier journal.log vidé';
                } else {
                    $notification = 'Fichier journal.log introuvable';
                }
            } else {
                // Récupère les filtres sélectionnés
                $filtres = [];
                if ($this->getInput('journalFiltresAdmin')) $filtres[] = 'admin';
                if ($this->getInput('journalFiltresVisitor')) $filtres[] = 'visitor';
                if ($this->getInput('journalFiltresTous')) $filtres[] = 'tous';

                // Si aucun filtre n'est sélectionné, tous les filtres sont appliqués par défaut
                if (empty($filtres)) $filtres = ['admin', 'visitor', 'tous'];

                // Enregistre la configuration
                $this->setData(['module', $this->getUrl(0), [
                    'identifiant' => $this->getInput('journalIdentifiant'),
                    'limite' => (int) $this->getInput('journalLimite'),
                    'filtres' => $filtres
                ]]);

                $notification = 'Configuration enregistrée';
            }

            // Redirige vers la page de configuration avec une notification
            $this->addOutput([
                'redirect' => helper::baseUrl() . 'journal/config',
                'notification' => $notification,
                'state' => true
            ]);
        }

        // Récupère les filtres actuels ou utilise les valeurs par défaut
        $filtres = $this->getData(['module', $this->getUrl(0), 'filtres']) ?? ['admin', 'visitor', 'tous'];

        // Affiche la vue de configuration
        $this->addOutput([
            'title' => 'Configuration du journal',
            'view' => 'config',
            'filtres' => $filtres
        ]);
    }
}
