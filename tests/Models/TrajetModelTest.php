<?php

declare(strict_types=1);

namespace Tests\Models;

use App\Models\TrajetModel;
use PDOException;
use Tests\DatabaseTestCase;

/**
 * Tests des opérations d'écriture sur les trajets.
 */
final class TrajetModelTest extends DatabaseTestCase
{
    private TrajetModel $model;
    private int $employe;
    private int $paris;
    private int $lyon;

    protected function setUp(): void
    {
        parent::setUp();

        $this->model   = new TrajetModel();
        $this->employe = $this->insertEmploye();
        $this->paris   = $this->insertAgence('Paris');
        $this->lyon    = $this->insertAgence('Lyon');
    }

    public function testCreateInsertsTrajet(): void
    {
        $id = $this->model->create($this->tripData($this->paris, $this->lyon), $this->employe);

        $trajet = $this->model->findById($id);

        $this->assertGreaterThan(0, $id);
        $this->assertNotNull($trajet);
        $this->assertSame($this->employe, (int) $trajet['id_employe']);
        $this->assertSame($this->paris, (int) $trajet['id_agence_depart']);
        $this->assertSame(3, (int) $trajet['places_disponibles']);
    }

    public function testUpdateChangesTrajet(): void
    {
        $id   = $this->model->create($this->tripData($this->paris, $this->lyon), $this->employe);
        $data = $this->tripData($this->lyon, $this->paris);
        $data['places_disponibles'] = 1;

        $this->model->update($id, $this->employe, $data);

        $trajet = $this->model->findById($id);
        $this->assertNotNull($trajet);
        $this->assertSame(1, (int) $trajet['places_disponibles']);
        $this->assertSame($this->lyon, (int) $trajet['id_agence_depart']);
    }

    public function testUpdateDoesNotTouchOtherTrajets(): void
    {
        $first  = $this->model->create($this->tripData($this->paris, $this->lyon), $this->employe);
        $second = $this->model->create($this->tripData($this->paris, $this->lyon), $this->employe);
        $data   = $this->tripData($this->paris, $this->lyon);
        $data['places_disponibles'] = 0;

        $this->model->update($first, $this->employe, $data);

        $untouched = $this->model->findById($second);
        $this->assertNotNull($untouched);
        $this->assertSame(3, (int) $untouched['places_disponibles']);
    }

    public function testDeleteRemovesOnlyTheTargetedTrajet(): void
    {
        $first  = $this->model->create($this->tripData($this->paris, $this->lyon), $this->employe);
        $second = $this->model->create($this->tripData($this->paris, $this->lyon), $this->employe);

        $this->model->delete($first, $this->employe);

        $this->assertNull($this->model->findById($first));
        $this->assertNotNull($this->model->findById($second));
    }

    public function testAnotherEmployeeCannotUpdateOrDelete(): void
    {
        $id    = $this->model->create($this->tripData($this->paris, $this->lyon), $this->employe);
        $other = $this->employe + 1;
        $data  = $this->tripData($this->paris, $this->lyon);
        $data['places_disponibles'] = 0;

        $this->assertFalse($this->model->update($id, $other, $data));
        $this->assertFalse($this->model->delete($id, $other));
        $this->assertNotNull($this->model->findById($id));
    }

    public function testDeleteByAdminRemovesTrajet(): void
    {
        $id = $this->model->create($this->tripData($this->paris, $this->lyon), $this->employe);

        $this->assertTrue($this->model->deleteByAdmin($id));
        $this->assertNull($this->model->findById($id));
    }

    public function testDatabaseRejectsSameDepartureAndArrival(): void
    {
        $this->expectException(PDOException::class);

        $this->model->create($this->tripData($this->paris, $this->paris), $this->employe);
    }

    public function testDatabaseRejectsArrivalBeforeDeparture(): void
    {
        $data = $this->tripData($this->paris, $this->lyon);
        $data['date_arrivee'] = '2026-12-02 07:00:00';

        $this->expectException(PDOException::class);

        $this->model->create($data, $this->employe);
    }

    public function testDatabaseRejectsUnknownEmployee(): void
    {
        $this->expectException(PDOException::class);

        $this->model->create($this->tripData($this->paris, $this->lyon), 999999);
    }
}