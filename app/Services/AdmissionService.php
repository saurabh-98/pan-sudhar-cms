<?php

namespace App\Services;

use App\Repositories\AdmissionRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\StudentCredentialsMail;
use App\Mail\ParentCredentialsMail;
use App\Mail\AdmissionSubmittedMail;
use App\Mail\AdmissionVerifiedMail;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Generator;
use App\Mail\PaymentVerifiedMail;
use App\Models\Admission;
use App\Models\Section;

class AdmissionService
{
    protected $admissionRepository;

    public function __construct(AdmissionRepository $admissionRepository)
    {
        $this->admissionRepository = $admissionRepository;
    }

    public function getAll()
    {
        return $this->admissionRepository->getAll();
    }

    public function find($id)
    {
        return $this->admissionRepository->find($id);
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVED ADMISSIONS
    |--------------------------------------------------------------------------
    */

    public function getApprovedAdmissions()
    {
        return $this->admissionRepository
            ->getApprovedAdmissions();
    }

    /* ================= STORE ================= */
    public function store($dto)
{
    try {

        $data = (array) $dto;

        /*
        |--------------------------------------------------------------------------
        | AADHAAR DUPLICATE CHECK
        |--------------------------------------------------------------------------
        */

        if (!empty($data['aadhaar'])) {

            $exists = \App\Models\Admission::where(

                'aadhaar',

                $data['aadhaar']

            )->exists();

            if ($exists) {

                throw new \Exception(

                    'Admission already exists with this Aadhaar'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | APPLICATION NUMBER GENERATION
        |--------------------------------------------------------------------------
        */

        $lastAdmission = \App\Models\Admission::latest('id')->first();

        $nextId = $lastAdmission
                    ? $lastAdmission->id + 1
                    : 1;

        $data['application_no'] =

            'APP-' .
            date('Y') .
            '-' .
            str_pad($nextId, 5, '0', STR_PAD_LEFT);

        /*
        |--------------------------------------------------------------------------
        | DEFAULT STATUS
        |--------------------------------------------------------------------------
        */

        $data['status'] = 'pending';

        /*
        |--------------------------------------------------------------------------
        | SAVE ADMISSION
        |--------------------------------------------------------------------------
        */

        return $this->admissionRepository->create($data);

    } catch (\Throwable $e) {

        Log::error('Admission Store Error', [

            'message' => $e->getMessage(),

            'line' => $e->getLine(),

            'file' => $e->getFile(),
        ]);

        throw $e;
    }
}
    /* ================= VERIFY ================= */

public function verify($id, $sectionId)
{
    DB::beginTransaction();

    try {

        /*
        |--------------------------------------------------------------------------
        | FIND ADMISSION
        |--------------------------------------------------------------------------
        */

        $admission = $this->find($id);

        if (!$admission) {

            throw new \Exception(

                'Admission not found'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ALREADY VERIFIED
        |--------------------------------------------------------------------------
        */

        if ($admission->section_id) {

            throw new \Exception(

                'Already verified'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SECTION
        |--------------------------------------------------------------------------
        */

        $section = Section::find($sectionId);

        if (!$section) {

            throw new \Exception(

                'Section not found'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SECTION FULL CHECK
        |--------------------------------------------------------------------------
        */

        if (

            $section->filled_seats >= $section->capacity
        ) {

            throw new \Exception(

                'Selected section is full'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE ADMISSION
        |--------------------------------------------------------------------------
        */

        $this->admissionRepository->update(

            $id,

            [

                /*
                |--------------------------------------------------------------------------
                | IMPORTANT FIX
                |--------------------------------------------------------------------------
                */

                'class_id' => $section->class_id,

                'section_id' => $section->id,

                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                'status' => 'payment_pending',

                'verification_status' => 'verified'
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | COMMIT
        |--------------------------------------------------------------------------
        */

        DB::commit();

        /*
        |--------------------------------------------------------------------------
        | REFRESH
        |--------------------------------------------------------------------------
        */

        $admission = $this->find($id);

        /*
        |--------------------------------------------------------------------------
        | SEND MAIL
        |--------------------------------------------------------------------------
        */

        try {

            $this->sendVerificationMail(

                $admission
            );

        } catch (\Throwable $e) {

            Log::error(

                'Verification Mail Error',

                [

                    'id' => $id,

                    'error' => $e->getMessage()
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */

        return [

            'success' => true,

            'message' => 'Verified & payment requested',

            'data' => $admission
        ];

    } catch (\Throwable $e) {

        /*
        |--------------------------------------------------------------------------
        | ROLLBACK
        |--------------------------------------------------------------------------
        */

        DB::rollBack();

        /*
        |--------------------------------------------------------------------------
        | LOG
        |--------------------------------------------------------------------------
        */

        Log::error(

            'Verification Error',

            [

                'id' => $id,

                'section_id' => $sectionId,

                'error' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile()
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */

        return [

            'success' => false,

            'message' => config('app.debug')

                ? $e->getMessage()

                : 'Verification failed'
        ];
    }
}

    /* ================= SEND VERIFICATION MAIL ================= */
    public function sendVerificationMail($admission)
    {
        try {

            $emails = collect([
                $admission->father_email,
                $admission->mother_email
            ])->filter()->unique();

            if ($emails->isEmpty()) {
                Log::warning('No email for verification mail', [
                    'admission_id' => $admission->id
                ]);
                return;
            }

            foreach ($emails as $email) {
                Mail::to($email)->send(new AdmissionVerifiedMail($admission));
                Log::info('Verification mail sent', ['email' => $email]);
            }

        } catch (\Throwable $e) {
            Log::error('Verification Mail Error', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /* ================= PAYMENT ================= */
    

    public function paymentSuccess($id, $paymentId, $utrNo = null)
    {
        DB::beginTransaction();

        try {

            $admission = Admission::lockForUpdate()->findOrFail($id);

            /* ❌ MUST BE VERIFIED FIRST */
            if (!$admission->section_id) {
                throw new \Exception('Please verify admission first');
            }

            /* ❌ ALREADY COMPLETED */
            if (in_array($admission->status, ['payment_received', 'approved'])) {
                throw new \Exception('Payment already processed');
            }

            /* ❌ INVALID FLOW */
            if (!in_array($admission->status, ['payment_pending', 'payment_submitted'])) {
                throw new \Exception('Invalid payment state');
            }

            /* 🔐 DUPLICATE UTR CHECK */
            if ($utrNo) {
                $exists = Admission::where('utr_no', $utrNo)
                            ->where('id', '!=', $id)
                            ->exists();

                if ($exists) {
                    throw new \Exception('Duplicate UTR detected');
                }
            }

            /* ✅ UPDATE */
            $admission->update([
                'payment_id' => $paymentId,
                'utr_no'     => $utrNo,
                'paid_at'    => now(),
                'status'     => 'payment_received'
            ]);

            DB::commit();

            /* 🔥 SEND MAIL AFTER COMMIT */
            $emails = array_filter([
                $admission->father_email,
                $admission->mother_email
            ]);

            if (!empty($emails)) {
                Mail::to($emails)->send(new PaymentVerifiedMail($admission));
            }

            return [
                'success' => true,
                'message' => 'Payment verified successfully'
            ];

        } catch (\Throwable $e) {

            DB::rollBack();

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function sendSubmissionMail($admission)
{
    try {

        /*
        |--------------------------------------------------------------------------
        | MEMORY LIMIT
        |--------------------------------------------------------------------------
        */

        ini_set('memory_limit', '512M');

        /*
        |--------------------------------------------------------------------------
        | RELOAD COMPLETE DATA
        |--------------------------------------------------------------------------
        */

        $admission = Admission::with([

            'studentClass',
            'section',
            'state',
            'district'

        ])->find($admission->id);

        /*
        |--------------------------------------------------------------------------
        | CHECK RECORD
        |--------------------------------------------------------------------------
        */

        if (!$admission) {

            Log::error(

                'Admission not found for mail'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | CHECK EMAILS
        |--------------------------------------------------------------------------
        */

        $emails = collect([

            $admission->father_email,

            $admission->mother_email

        ])

        ->filter()

        ->unique();

        if ($emails->isEmpty()) {

            Log::warning(

                'No parent email found',

                [

                    'admission_id' => $admission->id
                ]
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | GENERATE PDF FIRST
        |--------------------------------------------------------------------------
        */

        try {

            $pdf = Pdf::loadView(

                'pdf.admission-receipt',

                [

                    'admission' => $admission
                ]

            )

            ->setPaper('a4')

            ->output();

        } catch (\Throwable $e) {

            Log::error(

                'PDF Generation Failed',

                [

                    'error' => $e->getMessage()
                ]
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | SEND MAILS
        |--------------------------------------------------------------------------
        */

        foreach ($emails as $email) {

            try {

                Mail::send(

                    'emails.admission-submitted',

                    [

                        'admission' => $admission
                    ],

                    function ($message)

                    use (

                        $email,
                        $pdf,
                        $admission
                    ) {

                        $message

                            ->to($email)

                            ->subject(

                                'Admission Submitted Successfully'
                            )

                            ->attachData(

                                $pdf,

                                'Admission-' .

                                $admission->application_no .

                                '.pdf'
                            );
                    }
                );

                Log::info(

                    'Submission mail sent',

                    [

                        'email' => $email
                    ]
                );

            } catch (\Throwable $e) {

                Log::error(

                    'Submission mail failed',

                    [

                        'email' => $email,

                        'error' => $e->getMessage()
                    ]
                );
            }
        }

    } catch (\Throwable $e) {

        Log::error(

            'Submission Mail Error',

            [

                'error' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile()
            ]
        );
    }
}


   /* ================= APPROVE ================= */

public function approve($id)
{
    DB::beginTransaction();

    try {

        /*
        |--------------------------------------------------------------------------
        | FIND ADMISSION
        |--------------------------------------------------------------------------
        */

        $admission = $this->find($id);

        if (!$admission) {

            throw new \Exception(

                'Admission not found'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ALREADY APPROVED
        |--------------------------------------------------------------------------
        */

        if ($admission->status === 'approved') {

            throw new \Exception(

                'Admission already approved'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PAYMENT CHECK
        |--------------------------------------------------------------------------
        */

        if (

            !in_array(

                $admission->status,

                [

                    'paid',

                    'payment_received'
                ]
            )

        ) {

            throw new \Exception(

                'Payment required before approval'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | REGISTRATION NUMBER
        |--------------------------------------------------------------------------
        */

        $regNo =

            $admission->registration_no

            ??

            (

                'STU'

                . date('Y')

                . str_pad(

                    $admission->id,

                    5,

                    '0',

                    STR_PAD_LEFT
                )
            );

            /*
        |--------------------------------------------------------------------------
        | ROLL NUMBER
        |--------------------------------------------------------------------------
        */

        $lastRoll = Admission::where(

                'class_id',
                $admission->class_id

            )

            ->whereYear(

                'created_at',
                date('Y')

            )

            ->whereNotNull('roll_no')

            ->orderByDesc('id')

            ->first();

        $nextRollNumber = 1;

        if($lastRoll){

            $nextRollNumber =
            (int)$lastRoll->roll_no + 1;
        }

        $rollNo = str_pad(

            $nextRollNumber,

            3,

            '0',

            STR_PAD_LEFT
        );

        /*
        |--------------------------------------------------------------------------
        | PASSWORDS
        |--------------------------------------------------------------------------
        */

        $studentPassword = Str::password(10);

        $parentPassword = Str::password(10);

        /*
        |--------------------------------------------------------------------------
        | CREATE STUDENT
        |--------------------------------------------------------------------------
        */

        $this->admissionRepository
            ->createStudentIfNotExists(

                $admission
            );

        /*
        |--------------------------------------------------------------------------
        | CREATE USERS
        |--------------------------------------------------------------------------
        */

        $this->admissionRepository
            ->createStudentUser(

                $admission,

                $regNo,

                $studentPassword
            );

        $this->admissionRepository
            ->createParentUser(

                $admission,

                $parentPassword
            );

        /*
        |--------------------------------------------------------------------------
        | REFRESH DATA
        |--------------------------------------------------------------------------
        */

        $admission = $this->find($id);

        /*
        |--------------------------------------------------------------------------
        | QR CODE
        |--------------------------------------------------------------------------
        */

        $qrPath = null;

        try {

            $qrPath =

                'qrcodes/'

                . $regNo
                . $rollNo

                . '.png';

            $qr = new Generator();

            $qr->setBackend('gd');

            Storage::disk('public')->put(

                $qrPath,

                $qr->format('png')

                    ->size(200)

                    ->generate(

                        url('/student/login')
                    )
            );

        } catch (\Throwable $e) {

            Log::error(

                'QR Generation Failed',

                [

                    'error' => $e->getMessage()
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PDF GENERATION
        |--------------------------------------------------------------------------
        */

        $receiptPdf = null;

        $idCardPdf = null;

        /*
        |--------------------------------------------------------------------------
        | RECEIPT PDF
        |--------------------------------------------------------------------------
        */

        try {

            $receiptPdf = Pdf::loadView(

                'pdf.receipt',

                [

                    'name' => $admission->name,

                    'regNo' => $regNo,
                    'rollNo' => $rollNo
                ]

            )->output();

        } catch (\Throwable $e) {

            Log::error(

                'Receipt PDF Failed',

                [

                    'error' => $e->getMessage()
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ID CARD PDF
        |--------------------------------------------------------------------------
        */

        try {

            $idCardPdf = Pdf::loadView(

                'pdf.idcard',

                [

                    'name' => $admission->name,

                    'regNo' => $regNo,
                    'rollNo' => $rollNo,

                    'class' => optional(

                        $admission->studentClass
                    )->name,

                    'photo' => $admission->photo,

                    'qr' => $qrPath

                        ? storage_path(

                            'app/public/' . $qrPath
                        )

                        : null
                ]

            )->output();

        } catch (\Throwable $e) {

            Log::error(

                'ID Card PDF Failed',

                [

                    'error' => $e->getMessage()
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STUDENT EMAIL
        |--------------------------------------------------------------------------
        */

        try {

            $studentEmail =

                strtolower($regNo)

                . '@school.com';

            Mail::to($studentEmail)->send(

                new StudentCredentialsMail(

                    $admission->name,

                    $studentEmail,

                    $studentPassword,

                    $regNo,
                    $rollNo,

                    $receiptPdf,

                    $idCardPdf
                )
            );

            Log::info(

                'Student Mail Sent',

                [

                    'email' => $studentEmail
                ]
            );

        } catch (\Throwable $e) {

            Log::error(

                'Student Mail Failed',

                [

                    'error' => $e->getMessage()
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PARENT EMAILS
        |--------------------------------------------------------------------------
        */

        $parentEmails = collect([

            $admission->father_email,

            $admission->mother_email

        ])

        ->filter()

        ->unique();

        /*
        |--------------------------------------------------------------------------
        | SEND PARENT MAILS
        |--------------------------------------------------------------------------
        */

        foreach ($parentEmails as $email) {

            try {

                Mail::to($email)->send(

                    new ParentCredentialsMail(

                        $admission->name,

                        $email,

                        $parentPassword
                    )
                );

            } catch (\Throwable $e) {

                Log::error(

                    'Parent Mail Failed',

                    [

                        'email' => $email,

                        'error' => $e->getMessage()
                    ]
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS
        |--------------------------------------------------------------------------
        */

        $updatedAdmission = $this->admissionRepository
            ->updateStatus(

                $id,

                'approved'
            );

        /*
        |--------------------------------------------------------------------------
        | VERIFY UPDATE
        |--------------------------------------------------------------------------
        */

        if (

            !$updatedAdmission

            ||

            $updatedAdmission->status !== 'approved'
        ) {

            throw new \Exception(

                'Failed to approve admission'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SAVE REGISTRATION NUMBER
        |--------------------------------------------------------------------------
        */

        $updatedAdmission->update([

            'registration_no' => $regNo,

            'roll_no' => $rollNo
        ]);

        /*
        |--------------------------------------------------------------------------
        | COMMIT
        |--------------------------------------------------------------------------
        */

        DB::commit();

        /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */

        return $updatedAdmission->fresh();

    } catch (\Throwable $e) {

        /*
        |--------------------------------------------------------------------------
        | ROLLBACK
        |--------------------------------------------------------------------------
        */

        DB::rollBack();

        /*
        |--------------------------------------------------------------------------
        | LOG
        |--------------------------------------------------------------------------
        */

        Log::error(

            'Approve Error',

            [

                'id' => $id,

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile()
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | THROW
        |--------------------------------------------------------------------------
        */

        throw $e;
    }
}
    /*
|--------------------------------------------------------------------------
| REJECT ADMISSION
|--------------------------------------------------------------------------
*/

    public function reject($id, $remark = null)
    {
        return $this->admissionRepository->updateStatus(

            $id,

            'rejected',

            [

                'rejection_remark' => $remark,

                'rejected_at' => now(),

                'rejected_by' => auth()->id()
            ]
        );
    }


  /* ================= FILE UPLOAD ================= */

    public function uploadFile(?UploadedFile $file, $path)
    {
        if (!$file) {

            return null;
        }

        try {

            /*
            |--------------------------------------------------------------------------
            | ALLOWED MIME TYPES
            |--------------------------------------------------------------------------
            */

            $allowedMime = [

                'image/jpeg',

                'image/png',

                'application/pdf'
            ];

            /*
            |--------------------------------------------------------------------------
            | REAL MIME CHECK
            |--------------------------------------------------------------------------
            */

            $realMime = mime_content_type(

                $file->getRealPath()
            );

            if (

                !in_array(

                    $realMime,

                    $allowedMime
                )

            ) {

                throw new \Exception(

                    'Invalid file type'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | FILE SIZE LIMIT
            |--------------------------------------------------------------------------
            */

            if (

                $file->getSize()

                > 2 * 1024 * 1024
            ) {

                throw new \Exception(

                    'File too large'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | UNIQUE FILE NAME
            |--------------------------------------------------------------------------
            */

            $filename =

                Str::uuid()

                . '.'

                . $file->extension();

            /*
            |--------------------------------------------------------------------------
            | STORE IN PUBLIC DISK
            |--------------------------------------------------------------------------
            */

            return $file->storeAs(

                $path,

                $filename,

                'public'
            );

        } catch (\Throwable $e) {

            Log::error(

                'File Upload Error',

                [

                    'error' => $e->getMessage()
                ]
            );

            throw $e;
        }
    }
}