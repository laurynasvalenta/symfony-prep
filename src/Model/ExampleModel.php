<?php

declare(strict_types=1);

namespace App\Model;

use App\Validator\Constraints\ExampleCompound;
use DateTimeInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\AtLeastOneOf;
use Symfony\Component\Validator\Constraints\Bic;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\CardScheme;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Cidr;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\CssColor;
use Symfony\Component\Validator\Constraints\Currency;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\DivisibleBy;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Expression;
use Symfony\Component\Validator\Constraints\ExpressionSyntax;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Hostname;
use Symfony\Component\Validator\Constraints\Iban;
use Symfony\Component\Validator\Constraints\IdenticalTo;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Ip;
use Symfony\Component\Validator\Constraints\Isbn;
use Symfony\Component\Validator\Constraints\IsFalse;
use Symfony\Component\Validator\Constraints\Isin;
use Symfony\Component\Validator\Constraints\IsNull;
use Symfony\Component\Validator\Constraints\Issn;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Validator\Constraints\Language;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Locale;
use Symfony\Component\Validator\Constraints\Luhn;
use Symfony\Component\Validator\Constraints\Negative;
use Symfony\Component\Validator\Constraints\NegativeOrZero;
use Symfony\Component\Validator\Constraints\NoSuspiciousCharacters;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\NotEqualTo;
use Symfony\Component\Validator\Constraints\NotIdenticalTo;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Validator\Constraints\Time;
use Symfony\Component\Validator\Constraints\Timezone;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Ulid;
use Symfony\Component\Validator\Constraints\Unique;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Constraints\When;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[Callback(callback: 'validateCallback', groups: ['constraint60'])]
class ExampleModel
{
    public function __construct(
        #[NotBlank(groups: ['constraint1']), Blank(groups: ['constraint2'])]
        #[NotNull(groups: ['constraint3'])]
        #[IsNull(groups: ['constraint4'])]
        #[IsTrue(groups: ['constraint5'])]
        #[IsFalse(groups: ['constraint6'])]
        #[Type(type: DateTimeInterface::class, groups: ['constraint7'])]
        #[Email(groups: ['constraint8'])]
        #[ExpressionSyntax(allowedVariables: ['numberOfGuests'], groups: ['constraint9'])]
        #[Length(min: 5, max: 12, groups: ['constraint10'])]
        #[Url(groups: ['constraint11'], requireTld: true)]
        #[Regex(pattern: '/^[a-zA-Z]+[0-9]+$/', message: 'This value does not match the expected pattern.', groups: ['constraint12'])]
        #[Hostname(requireTld: true, groups: ['constraint13'])]
        #[Ip(groups: ['constraint14'])]
        #[Cidr(groups: ['constraint15'])]
        #[Json(groups: ['constraint16'])]
        #[Uuid(groups: ['constraint17'])]
        #[Ulid(groups: ['constraint18'])]
        #[UserPassword(groups: ['constraint19'])]
        #[NotCompromisedPassword(groups: ['constraint20'])]
        #[PasswordStrength(minScore: 4, groups: ['constraint21'])]
        #[CssColor(groups: ['constraint22'])]
        #[NoSuspiciousCharacters(groups: ['constraint23'])]
        #[EqualTo(value: 5, groups: ['constraint24'])]
        #[NotEqualTo(value: 5, groups: ['constraint25'])]
        #[IdenticalTo(value: 5, groups: ['constraint26'])]
        #[NotIdenticalTo(value: 5, groups: ['constraint27'])]
        #[LessThan(value: 5, groups: ['constraint28'])]
        #[LessThanOrEqual(value: 5, groups: ['constraint29'])]
        #[GreaterThan(value: 10, groups: ['constraint30'])]
        #[GreaterThanOrEqual(value: 10, groups: ['constraint31'])]
        #[Range(min: 5, max: 10, groups: ['constraint32'])]
        #[DivisibleBy(value: 3, groups: ['constraint33'])]
        #[Unique(groups: ['constraint34'])]
        #[Positive(groups: ['constraint35'])]
        #[PositiveOrZero(groups: ['constraint36'])]
        #[Negative(groups: ['constraint37'])]
        #[NegativeOrZero(groups: ['constraint38'])]
        #[Date(groups: ['constraint39'])]
        #[DateTime(groups: ['constraint40'])]
        #[Time(groups: ['constraint41'])]
        #[Timezone(groups: ['constraint42'])]
        #[Choice(choices: ['valid-choice'], groups: ['constraint43'])]
        #[Language(groups: ['constraint44'])]
        #[Locale(groups: ['constraint45'])]
        #[Country(groups: ['constraint46'])]
        #[File(groups: ['constraint47'])]
        #[Image(groups: ['constraint48'])]
        #[Bic(groups: ['constraint49'])]
        #[CardScheme(schemes: ['VISA'], groups: ['constraint50'])]
        #[Currency(groups: ['constraint51'])]
        #[Luhn(groups: ['constraint52'])]
        #[Iban(groups: ['constraint53'])]
        #[Isbn(groups: ['constraint54'])]
        #[Issn(groups: ['constraint55'])]
        #[Isin(groups: ['constraint56'])]
        #[AtLeastOneOf(constraints: [new Positive(), new Negative()], groups: ['constraint57'])]
        #[Sequentially(constraints: [new NotBlank(), new Length(min: 5)], groups: ['constraint58'])]
        #[ExampleCompound(groups: ['constraint59'])]
        #[Expression(expression: 'this.getMainProperty() == "valid"', groups: ['constraint61'])]
        #[When(expression: 'this.getSecondProperty() == "condition"', constraints: [new NotBlank()], groups: ['constraint62'])]
        #[All(constraints: [new NotBlank()], groups: ['constraint63'])]
        #[Valid(groups: ['constraint64'])]
        #[NotBlank(groups: ['constraint66'])]
        #[Collection(fields: ['key' => new NotBlank(groups: ['constraint67'])], groups: ['constraint67'])]
        #[Count(min: 1, max: 1, groups: ['constraint68'])]
        private readonly mixed $mainProperty,
        private readonly mixed $secondProperty = null,
    ) {
    }

    public function getMainProperty(): mixed
    {
        return $this->mainProperty;
    }

    public function getSecondProperty(): mixed
    {
        return $this->secondProperty;
    }

    public static function validateCallback(self $value, ExecutionContextInterface $context): void
    {
        if ($value->getMainProperty() !== 'valid') {
            $context->buildViolation('This value is not valid according to the custom callback.')
                ->addViolation();
        }
    }
}
