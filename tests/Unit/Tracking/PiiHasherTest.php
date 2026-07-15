<?php

declare(strict_types=1);

use App\Services\Tracking\PiiHasher;
use Tests\TestCase;

// tests/Pest.php binds TestCase to Feature only. PiiHasher reads the default
// phone country code from config, so this file needs the container booted.
// No database is touched, so RefreshDatabase is deliberately not used.
uses(TestCase::class);

/*
|--------------------------------------------------------------------------
| Meta's own published vectors
|--------------------------------------------------------------------------
|
| Every expected hash below is copied from Meta's customer-information
| parameters documentation, not computed by us — otherwise the test would just
| assert that our bug hashes consistently.
|
| These matter more than a typical unit test. A wrong normalisation still
| yields a valid 64-char hash that Meta accepts without complaint; it simply
| matches no one, and EMQ silently degrades with nothing in any log. These
| vectors are the only signal that would catch it.
|
| @see https://developers.facebook.com/docs/marketing-api/conversions-api/parameters/customer-information-parameters
*/

it('hashes email to Metas documented vector', function () {
    expect(PiiHasher::normalizeEmail('John_Smith@gmail.com'))->toBe('john_smith@gmail.com')
        ->and(PiiHasher::email('John_Smith@gmail.com'))
        ->toBe('62a14e44f765419d10fea99367361a727c12365e2520f32218d505ed9aa0f62f');
});

it('hashes a US phone to Metas documented vector', function () {
    expect(PiiHasher::normalizePhone('(650)555-1212', '1'))->toBe('16505551212')
        ->and(PiiHasher::phone('(650)555-1212', '1'))
        ->toBe('e323ec626319ca94ee8bff2e4c87cf613be6ea19919ed1364124e16807ab3176');
});

it('hashes a first name to Metas documented vector', function () {
    expect(PiiHasher::normalizeName('Mary'))->toBe('mary')
        ->and(PiiHasher::firstName('Mary'))
        ->toBe('6915771be1c5aa0c886870b6951b03d7eafc121fea0e80a5ea83beb7c449f4ec');
});

/*
| The two that catch the classic bug. Meta preserves accents and non-Latin
| scripts in names — "Valéry" normalises to "valéry", NOT "valry". A
| preg_replace('/[^a-z]/') would pass every other test in this file and quietly
| destroy matching for every customer with an accented name.
*/
it('preserves accented characters in names per Metas vector', function () {
    expect(PiiHasher::normalizeName('Valéry'))->toBe('valéry')
        ->and(PiiHasher::firstName('Valéry'))
        ->toBe('08e1996b5dd49e62a4b4c010d44e4345592a863bb9f8e3976219bac29417149c');
});

it('preserves non-latin scripts in names per Metas vector', function () {
    expect(PiiHasher::normalizeName('정'))->toBe('정')
        ->and(PiiHasher::firstName('정'))
        ->toBe('8fa8cd9c440be61d0151429310034083132b35975c4bea67fdd74158eb51db14');
});

it('hashes a birthdate to Metas documented vector', function () {
    expect(PiiHasher::normalizeBirthdate('2/16/1997'))->toBe('19970216')
        ->and(PiiHasher::birthdate('2/16/1997'))
        ->toBe('01acdbf6ec7b4f478a225f1a246e5d6767eeab1a7ffa17f025265b5b94f40f0c');
});

it('hashes a country to Metas documented vector', function () {
    expect(PiiHasher::normalizeCountry('United States'))->toBe('us')
        ->and(PiiHasher::country('United States'))
        ->toBe('79adb2a2fce5c6ba215fe5f27f532d4e7edbac4b6a5e09e1ef3a08084a904621');
});

// ─── Normalisation rules ────────────────────────────────────────────────────

it('trims and lowercases email', function () {
    expect(PiiHasher::normalizeEmail('  ALICE@Example.COM '))->toBe('alice@example.com');
});

it('rejects a value that is not an email', function () {
    expect(PiiHasher::normalizeEmail('not-an-email'))->toBeNull()
        ->and(PiiHasher::email('not-an-email'))->toBeNull();
});

it('converts a Bangladeshi national number to international form', function () {
    // The store's real-world format: leading trunk zero, no country code.
    expect(PiiHasher::normalizePhone('01712345678', '880'))->toBe('8801712345678');
});

it('leaves an already-international number alone', function () {
    expect(PiiHasher::normalizePhone('+8801712345678', '880'))->toBe('8801712345678')
        ->and(PiiHasher::normalizePhone('008801712345678', '880'))->toBe('8801712345678')
        ->and(PiiHasher::normalizePhone('8801712345678', '880'))->toBe('8801712345678');
});

it('strips formatting from phone numbers', function () {
    expect(PiiHasher::normalizePhone('+880 171-234 5678', '880'))->toBe('8801712345678');
});

it('uses the configured country code by default', function () {
    config()->set('tracking.default_phone_country_code', '880');

    expect(PiiHasher::normalizePhone('01712345678'))->toBe('8801712345678');
});

it('returns null for a phone with no digits', function () {
    expect(PiiHasher::normalizePhone('abc'))->toBeNull();
});

it('removes punctuation from names but keeps letters', function () {
    expect(PiiHasher::normalizeName("O'Brien-Smith"))->toBe('obriensmith');
});

it('normalizes gender to a single lowercase initial', function () {
    expect(PiiHasher::normalizeGender('Female'))->toBe('f')
        ->and(PiiHasher::normalizeGender('M'))->toBe('m')
        ->and(PiiHasher::normalizeGender('other'))->toBeNull();
});

it('normalizes city by removing spaces and punctuation', function () {
    expect(PiiHasher::normalizeCity('New York'))->toBe('newyork')
        ->and(PiiHasher::normalizeCity('Dhaka'))->toBe('dhaka');
});

it('normalizes zip by removing spaces and dashes', function () {
    expect(PiiHasher::normalizeZip('M1 1AE'))->toBe('m11ae')
        ->and(PiiHasher::normalizeZip('1207'))->toBe('1207');
});

it('cuts a US ZIP+4 to five digits', function () {
    expect(PiiHasher::normalizeZip('94035-1234'))->toBe('94035')
        ->and(PiiHasher::normalizeZip('940351234'))->toBe('94035');
});

it('maps Bangladesh to its ISO code', function () {
    expect(PiiHasher::normalizeCountry('Bangladesh'))->toBe('bd')
        ->and(PiiHasher::country('Bangladesh'))->toBe(hash('sha256', 'bd'));
});

it('passes through a country already in ISO form', function () {
    expect(PiiHasher::normalizeCountry('BD'))->toBe('bd');
});

it('returns null for an unmapped country rather than guessing', function () {
    expect(PiiHasher::normalizeCountry('Republic of Somewhere'))->toBeNull();
});

// ─── Hashing behaviour ──────────────────────────────────────────────────────

it('does not double hash an already hashed value', function () {
    $hashed = hash('sha256', 'john_smith@gmail.com');

    expect(PiiHasher::hash($hashed))->toBe($hashed);
});

it('lowercases an uppercase hash rather than rehashing it', function () {
    $hashed = strtoupper(hash('sha256', 'test'));

    expect(PiiHasher::hash($hashed))->toBe(strtolower($hashed));
});

it('returns null for blank input across every field', function () {
    expect(PiiHasher::email(null))->toBeNull()
        ->and(PiiHasher::phone(''))->toBeNull()
        ->and(PiiHasher::firstName(null))->toBeNull()
        ->and(PiiHasher::lastName(''))->toBeNull()
        ->and(PiiHasher::gender(null))->toBeNull()
        ->and(PiiHasher::birthdate(''))->toBeNull()
        ->and(PiiHasher::city(null))->toBeNull()
        ->and(PiiHasher::state(''))->toBeNull()
        ->and(PiiHasher::zip(null))->toBeNull()
        ->and(PiiHasher::country(''))->toBeNull()
        ->and(PiiHasher::externalId(null))->toBeNull();
});

it('hashes external id as a stable stringified value', function () {
    expect(PiiHasher::externalId(42))->toBe(hash('sha256', '42'))
        ->and(PiiHasher::externalId('42'))->toBe(hash('sha256', '42'));
});
