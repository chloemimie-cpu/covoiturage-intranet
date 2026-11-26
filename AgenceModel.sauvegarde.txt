    /**
     * Supprime une agence.
     *
     * @return bool False si l'agence est utilisée par des trajets.
     */
    public function delete(int $id): bool
    {
        try {
            $stmt = $this->pdo->prepare('DELETE FROM agences WHERE id_agence = :id');
            $stmt->execute(['id' => $id]);

            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                return false;
            }
            throw $e;
        }
    }