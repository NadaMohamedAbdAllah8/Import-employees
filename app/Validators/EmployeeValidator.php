<?php

namespace App\Validators;

use App\Constants\EmployeeHeader;
use App\Enums\Gender;
use App\Exceptions\ValidationException;
use App\Models\Employee;

class EmployeeValidator
{
    /**
     * Validate imported employee data
     *
     * @throws ValidationException
     */
    public static function validateEmployee($row): void
    {
        if (filter_var($row[EmployeeHeader::EMP_ID_INDEX], FILTER_VALIDATE_INT) === false) {
            throw new ValidationException('Employee id is not a valid integer');
        }

        if (! is_string($row[EmployeeHeader::FIRST_NAME_INDEX])) {
            throw new ValidationException('First name is not a valid string');
        }

        if (! is_string($row[EmployeeHeader::LAST_NAME_INDEX])) {
            throw new ValidationException('Last name is not a valid string');
        }

        if (strlen($row[EmployeeHeader::MIDDLE_INITIAL_INDEX]) > 1) {
            throw new ValidationException('Initial is not valid');
        }

        if (! self::isValidGender($row[EmployeeHeader::GENDER_INDEX])) {
            throw new ValidationException('Gender is not valid. It has to be '
                .Gender::FEMALE->value.', or '.Gender::MALE->value.''
            );
        }

        if (! self::isValidEmail($row[EmployeeHeader::E_MAIL_INDEX], $row[EmployeeHeader::EMP_ID_INDEX])) {
            throw new ValidationException('Email is not valid');
        }

        if (! is_numeric($row[EmployeeHeader::AGE_IN_COMPANY_YEARS_INDEX])) {
            throw new ValidationException('Age in company is not valid');
        }

        if (! is_numeric($row[EmployeeHeader::AGE_IN_YRS_INDEX])) {
            throw new ValidationException('Age in years is not valid');
        }

        if (! self::isValidPhoneNumber($row[EmployeeHeader::PHONE_NO_INDEX], $row[EmployeeHeader::EMP_ID_INDEX])) {
            throw new ValidationException('Phone number is not valid');
        }

        if (! is_string($row[EmployeeHeader::PLACE_NAME_INDEX])) {
            throw new ValidationException('Place name is not a valid string');
        }

        if (! is_string($row[EmployeeHeader::REGION_INDEX])) {
            throw new ValidationException('Region is not a valid string');
        }

        if (! is_string($row[EmployeeHeader::CITY_INDEX])) {
            throw new ValidationException('City is not a valid string');
        }

        if (! is_string($row[EmployeeHeader::COUNTY_INDEX])) {
            throw new ValidationException('County is not a valid string');
        }

        if (filter_var($row[EmployeeHeader::ZIP_INDEX], FILTER_VALIDATE_INT) === false) {
            throw new ValidationException('Zip code is not a valid number');
        }

        if (! self::isValidUsername($row[EmployeeHeader::USER_NAME_INDEX], $row[EmployeeHeader::EMP_ID_INDEX])) {
            throw new ValidationException('User name not valid');
        }
    }

    private static function isValidGender($gender): bool
    {
        if ($gender == Gender::FEMALE->value || $gender == Gender::MALE->value) {
            return true;
        }

        return false;
    }

    private static function isValidEmail($email, $id): bool
    {
        $unique = true;

        $valid_characters = filter_var($email, FILTER_VALIDATE_EMAIL);

        $employee_exists = Employee::where('email', $email)->select('id')->first();

        if ($employee_exists && $employee_exists->id != $id) {
            $unique = false;
        }

        return $valid_characters && $unique;
    }

    private static function isValidPhoneNumber($phone_number, $id): bool
    {
        $unique = true;

        /**This regex will match phone numbers in several formats:
        123-456-7890
        (123) 456-7890
        123.456.7890
        1234567890
        +1 (123) 456-7890 */
        $valid_characters = preg_match('/^(\+\d{1,2}\s?)?1?[-.\s]?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}$/', $phone_number);

        $employee_exists = Employee::where('phone_number', $phone_number)->select('id')->first();

        if ($employee_exists && $employee_exists->id != $id) {
            $unique = false;
        }

        return $valid_characters && $unique;
    }

    private static function isValidUsername($username, $id): bool
    {
        $unique = true;

        // Check allowed characters: alphanumeric, _, -, and .
        $valid_characters = preg_match('/^[a-zA-Z0-9_\-\.]+$/', $username);

        $employee_exists = Employee::where('username', $username)->select('id')->first();

        if ($employee_exists && $employee_exists->id != $id) {
            $unique = false;
        }

        return $valid_characters && $unique;
    }
}
