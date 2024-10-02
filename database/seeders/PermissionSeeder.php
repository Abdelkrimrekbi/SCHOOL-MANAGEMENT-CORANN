<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard, UI.
            'Tableau de bord', 'Interface Utilisateur', 'Interface Examen', 'Interface Caisse',
            // Student-related permissions
            'Voir étudiant', 'Mettre à jour étudiant', 'Supprimer étudiant', 'Ajouter étudiant', 'Paiement étudiant',
            // Group-related permissions
            'Voir groupe', 'Mettre à jour groupe', 'Supprimer groupe', 'Ajouter groupe',
            // Level-related permissions
            'Voir niveau', 'Mettre à jour niveau', 'Supprimer niveau', 'Ajouter niveau',
            // Teacher-related permissions
            'Mettre à jour enseignant', 'Supprimer enseignant', 'Ajouter enseignant',
            'Voir seance enseignant', 'Ajouter seance enseignant', 'Mettre a jour seance enseignant', 'Supprimer seance enseignant',
            // Subject-related permissions
            'Voir matière', 'Mettre à jour matière', 'Supprimer matière', 'Ajouter matière',
            // Department-related permissions
            'Voir filières', 'Mettre à jour filières', 'Supprimer filières', 'Ajouter filières',
            // Revenue-related permissions
            'Voir revenu', 'Mettre à jour revenu', 'Supprimer revenu', 'Ajouter revenu',
            // Exam-related permissions
            'Ajouter examen', 'Mettre à jour examen', 'Supprimer examen', 'Ajouter note',
            // Absence permission
            'Ajouter absence',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

}
