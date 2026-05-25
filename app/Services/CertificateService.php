<?php

namespace App\Services;

use App\DTO\CertificateDTO;
use App\Repositories\CertificateRepository;
use Illuminate\Support\Facades\DB;

class CertificateService
{
    public function __construct(
        protected CertificateRepository $repo
    ) {}

    /* =========================================================
     | ALL CERTIFICATES
     *=========================================================*/
    public function getAll()
    {
        return $this->repo->all();
    }

    /* =========================================================
     | FIND CERTIFICATE
     *=========================================================*/
    public function find($id)
    {
        return $this->repo->find($id);
    }

    /* =========================================================
     | GENERATE CERTIFICATE
     *=========================================================*/
    public function generate(CertificateDTO $dto)
    {
        DB::beginTransaction();

        try {

            $certificate = $this->repo->create(
                $dto->toArray()
            );

            DB::commit();

            return $certificate;

        } catch (\Exception $e) {

            DB::rollBack();

            throw $e;
        }
    }

    /* =========================================================
     | DELETE CERTIFICATE
     *=========================================================*/
    public function delete($id)
    {
        return $this->repo->delete($id);
    }

    /* =========================================================
     | VERIFY CERTIFICATE
     *=========================================================*/
    public function verify($certificateNo)
    {
        return $this->repo->verify($certificateNo);
    }

    /* =========================================================
     | DOWNLOAD CERTIFICATE
     *=========================================================*/
    public function download($id)
    {
        return $this->repo->download($id);
    }

    /* =========================================================
     | TEMPLATE LIST
     *=========================================================*/
    public function getTemplates()
    {
        return $this->repo->getTemplates();
    }

    public function getActiveTemplates()
    {
        return $this->repo
            ->getTemplates()
            ->where('status', 1);
    }

    /* =========================================================
     | STORE TEMPLATE
     *=========================================================*/
    public function storeTemplate(array $data)
    {
        DB::beginTransaction();

        try {

            $template = $this->repo->storeTemplate($data);

            DB::commit();

            return $template;

        } catch (\Exception $e) {

            DB::rollBack();

            throw $e;
        }
    }

    /* =========================================================
     | FIND TEMPLATE
     *=========================================================*/
    public function findTemplate($id)
    {
        return $this->repo->findTemplate($id);
    }

    /* =========================================================
     | UPDATE TEMPLATE
     *=========================================================*/
    public function updateTemplate($id, array $data)
    {
        DB::beginTransaction();

        try {

            $template = $this->repo->updateTemplate(
                $id,
                $data
            );

            DB::commit();

            return $template;

        } catch (\Exception $e) {

            DB::rollBack();

            throw $e;
        }
    }

    /* =========================================================
     | DELETE TEMPLATE
     *=========================================================*/
    public function deleteTemplate($id)
    {
        return $this->repo->deleteTemplate($id);
    }
}