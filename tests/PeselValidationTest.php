<?php

namespace App\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use App\Service\PeselService;
use PHPUnit\Framework\TestCase;

class PeselValidationTest extends TestCase
{
    private static ?PeselService $peselService;

    final public static function setUpBeforeClass(): void
    {
        self::$peselService = new PeselService();
    }

    #[DataProvider('correctPeselProvider')]
    public function testCorrectPesel(string $pesel): void
    {
        $result = self::$peselService->validatePesel($pesel);
        $this->assertTrue($result);
    }

    #[DataProvider('incorrectPeselProvider')]
    public function testIncorrectPesel(string $pesel): void
    {
        $result = self::$peselService->validatePesel($pesel);
        $this->assertFalse($result);
    }

    public function testIncorrectLength(): void
    {
        $peselTooShort = '367454646';
        $peselTooLong = '8973264873267572';

        $result = self::$peselService->validatePesel($peselTooShort);
        $this->assertNotEquals(11, strlen($peselTooShort));
        $this->assertFalse($result);

        $result = self::$peselService->validatePesel($peselTooLong);
        $this->assertNotEquals(11, strlen($peselTooLong));
        $this->assertFalse($result);
    }

    public function testIncorrectPeselHasNonNumericCharacters(){
        $pesel = '98L72D45199';

        $result = self::$peselService->validatePesel($pesel);
        $this->assertFalse(ctype_digit($pesel));
        $this->assertFalse($result);
    }

    public function testIncorrectPeselHasWrongCheckDigit(){
        $pesel = '98L72D45199';

        $isDigitCorrect = self::$peselService->validateDigitNumber($pesel);
        $this->assertFalse($isDigitCorrect);

        $result = self::$peselService->validatePesel($pesel);
        $this->assertFalse($result);
    }

    public function testIncorrectPeselHasWrongDate(){
        $pesel = '0000001234';

        $isDateCorrect = self::$peselService->validateDate($pesel);
        $this->assertFalse($isDateCorrect);

        $result = self::$peselService->validatePesel($pesel);
        $this->assertFalse($result);
    }

    public static function correctPeselProvider(): array
    {
        return [
            ['98072445199'],
            ['48062278382'],
            ['49032021144'],
            ['83030372376'],
            ['59082634351'],
            ['01300555926'],
            ['95062461571'],
            ['95021881732']
        ];
    }

    /**
     * @return array[]
     */
    public static function incorrectPeselProvider(): array
    {
        return [
            ['1234567890'],
            ['123456789012'],
            ['A2345678901'],
            ['4405140B458'],
            ['44051401459'],
            ['80010112347'],
            ['00131234567'],
            ['00000000000']
        ];
    }
}
