<?php

namespace App\Repositories;

use App\Models\Certificate;
use App\Models\CertificateTemplate;

class CertificateRepository
{
    /* =========================================================
     | ALL CERTIFICATES
     *=========================================================*/
    public function all()
    {
        return Certificate::latest()->get();
    }

    /* =========================================================
     | FIND CERTIFICATE
     *=========================================================*/
    public function find($id)
    {
        return Certificate::findOrFail($id);
    }

    /* =========================================================
     | CREATE CERTIFICATE
     *=========================================================*/
    public function create(array $data)
    {
        return Certificate::create($data);
    }

    /* =========================================================
     | DELETE CERTIFICATE
     *=========================================================*/
    public function delete($id)
    {
        $certificate = $this->find($id);

        return $certificate->delete();
    }

    /* =========================================================
     | VERIFY CERTIFICATE
     *=========================================================*/
    public function verify($certificateNo)
    {
        return Certificate::where(
            'certificate_no',
            $certificateNo
        )->first();
    }

    /* =========================================================
     | DOWNLOAD CERTIFICATE
     *=========================================================*/
    public function download($id)
    {
        $certificate = $this->find($id);

        return response()->download(
            public_path(
                'uploads/certificates/' .
                $certificate->file
            )
        );
    }

    /* =========================================================
     | TEMPLATE LIST
     *=========================================================*/
    public function getTemplates()
    {
        return CertificateTemplate::latest()->get();
    }

    /* =========================================================
     | FIND TEMPLATE
     *=========================================================*/
    public function findTemplate($id)
    {
        return CertificateTemplate::findOrFail($id);
    }

    /* =========================================================
     | STORE TEMPLATE
     *=========================================================*/
    public function storeTemplate(array $data)
    {
        return CertificateTemplate::create($data);
    }

    /* =========================================================
     | UPDATE TEMPLATE
     *=========================================================*/
    public function updateTemplate($id, array $data)
    {
        $template = $this->findTemplate($id);

        $template->update($data);

        return $template;
    }

    /* =========================================================
     | DELETE TEMPLATE
     *=========================================================*/
    public function deleteTemplate($id)
    {
        $template = $this->findTemplate($id);

        return $template->delete();
    }
}