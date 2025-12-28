<?php
// Menu.php

require_once "Patient.php";
require_once "Doctor.php";
require_once "Department.php";

class Menu
{
    public function run()
    {
        while (true) {
            $this->showMainMenu();
            $choice = readline("Votre choix: ");

            switch ($choice) {
                case '1':
                    $this->patientsMenu();
                    break;
                case '2':
                    $this->doctorsMenu();
                    break;
                case '3':
                    $this->departmentsMenu();
                    break;
                case '4':
                    $this->showStats();
                    break;
                case '5':
                    echo "Au revoir!\n";
                    exit;
                default:
                    echo "Choix invalide!\n";
            }
        }
    }

    private function showMainMenu()
    {
        echo "\n=== Unity Care CLI ===\n";
        echo "1. Gerer les patients\n";
        echo "2. Gerer les medecins\n";
        echo "3. Gerer les departements\n";
        echo "4. Statistiques\n";
        echo "5. Quitter\n";
    }

    // ===== PATIENTS =====
    private function patientsMenu()
    {
        while (true) {
            echo "\n=== Gestion des Patients ===\n";
            echo "1. Lister tous les patients\n";
            echo "2. Rechercher un patient\n";
            echo "3. Ajouter un patient\n";
            echo "4. Modifier un patient\n";
            echo "5. Supprimer un patient\n";
            echo "6. Retour\n";

            $choice = readline("Votre choix: ");

            switch ($choice) {
                case '1':
                    $this->listPatients();
                    break;
                case '2':
                    $this->searchPatient();
                    break;
                case '3':
                    $this->addPatient();
                    break;
                case '4':
                    $this->updatePatient();
                    break;
                case '5':
                    $this->deletePatient();
                    break;
                case '6':
                    return;
            }
        }
    }

    private function listPatients()
    {
        $patients = Patient::getAll();
        echo "\n--- Liste des patients ---\n";
        foreach ($patients as $p) {
            echo $p . "\n";
        }
    }

    private function searchPatient()
    {
        $name = readline("Nom a rechercher: ");
        $patients = Patient::search($name);
        
        if (count($patients) > 0) {
            foreach ($patients as $p) {
                echo $p . "\n";
            }
        } else {
            echo "Aucun patient trouve.\n";
        }
    }

    private function addPatient()
    {
        echo "\n--- Ajouter un patient ---\n";
        $first_name = readline("Prenom: ");
        $last_name = readline("Nom: ");
        $email = readline("Email: ");
        $date = readline("Date naissance (YYYY-MM-DD): ");
        $sexe = readline("Sexe (Homme/Femme): ");
        $medecin_id = readline("ID medecin: ");

        $patient = new Patient($first_name, $last_name, '', $email, $date, $sexe, $medecin_id);
        
        if ($patient->save()) {
            echo "Patient ajoute avec succes!\n";
        } else {
            echo "Erreur lors de l'ajout.\n";
        }
    }

    private function updatePatient()
    {
        $id = readline("ID du patient: ");
        $patient = Patient::getById($id);

        if ($patient) {
            echo "Patient actuel: " . $patient . "\n";
            
            $email = readline("Nouvel email (Enter pour garder): ");
            if ($email != '') {
                $patient->setEmail($email);
            }

            $date = readline("Nouvelle date naissance (Enter pour garder): ");
            if ($date != '') {
                $patient->setDateNaissance($date);
            }

            if ($patient->save()) {
                echo "Patient modifie!\n";
            }
        } else {
            echo "Patient non trouve.\n";
        }
    }

    private function deletePatient()
    {
        $id = readline("ID du patient a supprimer: ");
        $patient = Patient::getById($id);

        if ($patient) {
            echo $patient . "\n";
            $confirm = readline("Confirmer (oui/non): ");
            
            if ($confirm == 'oui') {
                $patient->delete();
                echo "Patient supprime!\n";
            }
        } else {
            echo "Patient non trouve.\n";
        }
    }

    // ===== DOCTORS =====
    private function doctorsMenu()
    {
        while (true) {
            echo "\n=== Gestion des Medecins ===\n";
            echo "1. Lister tous les medecins\n";
            echo "2. Rechercher un medecin\n";
            echo "3. Ajouter un medecin\n";
            echo "4. Modifier un medecin\n";
            echo "5. Supprimer un medecin\n";
            echo "6. Retour\n";

            $choice = readline("Votre choix: ");

            switch ($choice) {
                case '1':
                    $this->listDoctors();
                    break;
                case '2':
                    $this->searchDoctor();
                    break;
                case '3':
                    $this->addDoctor();
                    break;
                case '4':
                    $this->updateDoctor();
                    break;
                case '5':
                    $this->deleteDoctor();
                    break;
                case '6':
                    return;
            }
        }
    }

    private function listDoctors()
    {
        $doctors = Doctor::getAll();
        echo "\n--- Liste des medecins ---\n";
        foreach ($doctors as $d) {
            echo $d . "\n";
        }
    }

    private function searchDoctor()
    {
        $name = readline("Nom a rechercher: ");
        $doctors = Doctor::search($name);
        
        if (count($doctors) > 0) {
            foreach ($doctors as $d) {
                echo $d . "\n";
            }
        } else {
            echo "Aucun medecin trouve.\n";
        }
    }

    private function addDoctor()
    {
        echo "\n--- Ajouter un medecin ---\n";
        $first_name = readline("Prenom: ");
        $last_name = readline("Nom: ");
        $specialite = readline("Specialite: ");
        $dept_id = readline("ID departement: ");

        $doctor = new Doctor($first_name, $last_name, '', '', $specialite, $dept_id);
        
        if ($doctor->save()) {
            echo "Medecin ajoute avec succes!\n";
        } else {
            echo "Erreur lors de l'ajout.\n";
        }
    }

    private function updateDoctor()
    {
        $id = readline("ID du medecin: ");
        $doctor = Doctor::getById($id);

        if ($doctor) {
            echo "Medecin actuel: " . $doctor . "\n";
            
            $specialite = readline("Nouvelle specialite (Enter pour garder): ");
            if ($specialite != '') {
                $doctor->setSpecialite($specialite);
            }

            if ($doctor->save()) {
                echo "Medecin modifie!\n";
            }
        } else {
            echo "Medecin non trouve.\n";
        }
    }

    private function deleteDoctor()
    {
        $id = readline("ID du medecin a supprimer: ");
        $doctor = Doctor::getById($id);

        if ($doctor) {
            echo $doctor . "\n";
            $confirm = readline("Confirmer (oui/non): ");
            
            if ($confirm == 'oui') {
                $doctor->delete();
                echo "Medecin supprime!\n";
            }
        } else {
            echo "Medecin non trouve.\n";
        }
    }

    // ===== DEPARTMENTS =====
    private function departmentsMenu()
    {
        while (true) {
            echo "\n=== Gestion des Departements ===\n";
            echo "1. Lister tous les departements\n";
            echo "2. Ajouter un departement\n";
            echo "3. Modifier un departement\n";
            echo "4. Supprimer un departement\n";
            echo "5. Retour\n";

            $choice = readline("Votre choix: ");

            switch ($choice) {
                case '1':
                    $this->listDepartments();
                    break;
                case '2':
                    $this->addDepartment();
                    break;
                case '3':
                    $this->updateDepartment();
                    break;
                case '4':
                    $this->deleteDepartment();
                    break;
                case '5':
                    return;
            }
        }
    }

    private function listDepartments()
    {
        $depts = Department::getAll();
        echo "\n--- Liste des departements ---\n";
        foreach ($depts as $d) {
            echo $d . "\n";
        }
    }

    private function addDepartment()
    {
        echo "\n--- Ajouter un departement ---\n";
        $nom = readline("Nom: ");
        $location = readline("Location: ");

        $dept = new Department($nom, $location);
        
        if ($dept->save()) {
            echo "Departement ajoute!\n";
        }
    }

    private function updateDepartment()
    {
        $id = readline("ID du departement: ");
        $dept = Department::getById($id);

        if ($dept) {
            echo "Departement actuel: " . $dept . "\n";
            
            $nom = readline("Nouveau nom (Enter pour garder): ");
            if ($nom != '') {
                $dept->setNom($nom);
            }

            $location = readline("Nouvelle location (Enter pour garder): ");
            if ($location != '') {
                $dept->setLocation($location);
            }

            if ($dept->save()) {
                echo "Departement modifie!\n";
            }
        } else {
            echo "Departement non trouve.\n";
        }
    }

    private function deleteDepartment()
    {
        $id = readline("ID du departement: ");
        $dept = Department::getById($id);

        if ($dept) {
            echo $dept . "\n";
            $confirm = readline("Confirmer (oui/non): ");
            
            if ($confirm == 'oui') {
                $dept->delete();
                echo "Departement supprime!\n";
            }
        } else {
            echo "Departement non trouve.\n";
        }
    }

    // ===== STATS =====
    private function showStats()
    {
        echo "\n=== Statistiques ===\n";
        
        $avgAge = Patient::calculateAverageAge();
        echo "Age moyen des patients: " . $avgAge . " ans\n\n";
        
        echo "Patients par departement:\n";
        $distribution = Patient::countByDepartment();
        foreach ($distribution as $row) {
            echo "  - " . $row['departement_nom'] . ": " . $row['count'] . " patients\n";
        }
        
        $mostPop = Department::getMostPopulated();
        if ($mostPop) {
            echo "\nDepartement le plus peuple: " . $mostPop->getNom() . "\n";
        }
        
        readline("\nAppuyez sur Enter...");
    }
}