<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'user_id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $allowedFields = ['user_id', 'adresse_mail', 'is_admin', 'created_at', 'updated_at'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Récupère un client avec le nombre de commandes en cours
     */
    public function getClientWithCommandesEnCours($userId)
    {
        return $this->db->table('clients')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();
    }

    /**
     * Vérifie si on peut supprimer un admin (s'il n'est pas le dernier)
     */
    public function canDeleteAdmin(): bool
    {
        $adminCount = $this->db->table('auth_groups_users')
            ->where('group', 'admin')
            ->countAllResults();

        return $adminCount > 1;
    }

    /**
     * Supprime complètement un compte utilisateur
     */
    public function deleteUserAccount($userId): bool
    {
        $this->db->transStart();

        $this->db->table('auth_identities')
            ->where('user_id', $userId)
            ->delete();

        $this->db->table('clients')
            ->where('user_id', $userId)
            ->delete();

        $users = auth()->getProvider();
        $users->delete($userId, true);

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /**
     * Récupère tous les clients non-admin
     */
    public function getAllNonAdminClients()
    {
        $adminIds = $this->db->table('auth_groups_users')
            ->select('user_id')
            ->where('group', 'admin')
            ->get()
            ->getResultArray();

        $excludedIds = array_column($adminIds, 'user_id');

        $builder = $this->db->table('users');
        $builder->select('users.id, users.username, auth_identities.secret as email, clients.nb_commandes_en_cours, clients.is_admin');
        $builder->join('auth_identities', 'auth_identities.user_id = users.id', 'inner');
        $builder->join('clients', 'clients.user_id = users.id', 'left');
        $builder->where('auth_identities.type', 'email_password');

        if (!empty($excludedIds)) {
            $builder->whereNotIn('users.id', $excludedIds);
        }

        return $builder->get()->getResult();
    }

    /**
     * Promouvoir un utilisateur en administrateur
     */
    public function promoteToAdmin($userId): array
    {
        $users = auth()->getProvider();
        $user = $users->find($userId);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Utilisateur introuvable.'
            ];
        }

        $user->removeGroup('user');
        $user->addGroup('admin');

        $this->db->table('clients')
            ->where('user_id', $userId)
            ->update(['is_admin' => 1]);

        return [
            'success' => true,
            'message' => "L'utilisateur {$user->username} est maintenant administrateur."
        ];
    }
}