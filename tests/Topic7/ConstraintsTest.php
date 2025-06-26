<?php

declare(strict_types=1);

namespace App\Tests\Topic7;

use App\Model\AnotherExampleModel;
use App\Model\ExampleModel;
use App\Model\TraversableExample;
use App\Model\TraversableExample2;
use App\Model\User;
use DateTime;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/*
 * This is a demonstration test for Symfony Certification Topic 7 (Data Validation).
 */
class ConstraintsTest extends WebTestCase
{
    private ValidatorInterface $validator;

    public function setUp(): void
    {
        parent::setUp();

        $client = static::createClient();

        $user = new User();
        $user->setPassword(
            static::getContainer()->get(UserPasswordHasherInterface::class)->hashPassword($user, 'ValidPassword123!')
        );

        $client->loginUser($user);

        $this->validator = static::getContainer()->get('validator');
    }

    /**
     * @see src/Model/ExampleModel.php
     */
    #[Test, DataProvider('validationConstraintsCanBeEnabledPerGroupProvider')]
    public function validationConstraintsCanBeEnabledPerGroup(mixed $object, string $groups, string $expected): void
    {
        $violations = $this->validator->validate($object, null, $groups);

        $violationsAsString = implode('; ', array_map(
            static fn($violation) => $violation->getMessage(),
            iterator_to_array($violations)
        ));

        static::assertSame($expected, $violationsAsString);
    }

    public static function validationConstraintsCanBeEnabledPerGroupProvider(): iterable
    {
        yield [new ExampleModel(''),  'constraint1', 'This value should not be blank.'];
        yield [new ExampleModel('test'),  'constraint1', ''];

        yield [new ExampleModel('test'),  'constraint2', 'This value should be blank.'];
        yield [new ExampleModel(''),  'constraint2', ''];

        yield [new ExampleModel(null),  'constraint3', 'This value should not be null.'];
        yield [new ExampleModel(''),  'constraint3', ''];

        yield [new ExampleModel(''),  'constraint4', 'This value should be null.'];
        yield [new ExampleModel(null),  'constraint4', ''];

        yield [new ExampleModel('Yes'),  'constraint5', 'This value should be true.'];
        yield [new ExampleModel('1'),  'constraint5', ''];

        yield [new ExampleModel(1),  'constraint6', 'This value should be false.'];
        yield [new ExampleModel(false),  'constraint6', ''];

        yield [new ExampleModel(time()),  'constraint7', 'This value should be of type DateTimeInterface.'];
        yield [new ExampleModel(new DateTime()),  'constraint7', ''];

        yield [new ExampleModel('email@top-level-domain'),  'constraint8', 'This value is not a valid email address.'];
        yield [new ExampleModel('email@top-level-domain.eu'),  'constraint8', ''];

        yield [new ExampleModel('1 + numberOfGuests + numberOfStaff'),  'constraint9', 'This value should be a valid expression.'];
        yield [new ExampleModel('1 + numberOfGuests'),  'constraint9', ''];

        yield [new ExampleModel('string-of-certain-length'),  'constraint10', 'This value is too long. It should have 12 characters or less.'];
        yield [new ExampleModel('short-string'),  'constraint10', ''];

        yield [new ExampleModel('example.com'),  'constraint11', 'This value is not a valid URL.'];
        yield [new ExampleModel('https://example.com'),  'constraint11', ''];

        yield [new ExampleModel('123abc'), 'constraint12', 'This value does not match the expected pattern.'];
        yield [new ExampleModel('abc123'), 'constraint12', ''];

        yield [new ExampleModel('invalid_hostname'), 'constraint13', 'This value is not a valid hostname.'];
        yield [new ExampleModel('example.com'), 'constraint13', ''];

        yield [new ExampleModel('invalid_ip'), 'constraint14', 'This value is not a valid IP address.'];
        yield [new ExampleModel('192.168.1.1'), 'constraint14', ''];

        yield [new ExampleModel('192.168.1.1/33'), 'constraint15', 'The value of the netmask should be between 0 and 32.'];
        yield [new ExampleModel('192.168.1.1/24'), 'constraint15', ''];

        yield [new ExampleModel('invalid_json'), 'constraint16', 'This value should be valid JSON.'];
        yield [new ExampleModel('{"key": "value"}'), 'constraint16', ''];

        yield [new ExampleModel('invalid-uuid'), 'constraint17', 'This value is not a valid UUID.'];
        yield [new ExampleModel('123e4567-e89b-12d3-a456-426614174000'), 'constraint17', ''];

        yield [new ExampleModel('invalid-ulid'), 'constraint18', 'This is not a valid ULID.'];
        yield [new ExampleModel('01ARZ3NDEKTSV4RRFFQ69G5FAV'), 'constraint18', ''];

        yield [new ExampleModel('invalid password'), 'constraint19', 'This value should be the user\'s current password.'];
        yield [new ExampleModel('ValidPassword123!'), 'constraint19', ''];

        yield [new ExampleModel('123456'), 'constraint20', 'This password has been leaked in a data breach, it must not be used. Please use another password.'];
        yield [new ExampleModel('UniquePassword123!'), 'constraint20', ''];

        yield [new ExampleModel('weakpassword'), 'constraint21', 'The password strength is too low. Please use a stronger password.'];
        yield [new ExampleModel('StrongPassword123!``'), 'constraint21', ''];

        yield [new ExampleModel('not-a-color'), 'constraint22', 'This value is not a valid CSS color.'];
        yield [new ExampleModel('#FFFFFF'), 'constraint22', ''];

        yield [new ExampleModel('ѕymfony.com'), 'constraint23', 'This value contains characters that are not allowed by the current restriction-level.'];
        yield [new ExampleModel('symfony.com'), 'constraint23', ''];

        yield [new ExampleModel(10), 'constraint24', 'This value should be equal to 5.'];
        yield [new ExampleModel(5), 'constraint24', ''];

        yield [new ExampleModel(5), 'constraint25', 'This value should not be equal to 5.'];
        yield [new ExampleModel(10), 'constraint25', ''];

        yield [new ExampleModel('5'), 'constraint26', 'This value should be identical to int 5.'];
        yield [new ExampleModel(5), 'constraint26', ''];

        yield [new ExampleModel(5), 'constraint27', 'This value should not be identical to int 5.'];
        yield [new ExampleModel('5'), 'constraint27', ''];

        yield [new ExampleModel(10), 'constraint28', 'This value should be less than 5.'];
        yield [new ExampleModel(4), 'constraint28', ''];

        yield [new ExampleModel(10), 'constraint29', 'This value should be less than or equal to 5.'];
        yield [new ExampleModel(5), 'constraint29', ''];

        yield [new ExampleModel(5), 'constraint30', 'This value should be greater than 10.'];
        yield [new ExampleModel(15), 'constraint30', ''];

        yield [new ExampleModel(5), 'constraint31', 'This value should be greater than or equal to 10.'];
        yield [new ExampleModel(10), 'constraint31', ''];

        yield [new ExampleModel(15), 'constraint32', 'This value should be between 5 and 10.'];
        yield [new ExampleModel(7), 'constraint32', ''];

        yield [new ExampleModel(7), 'constraint33', 'This value should be a multiple of 3.'];
        yield [new ExampleModel(9), 'constraint33', ''];

        yield [new ExampleModel(['a', 'b', 'a']), 'constraint34', 'This collection should contain only unique elements.'];
        yield [new ExampleModel(['a', 'b', 'c']), 'constraint34', ''];

        yield [new ExampleModel(-1), 'constraint35', 'This value should be positive.'];
        yield [new ExampleModel(1), 'constraint35', ''];

        yield [new ExampleModel(-1), 'constraint36', 'This value should be either positive or zero.'];
        yield [new ExampleModel(0), 'constraint36', ''];

        yield [new ExampleModel(1), 'constraint37', 'This value should be negative.'];
        yield [new ExampleModel(-1), 'constraint37', ''];

        yield [new ExampleModel(1), 'constraint38', 'This value should be either negative or zero.'];
        yield [new ExampleModel(0), 'constraint38', ''];

        yield [new ExampleModel('invalid-date'), 'constraint39', 'This value is not a valid date.'];
        yield [new ExampleModel('2023-10-01'), 'constraint39', ''];

        yield [new ExampleModel('invalid-datetime'), 'constraint40', 'This value is not a valid datetime.'];
        yield [new ExampleModel('2023-10-01 12:00:00'), 'constraint40', ''];

        yield [new ExampleModel('invalid-time'), 'constraint41', 'This value is not a valid time.'];
        yield [new ExampleModel('12:00:00'), 'constraint41', ''];

        yield [new ExampleModel('invalid-timezone'), 'constraint42', 'This value is not a valid timezone.'];
        yield [new ExampleModel('Europe/Paris'), 'constraint42', ''];

        yield [new ExampleModel('invalid-choice'), 'constraint43', 'The value you selected is not a valid choice.'];
        yield [new ExampleModel('valid-choice'), 'constraint43', ''];

        yield [new ExampleModel('invalid-language'), 'constraint44', 'This value is not a valid language.'];
        yield [new ExampleModel('en'), 'constraint44', ''];

        yield [new ExampleModel('invalid-locale'), 'constraint45', 'This value is not a valid locale.'];
        yield [new ExampleModel('en_US'), 'constraint45', ''];

        yield [new ExampleModel('London'), 'constraint46', 'This value is not a valid country.'];
        yield [new ExampleModel('DE'), 'constraint46', ''];

        yield [new ExampleModel('invalid-file'), 'constraint47', 'The file could not be found.'];
        yield [new ExampleModel(__FILE__), 'constraint47', ''];

        yield [new ExampleModel(__FILE__), 'constraint48', 'This file is not a valid image.'];
        yield [new ExampleModel(__DIR__ . '/../../public/symfony.png'), 'constraint48', ''];

        yield [new ExampleModel('invalid-bic'), 'constraint49', 'This value is not a valid Business Identifier Code (BIC).'];
        yield [new ExampleModel('DEUTDEFF'), 'constraint49', ''];

        yield [new ExampleModel('invalid-card'), 'constraint50', 'Unsupported card type or invalid card number.'];
        yield [new ExampleModel('4111111111111111'), 'constraint50', ''];

        yield [new ExampleModel('invalid-currency'), 'constraint51', 'This value is not a valid currency.'];
        yield [new ExampleModel('USD'), 'constraint51', ''];

        yield [new ExampleModel('79927398712'), 'constraint52', 'Invalid card number.'];
        yield [new ExampleModel('79927398713'), 'constraint52', ''];

        yield [new ExampleModel('DE89370400440532013001'), 'constraint53', 'This value is not a valid International Bank Account Number (IBAN).'];
        yield [new ExampleModel('DE89370400440532013000'), 'constraint53', ''];

        yield [new ExampleModel('978-3-16-148410-1'), 'constraint54', 'This value is neither a valid ISBN-10 nor a valid ISBN-13.'];
        yield [new ExampleModel('978-3-16-148410-0'), 'constraint54', ''];

        yield [new ExampleModel('2049-36301'), 'constraint55', 'This value is not a valid ISSN.'];
        yield [new ExampleModel('2049-3630'), 'constraint55', ''];

        yield [new ExampleModel('US0378331004'), 'constraint56', 'This value is not a valid International Securities Identification Number (ISIN).'];
        yield [new ExampleModel('US0378331005'), 'constraint56', ''];

        yield [new ExampleModel(0), 'constraint57', 'This value should satisfy at least one of the following constraints: [1] This value should be positive. [2] This value should be negative.'];
        yield [new ExampleModel('valid'), 'constraint57', ''];

        yield [new ExampleModel(''), 'constraint58', 'This value should not be blank.'];
        yield [new ExampleModel('-'), 'constraint58', 'This value is too short. It should have 5 characters or more.'];
        yield [new ExampleModel('valid'), 'constraint58', ''];

        /** @see src/Validator/Constraints/ExampleCompound.php */
        yield [new ExampleModel('invalid-example1'), 'constraint59', 'This value should not be equal to "invalid-example1".'];
        yield [new ExampleModel('invalid-example2'), 'constraint59', 'This value should not be equal to "invalid-example2".'];
        yield [new ExampleModel('string cannot contain exclamation mark!'), 'constraint59', 'This value is not valid.'];
        yield [new ExampleModel('valid'), 'constraint59', ''];

        yield [new ExampleModel('invalid'), 'constraint60', 'This value is not valid according to the custom callback.'];
        yield [new ExampleModel('valid'), 'constraint60', ''];

        // Expression constraint
        yield [new ExampleModel('invalid'), 'constraint61', 'This value is not valid.'];
        yield [new ExampleModel('valid'), 'constraint61', ''];

        yield [new ExampleModel('', 'condition'), 'constraint62', 'This value should not be blank.'];
        yield [new ExampleModel(''), 'constraint62', ''];

        yield [new ExampleModel(['', 'not-blank']), 'constraint63', 'This value should not be blank.'];
        yield [new ExampleModel(['not-blank', 'not-blank2']), 'constraint63', ''];

        yield [new ExampleModel(new AnotherExampleModel(null)), 'constraint64', 'This value should not be null.'];
        yield [new ExampleModel(new AnotherExampleModel('not null')), 'constraint64', ''];

        yield [new AnotherExampleModel(property: 'test', anotherProperty: new AnotherExampleModel(null)), 'constraint65', 'This value should not be null.'];
        yield [new AnotherExampleModel(property: 'test', anotherProperty: new AnotherExampleModel('not-null')), 'constraint65', ''];

        yield [new TraversableExample([new AnotherExampleModel('valid1'), new AnotherExampleModel(null)]), 'constraint66', ''];
        yield [new TraversableExample2([new AnotherExampleModel('valid'), new AnotherExampleModel(null)]), 'constraint66', 'This value should not be null.'];

        yield [new ExampleModel(['key' => '']), 'constraint67', 'This value should not be blank.'];
        yield [new ExampleModel(['key' => 'valid']), 'constraint67', ''];

        yield [new ExampleModel([]), 'constraint68', 'This collection should contain exactly 1 element.'];
        yield [new ExampleModel(['element']), 'constraint68', ''];
    }
}
