<?php

namespace App\Imports;

use App\Models\Admission;
use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\State;
use App\Models\District;

use Carbon\Carbon;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use PhpOffice\PhpSpreadsheet\Shared\Date;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;

class StudentsImport implements
    WithHeadingRow,
    SkipsEmptyRows,
    ToCollection
{
    /*
    |--------------------------------------------------------------------------
    | HEADING ROW
    |--------------------------------------------------------------------------
    */

    public function headingRow(): int
    {
        
        return 4;
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT COLLECTION
    |--------------------------------------------------------------------------
    */

    public function collection(Collection $rows)
    {
       
        if($rows->count() == 0){

            Log::error('NO ROWS FOUND IN EXCEL');

            return;
        }

        foreach($rows as $row){

            try{

                $row = $row->toArray();
               

                /*
                |--------------------------------------------------------------------------
                | USER TYPE
                |--------------------------------------------------------------------------
                */

                $userType = $this->getValue($row, [
                    'user_type'
                ]) ?: 'student';

                /*
                |--------------------------------------------------------------------------
                | STUDENT
                |--------------------------------------------------------------------------
                */

                $name = $this->getValue($row, ['name']);

                $gender = $this->getValue($row, ['gender']);

                $dobRaw = $this->getValue($row, ['dob']);

                $aadhaarRaw = $this->getValue($row, ['aadhaar']);

                $bloodGroup = $this->getValue($row, ['blood_group']);

                $religion = $this->getValue($row, ['religion']);

                $category = $this->getValue($row, ['category']);

               

                /*
                |--------------------------------------------------------------------------
                | FATHER
                |--------------------------------------------------------------------------
                */

                $fatherEmail = $this->getValue($row, [
                    'father_email'
                ]);

                $fatherName = $this->getValue($row, [
                    'father_name'
                ]);

                $fatherMobileRaw = $this->getValue($row, [
                    'father_mobile'
                ]);

                $fatherAadhaarRaw = $this->getValue($row, [
                    'father_aadhaar'
                ]);

                $fatherEducation = $this->getValue($row, [
                    'father_education'
                ]);

                $fatherOccupation = $this->getValue($row, [
                    'father_occupation'
                ]);

                /*
                |--------------------------------------------------------------------------
                | MOTHER
                |--------------------------------------------------------------------------
                */

                $motherName = $this->getValue($row, [
                    'mother_name'
                ]);

                $motherMobileRaw = $this->getValue($row, [
                    'mother_mobile'
                ]);

                $motherAadhaarRaw = $this->getValue($row, [
                    'mother_aadhaar'
                ]);

                $motherEducation = $this->getValue($row, [
                    'mother_education'
                ]);

                $motherOccupation = $this->getValue($row, [
                    'mother_occupation'
                ]);

                /*
                |--------------------------------------------------------------------------
                | EMERGENCY
                |--------------------------------------------------------------------------
                */

                $emergencyContactRaw = $this->getValue($row, [
                    'emergency_contact'
                ]);

                /*
                |--------------------------------------------------------------------------
                | ADDRESS
                |--------------------------------------------------------------------------
                */

                $pincode = $this->getValue($row, [
                    'pincode'
                ]);

                $permanentAddress = $this->getValue($row, [
                    'permanent_address'
                ]);

                $currentAddress = $this->getValue($row, [
                    'current_address'
                ]);

                /*
                |--------------------------------------------------------------------------
                | STATE / DISTRICT
                |--------------------------------------------------------------------------
                */

                $stateName = $this->getValue($row, [
                    'state'
                ]);

                $districtName = $this->getValue($row, [
                    'district'
                ]);

                /*
                |--------------------------------------------------------------------------
                | DOCUMENTS
                |--------------------------------------------------------------------------
                */

                $photo = $this->getValue($row, [
                    'photo'
                ]);

                $birthCertificate = $this->getValue($row, [
                    'birth_certificate'
                ]);

                $aadhaarDoc = $this->getValue($row, [
                    'aadhaar_doc'
                ]);

                $familyPhoto = $this->getValue($row, [
                    'family_photo'
                ]);

                $fatherAadhaarDoc = $this->getValue($row, [
                    'father_aadhaar_doc'
                ]);

                $motherAadhaarDoc = $this->getValue($row, [
                    'mother_aadhaar_doc'
                ]);

                /*
                |--------------------------------------------------------------------------
                | ACADEMIC
                |--------------------------------------------------------------------------
                */

                $className = $this->getValue($row, [
                    'class'
                ]);

                $sectionName = $this->getValue($row, [
                    'section'
                ]);

                /*
                |--------------------------------------------------------------------------
                | REQUIRED VALIDATION
                |--------------------------------------------------------------------------
                */

                $required = [

                    'name' => $name,

                    'gender' => $gender,

                    'dob' => $dobRaw,

                    'aadhaar' => $aadhaarRaw,

                    'father_name' => $fatherName,

                    'father_mobile' => $fatherMobileRaw,

                    'mother_name' => $motherName,

                    'emergency_contact' => $emergencyContactRaw,

                    'pincode' => $pincode,

                    'permanent_address' => $permanentAddress,

                    'state' => $stateName,

                    'district' => $districtName,

                    'class' => $className,

                    'section' => $sectionName,
                ];

                foreach($required as $field => $value){

                    if(empty($value)){

                        Log::error('IMPORT FAILED', [

                            'field' => $field,

                            'row' => $row
                        ]);

                        continue 2;
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | CLEAN NUMBERS
                |--------------------------------------------------------------------------
                */

                $aadhaar = $this->cleanNumeric(
                    $aadhaarRaw
                );

                $fatherMobile = $this->cleanNumeric(
                    $fatherMobileRaw
                );

                $fatherAadhaar = $this->cleanNumeric(
                    $fatherAadhaarRaw
                );

                $motherMobile = $this->cleanNumeric(
                    $motherMobileRaw
                );

                $motherAadhaar = $this->cleanNumeric(
                    $motherAadhaarRaw
                );

                $emergencyContact = $this->cleanNumeric(
                    $emergencyContactRaw
                );

                /*
                |--------------------------------------------------------------------------
                | DOB
                |--------------------------------------------------------------------------
                */

                $dob = $this->parseDate(
                    $dobRaw
                );

                /*
                |--------------------------------------------------------------------------
                | STATE MATCH
                |--------------------------------------------------------------------------
                */

                $state = State::whereRaw(
                    'LOWER(TRIM(name)) = ?',
                    [strtolower(trim($stateName))]
                )->first();

                

                if(!$state){

                    Log::error('STATE NOT FOUND', [

                        'state' => $stateName
                    ]);
                     
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | DISTRICT MATCH
                |--------------------------------------------------------------------------
                */

                $district = District::where(function($query)
                    use ($districtName, $state){

                    $query->whereRaw(
                        'LOWER(TRIM(name)) = ?',
                        [strtolower(trim($districtName))]
                    );

                    $query->where(
                        'state_id',
                        $state->id
                    );

                })->first();
               

                if(!$district){

                    Log::error('DISTRICT NOT FOUND', [

                        'district' => $districtName
                    ]);

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | CLASS MATCH
                |--------------------------------------------------------------------------
                */

                $classValue = strtolower(
                    trim($className)
                );

                $class = SchoolClass::where(function($query)
                    use ($classValue){

                    $query->whereRaw(
                        'LOWER(TRIM(name)) = ?',
                        [$classValue]
                    );

                    $query->orWhereRaw(
                        'LOWER(TRIM(name)) = ?',
                        ['class '.$classValue]
                    );

                    $query->orWhere(
                        'id',
                        $classValue
                    );

                })->first();

                
                

                if(!$class){

                    Log::error('CLASS NOT FOUND', [

                        'class' => $className
                    ]);

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | SECTION MATCH
                |--------------------------------------------------------------------------
                */

                $sectionValue = strtolower(
                    trim($sectionName)
                );

                $section = Section::where(function($query)
                    use ($sectionValue){

                    $query->whereRaw(
                        'LOWER(TRIM(name)) = ?',
                        [$sectionValue]
                    );

                    $query->orWhereRaw(
                        'LOWER(TRIM(name)) = ?',
                        ['section '.$sectionValue]
                    );

                    $query->orWhere(
                        'id',
                        $sectionValue
                    );

                })->first();

                

                if(!$section){

                    Log::error('SECTION NOT FOUND', [

                        'section' => $sectionName
                    ]);

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | DUPLICATE CHECK
                |--------------------------------------------------------------------------
                */

                if(
                    Admission::where(
                        'aadhaar',
                        $aadhaar
                    )->exists()
                ){

                    Log::warning('DUPLICATE AADHAAR', [

                        'aadhaar' => $aadhaar
                    ]);

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | APPLICATION NUMBER
                |--------------------------------------------------------------------------
                */

                $nextAdmissionId = (
                    Admission::max('id') ?? 0
                ) + 1;

                $applicationNo = 'ADM' . str_pad(

                    $nextAdmissionId,

                    5,

                    '0',

                    STR_PAD_LEFT
                );

                /*
                |--------------------------------------------------------------------------
                | CREATE ADMISSION
                |--------------------------------------------------------------------------
                */

                $admission = Admission::create([

                    'application_no' => $applicationNo,

                    'status' => 'approved',

                    'verification_status' => 'pending',

                    'approved_at' => now(),

                    'academic_year' =>
                        date('Y')
                        . '-' .
                        (date('Y') + 1),

                    'user_type' => $userType,

                    'name' => $name,

                    'gender' => $gender,

                    'dob' => $dob,

                    'aadhaar' => $aadhaar,

                    'blood_group' => $bloodGroup,

                    'religion' => $religion,

                    'category' => $category,

                    'father_name' => $fatherName,

                    'father_mobile' => $fatherMobile,

                    'father_email' => $fatherEmail,

                    'father_aadhaar' => $fatherAadhaar,

                    'father_education' => $fatherEducation,

                    'father_occupation' => $fatherOccupation,

                    'mother_name' => $motherName,

                    'mother_mobile' => $motherMobile,

                    'mother_aadhaar' => $motherAadhaar,

                    'mother_education' => $motherEducation,

                    'mother_occupation' => $motherOccupation,

                    'emergency_contact' => $emergencyContact,

                    'pincode' => $pincode,

                    'permanent_address' => $permanentAddress,

                    'current_address' => $currentAddress,

                    'state_id' => $state->id,

                    'district_id' => $district->id,

                    'photo' => $photo,

                    'birth_certificate' => $birthCertificate,

                    'aadhaar_doc' => $aadhaarDoc,

                    'family_photo' => $familyPhoto,

                    'father_aadhaar_doc' => $fatherAadhaarDoc,

                    'mother_aadhaar_doc' => $motherAadhaarDoc,

                    'class_id' => $class->id,

                    'section_id' => $section->id,
                ]);

                /*
                |--------------------------------------------------------------------------
                | ROLL NUMBER
                |--------------------------------------------------------------------------
                */

                $nextStudentId = (
                    Student::max('id') ?? 0
                ) + 1;

                $rollNumber = 'STD' . str_pad(

                    $nextStudentId,

                    5,

                    '0',

                    STR_PAD_LEFT
                );

                /*
                |--------------------------------------------------------------------------
                | CREATE STUDENT
                |--------------------------------------------------------------------------
                */

                Student::create([

                    'admission_id' => $admission->id,

                    'name' => $admission->name,

                    'email' => $admission->father_email,

                    'phone' => $admission->father_mobile,

                    'class_id' => $admission->class_id,

                    'section_id' => $admission->section_id,

                    'roll_number' => $rollNumber,

                    'status' => 1,
                ]);

                /*
                |--------------------------------------------------------------------------
                | PASSWORDS
                |--------------------------------------------------------------------------
                */

                $studentPassword = 'STD@' . rand(1000,9999);

                $parentPassword = 'PAR@' . rand(1000,9999);

                /*
                |--------------------------------------------------------------------------
                | REPOSITORY
                |--------------------------------------------------------------------------
                */

                $repository = app(
                    \App\Repositories\AdmissionRepository::class
                );

                /*
                |--------------------------------------------------------------------------
                | STUDENT USER
                |--------------------------------------------------------------------------
                */

                $repository->createStudentUser(

                    $admission,

                    $rollNumber,

                    $studentPassword
                );

                /*
                |--------------------------------------------------------------------------
                | PARENT USER
                |--------------------------------------------------------------------------
                */

                $repository->createParentUser(

                    $admission,

                    $fatherEmail,

                    $parentPassword
                );

                /*
                |--------------------------------------------------------------------------
                | SEND LOGIN MAIL
                |--------------------------------------------------------------------------
                */

                if(!empty($fatherEmail)){

                    $message = "

                    Dear Parent,

                    Student admission has been successfully imported into School ERP.

                    ==================================================

                    STUDENT LOGIN DETAILS

                    Student Name : {$name}

                    Registration No : {$rollNumber}

                    Username : {$rollNumber}

                    Password : {$studentPassword}

                    ==================================================

                    PARENT LOGIN DETAILS

                    Parent Name : {$fatherName}

                    Email : {$fatherEmail}

                    Password : {$parentPassword}

                    ==================================================

                    ACADEMIC DETAILS

                    Class : {$class->name}

                    Section : {$section->name}

                    Academic Year : ".date('Y')."-".(date('Y') + 1)."

                    ==================================================

                    Please change your password after first login.

                    Regards,
                    School ERP Team

                    ";

                    Mail::raw(

                        $message,

                        function($mail) use ($fatherEmail, $name){

                            $mail->to($fatherEmail)

                                ->subject(
                                    'School ERP Login Credentials - '.$name
                                );
                        }
                    );
                }

            }catch(\Throwable $e){

                dd([

                    'message' => $e->getMessage(),

                    'line' => $e->getLine(),

                    'file' => $e->getFile(),

                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | GET VALUE
    |--------------------------------------------------------------------------
    */

    private function getValue(
        array $row,
        array $keys,
        $default = null
    ){

        foreach($keys as $key){

            if(isset($row[$key])){

                return trim(
                    (string)$row[$key]
                );
            }
        }

        return $default;
    }

    /*
    |--------------------------------------------------------------------------
    | CLEAN NUMERIC
    |--------------------------------------------------------------------------
    */

    private function cleanNumeric($value)
    {
        if(is_numeric($value)){

            return number_format(
                $value,
                0,
                '',
                ''
            );
        }

        return preg_replace(
            '/[^0-9]/',
            '',
            (string)$value
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PARSE DATE
    |--------------------------------------------------------------------------
    */

    private function parseDate($value)
    {
        if(is_numeric($value)){

            return Carbon::instance(

                Date::excelToDateTimeObject(
                    $value
                )
            );
        }

        try{

            return Carbon::parse($value);

        }catch(\Exception $e){

            return null;
        }
    }
}