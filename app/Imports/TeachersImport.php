<?php

namespace App\Imports;

use App\Models\Teacher;
use App\Models\Subject;
use App\Models\SchoolClass;

use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class TeachersImport implements
    ToCollection,
    WithHeadingRow,
    SkipsEmptyRows
{
    /*
    |--------------------------------------------------------------------------
    | HEADING ROW
    |--------------------------------------------------------------------------
    */

    public function headingRow(): int
    {
        /*
        |--------------------------------------------------------------------------
        | YOUR EXCEL STRUCTURE
        |--------------------------------------------------------------------------
        |
        | Row 1 => Title
        | Row 2 => Empty
        | Row 3 => Section Title
        | Row 4 => Actual Headers
        |
        */

        return 4;
    }

    /*
    |--------------------------------------------------------------------------
    | COLLECTION
    |--------------------------------------------------------------------------
    */

    public function collection(Collection $rows)
    {
        /*
        |--------------------------------------------------------------------------
        | EMPTY CHECK
        |--------------------------------------------------------------------------
        */

        if($rows->count() == 0){

            Log::error(

                'NO TEACHER ROWS FOUND'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | LOOP ROWS
        |--------------------------------------------------------------------------
        */

        foreach($rows as $row){

            try{

                $row = $row->toArray();

                /*
                |--------------------------------------------------------------------------
                | BASIC INFO
                |--------------------------------------------------------------------------
                */

                $name = $this->getValue(
                    $row,
                    ['name']
                );

                $employeeId = $this->getValue(
                    $row,
                    ['employee_id']
                );

                $phone = $this->getValue(
                    $row,
                    ['phone']
                );

                /*
                |--------------------------------------------------------------------------
                | REMOVE EXCEL PHONE PREFIX
                |--------------------------------------------------------------------------
                */

                $phone = str_replace(
                    "'",
                    '',
                    $phone
                );

                $email = $this->getValue(
                    $row,
                    ['email']
                );

                $qualification = $this->getValue(
                    $row,
                    ['qualification']
                );

                $experience = $this->getValue(
                    $row,
                    ['experience']
                );

                $specialization = $this->getValue(
                    $row,
                    ['specialization']
                );

                $address = $this->getValue(
                    $row,
                    ['address']
                );

                $status = $this->getValue(
                    $row,
                    ['status']
                ) ?: 'active';

                /*
                |--------------------------------------------------------------------------
                | SUBJECTS / CLASSES
                |--------------------------------------------------------------------------
                */

                $subjectsRaw = $this->getValue(
                    $row,
                    ['subjects']
                );

                $classesRaw = $this->getValue(
                    $row,
                    ['classes']
                );

                /*
                |--------------------------------------------------------------------------
                | REQUIRED VALIDATION
                |--------------------------------------------------------------------------
                */

                $required = [

                    'name' => $name,

                    'employee_id' => $employeeId,

                    'phone' => $phone,

                    'email' => $email,
                ];

                foreach($required as $field => $value){

                    if(empty($value)){

                        Log::error(

                            'TEACHER IMPORT FAILED',

                            [

                                'field' => $field,

                                'row' => $row
                            ]
                        );

                        continue 2;
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | DUPLICATE EMAIL
                |--------------------------------------------------------------------------
                */

                if(
                    Teacher::where(
                        'email',
                        $email
                    )->exists()
                ){

                    Log::warning(

                        'DUPLICATE TEACHER EMAIL',

                        [

                            'email' => $email
                        ]
                    );

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | DUPLICATE EMPLOYEE ID
                |--------------------------------------------------------------------------
                */

                if(
                    Teacher::where(
                        'employee_id',
                        $employeeId
                    )->exists()
                ){

                    Log::warning(

                        'DUPLICATE EMPLOYEE ID',

                        [

                            'employee_id' => $employeeId
                        ]
                    );

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | USERNAME
                |--------------------------------------------------------------------------
                */

                $username = strtolower(

                    preg_replace(

                        '/\s+/',

                        '',

                        $name
                    )
                ) . rand(100,999);

                /*
                |--------------------------------------------------------------------------
                | PASSWORD
                |--------------------------------------------------------------------------
                */

                $plainPassword =

                    'TEA@' . rand(1000,9999);

                /*
                |--------------------------------------------------------------------------
                | CREATE TEACHER
                |--------------------------------------------------------------------------
                */

                $teacher = Teacher::create([

                    'name' => $name,

                    'employee_id' => $employeeId,

                    'phone' => $phone,

                    'email' => $email,

                    'username' => $username,

                    'qualification' => $qualification,

                    'experience' => $experience,

                    'specialization' => $specialization,

                    'address' => $address,

                    'status' => $status,

                    'password' => Hash::make(
                        $plainPassword
                    ),
                ]);

                /*
                |--------------------------------------------------------------------------
                | SUBJECTS
                |--------------------------------------------------------------------------
                */

                if(!empty($subjectsRaw)){

                    $subjectNames = explode(
                        ',',
                        $subjectsRaw
                    );

                    $subjectIds = [];

                    foreach($subjectNames as $subjectName){

                        $subject = Subject::whereRaw(

                            'LOWER(TRIM(name)) = ?',

                            [
                                strtolower(
                                    trim($subjectName)
                                )
                            ]

                        )->first();

                        if($subject){

                            $subjectIds[] =
                                $subject->id;
                        }
                    }

                    $teacher->subjects()
                        ->sync($subjectIds);
                }

                /*
                |--------------------------------------------------------------------------
                | CLASSES
                |--------------------------------------------------------------------------
                */

                if(!empty($classesRaw)){

                    $classNames = explode(
                        ',',
                        $classesRaw
                    );

                    $classIds = [];

                    foreach($classNames as $className){

                        $class = SchoolClass::whereRaw(

                            'LOWER(TRIM(name)) = ?',

                            [
                                strtolower(
                                    trim($className)
                                )
                            ]

                        )->first();

                        if($class){

                            $classIds[] =
                                $class->id;
                        }
                    }

                    $teacher->classes()
                        ->sync($classIds);
                }

                /*
                |--------------------------------------------------------------------------
                | SEND MAIL
                |--------------------------------------------------------------------------
                */

                if(!empty($email)){

                    $message = "

                    Dear {$name},

                    Your teacher account has been created successfully.

                    =====================================

                    LOGIN DETAILS

                    Username : {$username}

                    Password : {$plainPassword}

                    =====================================

                    Employee ID : {$employeeId}

                    =====================================

                    Please change your password after first login.

                    Regards,
                    School ERP Team

                    ";

                    Mail::raw(

                        $message,

                        function($mail)
                        use ($email, $name){

                            $mail->to($email)

                                ->subject(

                                    'Teacher Login Credentials - '

                                    .$name
                                );
                        }
                    );
                }

            }catch(\Throwable $e){

                Log::error(

                    'TEACHER IMPORT ERROR',

                    [

                        'message' =>
                            $e->getMessage(),

                        'line' =>
                            $e->getLine(),

                        'row' => $row ?? []
                    ]
                );
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
}