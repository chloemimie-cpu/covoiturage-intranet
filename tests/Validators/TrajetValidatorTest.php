<?php

declare(strict_types=1);

namespace Tests\Validators;

use App\Validators\TrajetValidator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * Tests des contrôles de cohérence d'un trajet.
 */
final class TrajetValidatorTest extends TestCase
{
    /** Agences qui existent dans notre scénario de test. */
    private const AGENCES = [1, 2, 3];

    private TrajetValidator $validator;
    private DateTimeImmutable $now;

    /**
     * Exécutée avant chaque test : on repart d'un état propre.
     */
    protected function setUp(): void
    {
        $this->validator = new TrajetValidator();
        $this->now       = new DateTimeImmutable('2026-12-01 10:00:00');
    }

    /**
     * Données d'un trajet valide, que chaque test modifie à sa guise.
     *
     * @return array<string, string>
     */
    private function validInput(): array
    {
        return [
            'id_agence_depart'   => '1',
            'id_agence_arrivee'  => '2',
            'date_depart'        => '2026-12-02T08:00',
            'date_arrivee'       => '2026-12-02T12:00',
            'places_total'       => '4',
            'places_disponibles' => '3',
        ];
    }

    public function testValidInputHasNoErrors(): void
    {
        $result = $this->validator->validate($this->validInput(), self::AGENCES, $this->now);

        $this->assertSame([], $result['errors']);
        $this->assertSame('2026-12-02 08:00:00', $result['data']['date_depart']);
        $this->assertSame(3, $result['data']['places_disponibles']);
    }

    public function testSameDepartureAndArrivalAgencyIsRejected(): void
    {
        $input = $this->validInput();
        $input['id_agence_arrivee'] = '1';

        $result = $this->validator->validate($input, self::AGENCES, $this->now);

        $this->assertArrayHasKey('id_agence_arrivee', $result['errors']);
        $this->assertSame([], $result['data']);
    }

    public function testUnknownAgencyIsRejected(): void
    {
        $input = $this->validInput();
        $input['id_agence_depart'] = '99';

        $result = $this->validator->validate($input, self::AGENCES, $this->now);

        $this->assertArrayHasKey('id_agence_depart', $result['errors']);
    }

    public function testArrivalBeforeDepartureIsRejected(): void
    {
        $input = $this->validInput();
        $input['date_arrivee'] = '2026-12-02T07:00';

        $result = $this->validator->validate($input, self::AGENCES, $this->now);

        $this->assertArrayHasKey('date_arrivee', $result['errors']);
    }

    public function testDepartureInThePastIsRejected(): void
    {
        $input = $this->validInput();
        $input['date_depart']  = '2026-11-30T08:00';
        $input['date_arrivee'] = '2026-11-30T12:00';

        $result = $this->validator->validate($input, self::AGENCES, $this->now);

        $this->assertArrayHasKey('date_depart', $result['errors']);
    }

    public function testMoreAvailableSeatsThanTotalIsRejected(): void
    {
        $input = $this->validInput();
        $input['places_disponibles'] = '5';

        $result = $this->validator->validate($input, self::AGENCES, $this->now);

        $this->assertArrayHasKey('places_disponibles', $result['errors']);
    }

    public function testInvalidDateFormatIsRejected(): void
    {
        $input = $this->validInput();
        $input['date_depart'] = 'demain matin';

        $result = $this->validator->validate($input, self::AGENCES, $this->now);

        $this->assertArrayHasKey('date_depart', $result['errors']);
    }
}