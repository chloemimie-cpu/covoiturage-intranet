<?php

declare(strict_types=1);

namespace Tests\Models;

use App\Models\AgenceModel;
use App\Models\TrajetModel;
use PDOException;
use Tests\DatabaseTestCase;

/**
 * Tests des opérations d'écriture sur les agences.
 */
final class AgenceModelTest extends DatabaseTestCase
{
    private AgenceModel $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model = new AgenceModel();
    }

    public function testCreateAddsAgence(): void
    {
        $this->model->create('Dijon');

        $noms = array_column($this->model->findAll(), 'nom');
        $this->assertSame(['Dijon'], $noms);
    }

    public function testUpdateRenamesAgence(): void
    {
        $id = $this->insertAgence('Dijon');

        $this->model->update($id, 'Dijon Nord');

        $agence = $this->model->findById($id);
        $this->assertNotNull($agence);
        $this->assertSame('Dijon Nord', $agence['nom']);
    }

    public function testDeleteRemovesAgence(): void
    {
        $id = $this->insertAgence('Dijon');

        $this->model->delete($id);

        $this->assertNull($this->model->findById($id));
    }

    public function testDuplicateNameIsRejectedWithCode1062(): void
    {
        $this->insertAgence('Dijon');

        try {
            $this->model->create('Dijon');
            $this->fail('Un doublon aurait dû être refusé.');
        } catch (PDOException $e) {
            // 1062 = valeur déjà existante : AdminController s'appuie sur ce code
            $this->assertSame(1062, $e->errorInfo[1]);
        }
    }

    public function testAgenceUsedByATrajetCannotBeDeleted(): void
    {
        $employe = $this->insertEmploye();
        $paris   = $this->insertAgence('Paris');
        $lyon    = $this->insertAgence('Lyon');
        (new TrajetModel())->create($this->tripData($paris, $lyon), $employe);

        try {
            $this->model->delete($paris);
            $this->fail('La suppression aurait dû être refusée.');
        } catch (PDOException $e) {
            // 1451 = ligne encore utilisée ailleurs : AdminController s'appuie sur ce code
            $this->assertSame(1451, $e->errorInfo[1]);
        }

        $this->assertNotNull($this->model->findById($paris));
    }
}